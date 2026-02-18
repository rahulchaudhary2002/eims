<?php

namespace App\Support;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class SimpleXlsxReader
{
    /**
     * @return array<string, array<int, array<string, string>>>
     */
    public function read(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new RuntimeException('Unable to open the uploaded Excel file.');
        }

        $sharedStrings = $this->parseSharedStrings($zip);
        $sheetTargets = $this->getSheetTargets($zip);
        $sheets = [];

        foreach ($sheetTargets as $sheetName => $target) {
            $sheetXml = $zip->getFromName($target);
            if ($sheetXml === false) {
                continue;
            }

            $rows = $this->parseWorksheet($sheetXml, $sharedStrings);
            $sheets[$sheetName] = $rows;
        }

        $zip->close();

        return $sheets;
    }

    /**
     * @return array<int, string>
     */
    private function parseSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml === false) {
            return [];
        }

        $shared = [];
        $sharedXml = simplexml_load_string($xml);
        if (!$sharedXml instanceof SimpleXMLElement) {
            return $shared;
        }

        foreach ($sharedXml->xpath('//*[local-name()="si"]') ?: [] as $si) {
            $texts = $si->xpath('.//*[local-name()="t"]') ?: [];
            $value = '';
            foreach ($texts as $textNode) {
                $value .= (string) $textNode;
            }
            $shared[] = trim($value);
        }

        return $shared;
    }

    /**
     * @return array<string, string>
     */
    private function getSheetTargets(ZipArchive $zip): array
    {
        $workbookXml = $zip->getFromName('xl/workbook.xml');
        $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($workbookXml === false || $relsXml === false) {
            throw new RuntimeException('Invalid Excel workbook structure.');
        }

        $rels = [];
        $relsData = simplexml_load_string($relsXml);
        if ($relsData instanceof SimpleXMLElement) {
            foreach ($relsData->xpath('//*[local-name()="Relationship"]') ?: [] as $relationship) {
                $rels[(string) $relationship['Id']] = 'xl/' . ltrim((string) $relationship['Target'], '/');
            }
        }

        $targets = [];
        $workbookData = simplexml_load_string($workbookXml);
        if (!$workbookData instanceof SimpleXMLElement) {
            return $targets;
        }

        foreach ($workbookData->xpath('//*[local-name()="sheets"]/*[local-name()="sheet"]') ?: [] as $sheet) {
            $name = (string) $sheet['name'];
            $relation = (string) $sheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];

            if ($name !== '' && isset($rels[$relation])) {
                $targets[$name] = $rels[$relation];
            }
        }

        return $targets;
    }

    /**
     * @param  array<int, string>  $sharedStrings
     * @return array<int, array<string, string>>
     */
    private function parseWorksheet(string $xml, array $sharedStrings): array
    {
        $sheetData = simplexml_load_string($xml);
        if (!$sheetData instanceof SimpleXMLElement) {
            return [];
        }

        $rows = [];
        $headers = [];

        foreach ($sheetData->xpath('//*[local-name()="sheetData"]/*[local-name()="row"]') ?: [] as $rowNode) {
            $rowNumber = (int) $rowNode['r'];
            $indexedValues = [];
            $maxColumn = 0;

            foreach ($rowNode->xpath('./*[local-name()="c"]') ?: [] as $cellNode) {
                $cellRef = (string) $cellNode['r'];
                $column = preg_replace('/\d+/', '', $cellRef) ?: 'A';
                $columnIndex = $this->columnToIndex($column);
                $indexedValues[$columnIndex] = $this->extractCellValue($cellNode, $sharedStrings);
                $maxColumn = max($maxColumn, $columnIndex);
            }

            if ($maxColumn === 0) {
                continue;
            }

            $values = [];
            for ($i = 1; $i <= $maxColumn; $i++) {
                $values[] = trim((string) ($indexedValues[$i] ?? ''));
            }

            if ($this->rowIsEmpty($values)) {
                continue;
            }

            if (empty($headers)) {
                $headers = array_map([$this, 'normalizeHeader'], $values);
                continue;
            }

            $row = ['__row_number' => (string) $rowNumber];

            foreach ($headers as $index => $header) {
                if ($header === '') {
                    continue;
                }

                $row[$header] = $values[$index] ?? '';
            }

            $rows[] = $row;
        }

        return $rows;
    }

    private function extractCellValue(SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) $cell['t'];
        $valueNodes = $cell->xpath('./*[local-name()="v"]') ?: [];
        $rawValue = trim((string) ($valueNodes[0] ?? ''));

        if ($type === 's') {
            $index = (int) $rawValue;
            return (string) ($sharedStrings[$index] ?? '');
        }

        if ($type === 'inlineStr') {
            $texts = $cell->xpath('.//*[local-name()="is"]//*[local-name()="t"]') ?: [];
            $value = '';
            foreach ($texts as $text) {
                $value .= (string) $text;
            }

            return trim($value);
        }

        return $rawValue;
    }

    private function columnToIndex(string $letters): int
    {
        $result = 0;
        $letters = strtoupper($letters);

        for ($i = 0; $i < strlen($letters); $i++) {
            $result = ($result * 26) + (ord($letters[$i]) - 64);
        }

        return $result;
    }

    private function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        $header = preg_replace('/[^a-z0-9]+/', '_', $header) ?? '';
        return trim($header, '_');
    }

    /**
     * @param  array<int, string>  $values
     */
    private function rowIsEmpty(array $values): bool
    {
        foreach ($values as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
