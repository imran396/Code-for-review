<?php
/**
 * SAM-10339: Fetch US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE from entity accounts
 * SAM-8543: Dummy classes for service stubbing in unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render\Amount;

use AuctionLotItem;
use LotItem;

interface LotAmountRendererInterface
{
    // --- Lot Estimates ---

    /**
     * Construct lot estimated prices based on input arguments.
     * @param float|null $lowEstimate
     * @param float|null $highEstimate
     * @param string $currencySign
     * @param bool $isShowLowEst
     * @param bool $isShowHighEst
     * @return string
     */
    public function makeEstimates(
        ?float $lowEstimate,
        ?float $highEstimate,
        string $currencySign,
        bool $isShowLowEst,
        bool $isShowHighEst
    ): string;

    /**
     * Render lot estimated prices based on input entity and settings.
     * @param LotItem $lotItem
     * @param string $currencySign
     * @param array $optionals
     * @return string
     */
    public function renderEstimates(
        LotItem $lotItem,
        string $currencySign,
        array $optionals = []
    ): string;

    // --- Quantity ---

    /**
     * Construct formatted quantity based on input arguments.
     * @param float|null $quantity
     * @param int $scale
     * @return string
     */
    public function makeQuantity(?float $quantity, int $scale): string;

    /**
     * Render formatted quantity based on input AuctionLotItem entity
     * @param AuctionLotItem $auctionLot
     * @return string
     */
    public function renderQuantity(AuctionLotItem $auctionLot): string;
}
