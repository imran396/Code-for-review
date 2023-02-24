<?php

namespace Sam\Core\Constants;

/**
 * Class Consignor
 * @package Sam\Core\Constants
 */
class Consignor
{
    // consignor.consignor_tax_hp_type
    public const TAX_HP_EXCLUSIVE = 1;
    public const TAX_HP_INCLUSIVE = 2;
    /** @var int[] */
    public static array $consignorTaxHpTypes = [self::TAX_HP_EXCLUSIVE, self::TAX_HP_INCLUSIVE];
}
