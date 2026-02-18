<?php

namespace App\Support;

use RuntimeException;
use ZipArchive;

class SimpleXlsxBuilder
{
    /**
     * @param  array<string, array<int, array<int, string|int|float>>>  $sheets
     */
    public function build(array $sheets): string
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'bulk-import-template-');
        if ($tempPath === false) {
            throw new RuntimeException('Unable to create temporary file for template.');
        }

        $zip = new ZipArchive();
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Unable to create template workbook.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml(count($sheets)));
        $zip->addFromString('_rels/.rels', $this->rootRelsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml(array_keys($sheets)));
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml(count($sheets)));
        $zip->addFromString('xl/styles.xml', $this->stylesXml());

        $index = 1;
        foreach ($sheets as $rows) {
            $zip->addFromString("xl/worksheets/sheet{$index}.xml", $this->sheetXml($rows));
            $index++;
        }

        $zip->close();

        return $tempPath;
    }

    private function contentTypesXml(int $sheetCount): string
    {
        $overrides = '';
        for ($i = 1; $i <= $sheetCount; $i++) {
            $overrides .= '<Override PartName="/xl/worksheets/sheet' . $i . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . $overrides
            . '</Types>';
    }

    private function rootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    /**
     * @param  array<int, string>  $sheetNames
     */
    private function workbookXml(array $sheetNames): string
    {
        $sheets = '';
        foreach ($sheetNames as $index => $sheetName) {
            $escapedName = htmlspecialchars($sheetName, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $sheetId = $index + 1;
            $sheets .= '<sheet name="' . $escapedName . '" sheetId="' . $sheetId . '" r:id="rId' . $sheetId . '"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>' . $sheets . '</sheets>'
            . '</workbook>';
    }

    private function workbookRelsXml(int $sheetCount): string
    {
        $relationships = '';
        for ($i = 1; $i <= $sheetCount; $i++) {
            $relationships .= '<Relationship Id="rId' . $i . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $i . '.xml"/>';
        }

        $relationships .= '<Relationship Id="rId' . ($sheetCount + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . $relationships
            . '</Relationships>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
            . '<borders count="1"><border/></borders>'
            . '<cellStyleXfs count="1"><xf/></cellStyleXfs>'
            . '<cellXfs count="1"><xf xfId="0"/></cellXfs>'
            . '</styleSheet>';
    }

    /**
     * @param  array<int, array<int, string|int|float>>  $rows
     */
    private function sheetXml(array $rows): string
    {
        $sheetData = '';
        foreach ($rows as $rowIndex => $cells) {
            $excelRow = $rowIndex + 1;
            $sheetData .= '<row r="' . $excelRow . '">';

            foreach ($cells as $columnIndex => $value) {
                $columnName = $this->indexToColumn($columnIndex + 1);
                $cellRef = $columnName . $excelRow;
                $cellValue = htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

                $sheetData .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . $cellValue . '</t></is></c>';
            }

            $sheetData .= '</row>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetData>' . $sheetData . '</sheetData>'
            . '</worksheet>';
    }

    private function indexToColumn(int $index): string
    {
        $column = '';
        while ($index > 0) {
            $index--;
            $column = chr(65 + ($index % 26)) . $column;
            $index = intdiv($index, 26);
        }

        return $column;
    }
}
