<?php
/**
 * Dummy service for stubbing LotAmountRenderer in unit tests.
 *
 * SAM-10339: Fetch US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE from entity accounts
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 23, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render\Amount\Dummy;

use AuctionLotItem;
use LotItem;
use Sam\Core\Service\Dummy\DummyServiceTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;

/**
 * Class LotRenderer
 * @package Sam\Lot\Render
 */
class DummyLotAmountRenderer implements LotAmountRendererInterface
{
    use DummyServiceTrait;

    // --- Lot Estimates ---

    /**
     * {@inheritDoc}
     */
    public function makeEstimates(
        ?float $lowEstimate,
        ?float $highEstimate,
        string $currencySign,
        bool $isShowLowEst,
        bool $isShowHighEst
    ): string {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function renderEstimates(
        LotItem $lotItem,
        string $currencySign,
        array $optionals = []
    ): string {
        return $this->toString(func_get_args());
    }

    // --- Quantity ---

    /**
     * {@inheritDoc}
     */
    public function makeQuantity(?float $quantity, int $scale): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function renderQuantity(AuctionLotItem $auctionLot): string
    {
        return (string)$auctionLot->Quantity;
    }
}
