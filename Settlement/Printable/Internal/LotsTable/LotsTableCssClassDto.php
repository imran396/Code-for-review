<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-04, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Settlement\Printable\Internal\LotsTable;


use Sam\Core\Service\CustomizableClass;

/**
 * Contains CSS class names for Settlement Lots table columns.
 *
 * Class LotsTableCssClassDto
 * @package Sam\Settlement\Printable
 */
final class LotsTableCssClassDto extends CustomizableClass
{
    public string $commission = 'item-comm';
    public string $estimates = 'item-est';
    public string $fee = 'item-fee';
    public string $hammerPrice = 'item-hammer-price';
    public string $lotName = 'item-lot-name';
    public string $lotNum = 'item-lot-num';
    public string $quantity = 'item-quantity';
    public string $saleLotNumSaleNum = 'item-saleLotNum-SaleNum';
    public string $saleName = 'item-sale-name';
    public string $subTotal = 'item-sub-total';
    public string $taxCommission = 'item-tax-comm';
    public string $taxHp = 'item-tax-hp';

    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
