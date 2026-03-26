<?php

namespace App\Enums;

enum BloodType: string
{
    case A_POSITIVE = 'a_positive';
    case A_NEGATIVE = 'a_negative';
    case B_POSITIVE = 'b_positive';
    case B_NEGATIVE = 'b_negative';
    case AB_POSITIVE = 'ab_positive';
    case AB_NEGATIVE = 'ab_negative';
    case O_POSITIVE = 'o_positive';
    case O_NEGATIVE = 'o_negative';

    /**
     * Get a human-readable label for this enum case.
     *
     * Provides a display-friendly string intended for UI, logs, or selection
     * lists. Implementations may return a static value, a translated string,
     * or a computed label based on the enum case.
     *
     * @return string Human-readable label for the enum case.
     */
    public function label(): string
    {
        return match ($this) {
            self::A_POSITIVE => 'A+',
            self::A_NEGATIVE => 'A-',
            self::B_POSITIVE => 'B+',
            self::B_NEGATIVE => 'B-',
            self::AB_POSITIVE => 'AB+',
            self::AB_NEGATIVE => 'AB-',
            self::O_POSITIVE => 'O+',
            self::O_NEGATIVE => 'O-',
        };
    }
}
