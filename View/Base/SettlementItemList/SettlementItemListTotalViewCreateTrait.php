<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList;

/**
 * Trait SettlementItemListTotalViewCreateTrait
 * @package Sam\View\Base\SettlementItemList
 */
trait SettlementItemListTotalViewCreateTrait
{
    protected ?SettlementItemListTotalView $settlementItemListTotalView = null;

    /**
     * @return SettlementItemListTotalView
     */
    protected function createSettlementItemListTotalView(): SettlementItemListTotalView
    {
        return $this->settlementItemListTotalView ?: SettlementItemListTotalView::new();
    }

    /**
     * @param SettlementItemListTotalView $settlementItemListTotalView
     * @return static
     * @internal
     */
    public function setSettlementItemListTotalView(SettlementItemListTotalView $settlementItemListTotalView): static
    {
        $this->settlementItemListTotalView = $settlementItemListTotalView;
        return $this;
    }
}
