<?php
/**
 * This service detect the next step of invoice generation process.
 * System decides, if it wants to assign lot item to the currently processing invoice,
 * or invoice should be closed with already assigned items, because this lot item does not meet expected conditions.
 * Decision is based on the next properties:
 *
 * - System parameter: MULTIPLE_SALE_INVOICE.
 * It can be changed via CLI tool:
 * $ php bin/install/setting.php set -k MULTIPLE_SALE_INVOICE --value 0 --account all
 * $ php bin/install/setting.php set -k MULTIPLE_SALE_INVOICE --value 1 --account all
 *
 * - System parameter: ONE_SALE_GROUPED_INVOICE.
 * It can be changed via CLI tool:
 * $ php bin/install/setting.php set -k ONE_SALE_GROUPED_INVOICE --value 0 --account all
 * $ php bin/install/setting.php set -k ONE_SALE_GROUPED_INVOICE --value 1 --account all
 *
 * - "Sale Sold" auction (li.auction_id)
 * - "Sale Group" of "Sale Sold" auction (a.sale_group)
 * - Currency of "Sale Sold" auction (a.currency)
 *
 * SAM-5656: One invoice for grouped sales creating multiple invoice for one user
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk\Internal\DetectClosing;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Chunk\Internal\DetectClosing\Internal\Load\DataProviderCreateTrait;

/**
 * Class InvoiceClosingDetector
 * @package Sam\Invoice\StackedTax\Generate\Chunk\Internal\DetectClosing
 */
class InvoiceClosingDetector extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Currency Id of previously invoiced lot item
     * @var int|null
     */
    protected ?int $previousCurrencyId = null;
    /**
     * Auction id of previously invoiced lot item
     * @var int|null
     */
    private ?int $previousAuctionId = null;
    /**
     * Sale Group of previously invoiced lot item
     * @var string|null
     */
    private ?string $previousSaleGroup = null;
    /**
     * Constants\Setting::MULTIPLE_SALE_INVOICE
     * @var bool
     */
    private bool $isMultipleSale = false;
    /**
     * Constants\Setting::ONE_SALE_GROUPED_INVOICE
     * @var bool
     */
    private bool $isOneGroupedSale = false;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(bool $isMultipleSale, bool $isOneGroupedSale): static
    {
        $this->isMultipleSale = $isMultipleSale;
        $this->isOneGroupedSale = $isOneGroupedSale;
        return $this;
    }

    public function shouldClose(?int $currentAuctionId): bool
    {
        [$currentCurrencyId, $currentSaleGroup] = $this->createDataProvider()->loadAuctionData($currentAuctionId);

        $shouldClose = $this->shouldCloseInvoice(
            $currentCurrencyId,
            $currentAuctionId,
            $currentSaleGroup
        );

        $this->previousCurrencyId = $currentCurrencyId;
        $this->previousAuctionId = $currentAuctionId;
        $this->previousSaleGroup = $currentSaleGroup;

        return $shouldClose;
    }

    /**
     * Check, if the currently processing invoice should be closed with already added items and without currently checking lot item.
     * Result with
     *  false: unite lots in the same invoice,
     *   true: split lots to separate invoices.
     * @param int|null $currentCurrencyId
     * @param int|null $currentAuctionId
     * @param string $currentSaleGroup
     * @return bool
     */
    protected function shouldCloseInvoice(
        ?int $currentCurrencyId,
        ?int $currentAuctionId,
        string $currentSaleGroup
    ): bool {
        // check that "sale sold" auction not the same as for previous lot
        $isAuctionDifferent = $this->previousAuctionId !== null
            && $currentAuctionId !== $this->previousAuctionId;

        // check that currency not the same as for previous lot
        $isCurrencyDifferent = $this->previousCurrencyId !== null
            && $currentCurrencyId !== $this->previousCurrencyId;

        // check that sale group is not the same as for previous lot
        $isSaleGroupDifferent = $this->previousSaleGroup !== null
            && (
                $currentSaleGroup === ''
                || $currentSaleGroup !== $this->previousSaleGroup
            );

        $logData = [
            'a' => $currentAuctionId,
            'sg' => $currentSaleGroup,
            'currency' => $currentCurrencyId,
            'prev a' => $this->previousAuctionId,
            'prev sg' => $this->previousSaleGroup,
            'prev currency' => $this->previousCurrencyId,
            'MULTIPLE_SALE_INVOICE' => $this->isMultipleSale,
            'ONE_SALE_GROUPED_INVOICE' => $this->isOneGroupedSale,
        ];

        if (!$isAuctionDifferent) {
            log_trace("Unite in invoice items from the same auction" . composeSuffix($logData));
            return false;
        }

        if ($isCurrencyDifferent) {
            log_trace("Split items in invoices because of different currencies" . composeSuffix($logData));
            return true;
        }

        if ($this->isMultipleSale) {
            log_trace("Unite items in invoice for enabled MULTIPLE_SALE_INVOICE (and currency is the same)" . composeSuffix($logData));
            return false;
        }

        if (
            $this->isOneGroupedSale
            && !$isSaleGroupDifferent
        ) {
            log_trace("Unite items in invoice, because sale groups are the same for enabled ONE_SALE_GROUPED_INVOICE" . composeSuffix($logData));
            return false;
        }

        log_trace("Split items in invoices" . composeSuffix($logData));
        return true;
    }

}
