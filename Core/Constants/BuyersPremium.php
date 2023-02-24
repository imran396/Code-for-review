<?php
/**
 * Created by PhpStorm.
 * User: boanerge
 * Date: 4/12/2017
 * Time: 11:18 AM
 */

namespace Sam\Core\Constants;

/**
 * Class BuyersPremium
 * @package Sam\Core\Constants
 */
class BuyersPremium
{
    // Mode values
    public const MODE_GREATER = '>';
    public const MODE_SUM = '+';
    /** @var string[] */
    public static array $rangeModes = [self::MODE_GREATER, self::MODE_SUM];

    // Mode names
    public const MODE_NAME_GREATER = "greater";
    public const MODE_NAME_SUM = "sum";
    /** @var string[] */
    public static array $rangeModeNames = [
        self::MODE_GREATER => self::MODE_NAME_GREATER,
        self::MODE_SUM => self::MODE_NAME_SUM,
    ];
    /** @var string[] */
    public static array $rangeModeDescriptions = [
        self::MODE_GREATER => '> (Greater of fixed and percentage)',
        self::MODE_SUM => '+ (Sum of fixed and percentage)',
    ];

    public const RANGE_CALC_SLIDING = 'sliding';
    public const RANGE_CALC_CUMULATIVE_TIERED = 'tiered';
    /** @var string[] */
    public static array $rangeCalculations = [self::RANGE_CALC_SLIDING, self::RANGE_CALC_CUMULATIVE_TIERED,];
    /** @var string[] */
    public static array $rangeCalculationNames = [
        self::RANGE_CALC_SLIDING => 'Sliding',
        self::RANGE_CALC_CUMULATIVE_TIERED => 'Cumulative Tiered',
    ];

    // Default values for markers that can be used in CSV and SOAP inputs
    public const CLEAR_VALUE_MARKER = '~~CLEAR~~';
    public const DEFAULT_VALUE_MARKER = '~~DEFAULT~~';

    // CSV format delimiters. Format: start:fixed-percent-mode|start:fixed-percent-mode
    public const RANGES_DELIMITER = '|';
    public const AMOUNT_DELIMITER = ':';
    public const SET_DELIMITER = '-';

    // BP Rule Options
    public const BPRULE_DEFAULT = 'default';
    public const BPRULE_INDIVIDUAL = 'individual';
}
