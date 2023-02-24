<?php
/**
 * SAM-5495: Rtb server and daemon refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Sell;

/**
 * Trait SellLotNotifierCreateTrait
 * @package Sam\Rtb\Sell
 */
trait SellLotNotifierCreateTrait
{
    /**
     * @var SellLotNotifier|null
     */
    protected ?SellLotNotifier $sellLotNotifier = null;

    /**
     * @return SellLotNotifier
     */
    protected function createSellLotNotifier(): SellLotNotifier
    {
        return $this->sellLotNotifier ?: SellLotNotifier::new();
    }

    /**
     * @param SellLotNotifier $sellLotNotifier
     * @return static
     * @internal
     */
    public function setSellLotNotifier(SellLotNotifier $sellLotNotifier): static
    {
        $this->sellLotNotifier = $sellLotNotifier;
        return $this;
    }
}
