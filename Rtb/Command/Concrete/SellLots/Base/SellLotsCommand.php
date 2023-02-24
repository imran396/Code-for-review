<?php
/**
 * Parent handler for SellLots command coming from any console
 *
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base;

use Sam\Core\Service\CustomizableClass;

class SellLotsCommand extends CustomizableClass
{
    /**
     * Should be null by default. Means no quantity defined, eg. when Group(Choice)
     */
    public readonly ?int $quantity;
    public readonly string $lotItemIdList;
    public readonly bool $onlySellRunningLot;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $quantity null when it isn't quantity-grouping
     * @param string $lotItemIdList pass running lot item id there when you want to sell only main lot of the group
     * @return $this
     */
    public function construct(?int $quantity, string $lotItemIdList = '', bool $onlySellRunningLot = false): static
    {
        $this->quantity = $quantity;
        $this->lotItemIdList = $lotItemIdList;
        $this->onlySellRunningLot = $onlySellRunningLot;
        return $this;
    }

    /**
     * Special constructor for the case, when we want to sell single running lot.
     * @return $this
     */
    public function constructToOnlySellRunningLot(): static
    {
        return $this->construct(null, '', true);
    }
}
