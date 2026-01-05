<?php

namespace App\Enums;

enum QuestionCategory: string
{
    case ACADEMICS       = 'academics';
    case ADMISSIONS      = 'admissions';
    case CAMPUS_LIFE     = 'campus_life';
    case COLLEGES        = 'colleges';
    case ENTRANCE_EXAMS  = 'entrance_exams';
    case EVENTS          = 'events';
    case EXAMS           = 'exams';
    case GENERAL         = 'general';
    case PROGRAMS        = 'programs';
    case SCHOLARSHIPS    = 'scholarships';

    public function label(): string
    {
        return match ($this) {
            self::ACADEMICS      => 'Academics',
            self::ADMISSIONS     => 'Admissions',
            self::CAMPUS_LIFE    => 'Campus Life',
            self::COLLEGES       => 'Colleges',
            self::ENTRANCE_EXAMS => 'Entrance Exams',
            self::EVENTS         => 'Events',
            self::EXAMS          => 'Exams',
            self::GENERAL        => 'General',
            self::PROGRAMS       => 'Programs',
            self::SCHOLARSHIPS   => 'Scholarships',
        };
    }

    /** For sidebar list */
    public static function all(): array
    {
        return self::cases();
    }
}
