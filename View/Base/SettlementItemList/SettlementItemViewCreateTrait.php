<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList;

/**
 * Trait SettlementItemViewCreateTrait
 * @package Sam\View\Base\SettlementItemList
 */
trait SettlementItemViewCreateTrait
{
    protected ?SettlementItemView $settlementItemView = null;

    /**
     * @return SettlementItemView
     */
    protected function createSettlementItemView(): SettlementItemView
    {
        return $this->settlementItemView ?: SettlementItemView::new();
    }

    /**
     * @param SettlementItemView $settlementItemView
     * @return static
     * @internal
     */
    public function setSettlementItemView(SettlementItemView $settlementItemView): static
    {
        $this->settlementItemView = $settlementItemView;
        return $this;
    }
}
