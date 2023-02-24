<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Threshold;

use Invoice;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;

/**
 * Class InvoiceThresholdChecker
 * @package Sam\Invoice\Common\Threshold
 */
class InvoiceThresholdChecker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use DbConnectionTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceReadRepositoryCreateTrait;
    use InvoiceUserLoaderAwareTrait;
    use PaymentReadRepositoryCreateTrait;

    public const OP_GRAND_TOTAL = OptionalKeyConstants::KEY_GRAND_TOTAL; //float

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detects, if this invoice is within the CC thresholds, and if the Pay Now button should be shown
     * @param int $invoiceId
     * @param int $userId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @param array $optionals [
     *   self::OP_GRAND_TOTAL => float
     * ]
     * @return bool
     */
    public function inThreshold(int $invoiceId, int $userId, int $editorUserId, bool $isReadOnlyDb = false, array $optionals = []): bool
    {
        $auctionId = $this->getInvoiceItemLoader()->findFirstInvoicedAuctionId($invoiceId, $isReadOnlyDb);
        $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
        if (!$auction) {
            // there is no auction involved, thus allow cc payments
            return true;
        }

        // make sure an auction object is related to this invoice
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Available invoice not found for threshold check" . composeSuffix(['i' => $invoiceId]));
            return false;
        }

        $totalPaidThruCC = $this->calcThresholdPaymentTotalByAuctionAndUser($userId, $auction->Id, $isReadOnlyDb);
        $grandTotal = $optionals[self::OP_GRAND_TOTAL] ?? $this->calcGrandTotal($invoice, $isReadOnlyDb);
        $projectedTotal = $totalPaidThruCC + $grandTotal;

        if (
            $auction->CcThresholdDomestic === null
            && $auction->CcThresholdInternational === null
        ) {
            //there are no thresholds defined so always in threshold
            return true;
        }

        $invoiceBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreatePersisted($invoice->Id, $editorUserId, $isReadOnlyDb);
        if ($invoiceBilling->Country === $auction->AuctionHeldIn) {
            //domestic threshold will be used
            if (Floating::eq($auction->CcThresholdDomestic, 0)) {
                //threshold is set to 0, so no CC pay
                return false;
            }

            if ($auction->CcThresholdDomestic === null) {
                //domestic threshold is not defined
                //so allow cc payments always
                return true;
            }

            if (Floating::gt($projectedTotal, $auction->CcThresholdDomestic)) {
                //total cc payments will exceed the threshold
                return false;
            }

            //total cc payments will NOT exceed the threshold
            return true;
        }

        //international threshold will be used
        if (Floating::eq($auction->CcThresholdInternational, 0)) {
            //threshold is set to 0, so no CC pay
            return false;
        }

        if ($auction->CcThresholdInternational === null) {
            //domestic threshold is not defined
            //so allow cc payments always
            return true;
        }

        if (Floating::gt($projectedTotal, $auction->CcThresholdInternational)) {
            //total cc payments will exceed the threshold
            return false;
        }

        //total cc payments will NOT exceed the threshold
        return true;
    }

    /**
     * @param int $winningUserId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return float
     */
    protected function calcThresholdPaymentTotalByAuctionAndUser(int $winningUserId, int $auctionId, bool $isReadOnlyDb = false): float
    {
        $invoiceIds = $this->loadRelatedInvoiceIdsByAuctionAndUser($winningUserId, $auctionId, $isReadOnlyDb);
        if (!count($invoiceIds)) {
            log_debug(
                "Related invoices not found for threshold payment total calculation"
                . composeSuffix(['u' => $winningUserId, 'a' => $auctionId])
            );
            return 0.;
        }

        $row = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterPaymentMethodId(Constants\Payment::PM_CC)
            ->filterTranType(Constants\Payment::TT_INVOICE)
            ->filterTranId($invoiceIds)
            ->joinInvoiceFilterExcludeInThreshold(false)
            ->select(['SUM(amount) AS total'])
            ->loadRow();
        return (float)($row['total'] ?? 0.);
    }

    /**
     * Get the related invoice ids for user under a certain sale
     * @param int $winningUserId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    protected function loadRelatedInvoiceIdsByAuctionAndUser(int $winningUserId, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createInvoiceReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterBidderId($winningUserId)
            ->joinInvoiceItemFilterActive(true)
            ->joinLotItemFilterAuctionId($auctionId)
            ->groupById()
            ->select(['i.id'])
            ->loadRows();
        $invoiceIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $invoiceIds;
    }

    protected function calcGrandTotal(Invoice $invoice, bool $isReadOnlyDb = false): float
    {
        if ($invoice->isLegacyTaxDesignation()) {
            return $this->getLegacyInvoiceCalculator()->calcGrandTotal($invoice->Id, $isReadOnlyDb);
        }

        if ($invoice->isStackedTaxDesignation()) {
            return $invoice->calcInvoiceTotal(); // TODO: consider cash discount there?
        }

        return 0.;
    }
}
