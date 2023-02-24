<?php


namespace Sam\Core\Constants;

/**
 * Class AuctionInc
 * @package Sam\Core\Constants
 */
class AuctionInc
{
    public const WEIGHT_UNIT_LBS = 1;
    public const WEIGHT_UNIT_OZ = 2;
    public const WEIGHT_UNIT_KGS = 3;

    public const WEIGHT_UOM_VALUE = [
        self::WEIGHT_UNIT_LBS => 'LBS',
        self::WEIGHT_UNIT_OZ => 'OZ',
        self::WEIGHT_UNIT_KGS => 'KGS',
    ];

    public const DIMENSION_UNIT_IN = 1;
    public const DIMENSION_UNIT_CM = 2;

    public const DIMENSION_UOM_VALUE = [
        self::DIMENSION_UNIT_IN => 'IN',
        self::DIMENSION_UNIT_CM => 'CM',
    ];
}
