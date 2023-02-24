<?php

namespace Sam\Core\Constants;

/**
 * Class PasswordStrength
 * @package Sam\Core\Constants
 */
class PasswordStrength
{
    // Score Statuses
    public const VERY_WEAK = 1;
    public const WEAK = 2;
    public const GOOD = 3;
    public const STRONG = 4;
    public const VERY_STRONG = 5;

    /** @var string[] */
    public static array $scoreStatusNames = [
        self::VERY_WEAK => 'Very weak',
        self::WEAK => 'Weak',
        self::GOOD => 'Good',
        self::STRONG => 'Strong',
        self::VERY_STRONG => 'Very strong',
    ];

    /** @var float[] */
    public static array $scoreAmounts = [
        self::VERY_WEAK => 0.1,
        self::WEAK => 0.25,
        self::GOOD => 0.4,
        self::STRONG => 0.6,
        self::VERY_STRONG => 1,
    ];

    /** @var string[] */
    public static array $indicatorClasses = [
        self::VERY_WEAK => 'password-indicator-very-weak',       // red
        self::WEAK => 'password-indicator-weak',                 // orange
        self::GOOD => 'password-indicator-good',                 // yellow
        self::STRONG => 'password-indicator-strong',             // green
        self::VERY_STRONG => 'password-indicator-very-strong',   // blue
    ];

    /** @var int[] */
    public static array $indicatorPercents = [
        self::VERY_WEAK => 20,
        self::WEAK => 40,
        self::GOOD => 60,
        self::STRONG => 80,
        self::VERY_STRONG => 100,
    ];
}
