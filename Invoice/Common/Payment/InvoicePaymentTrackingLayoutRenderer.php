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

namespace Sam\Invoice\Common\Payment;

use Invoice;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use QMySqli5DatabaseResult;
use QMySqliDatabaseException;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use RuntimeException;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoicePaymentTrackingLayoutRenderer
 * @package Sam\Invoice\Common\Payment
 */
class InvoicePaymentTrackingLayoutRenderer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use DbConnectionTrait;
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use InvoiceItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Renders payment tracking data as html
     *
     * @param Invoice $invoice
     * @param float $amount
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function render(Invoice $invoice, float $amount, bool $isReadOnlyDb = false): string
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $output = '';
        $winningUser = $this->getUserLoader()->load($invoice->BidderId, $isReadOnlyDb);
        if (!$winningUser) {
            log_error(
                "Available invoice winning user not found for invoice payment tracking layout rendering"
                . composeSuffix(['u' => $invoice->BidderId])
            );
            return $output;
        }

        // add payment tracking layout from auction info to be output in payment confirmation screen
        $processedAuctionIds = [];
        $invoiceItemRows = $this->getInvoiceItemLoader()->loadSelectedByInvoiceId(['ii.auction_id'], $invoice->Id, $isReadOnlyDb);
        foreach ($invoiceItemRows as $invoiceItemRow) {
            $auctionId = (int)$invoiceItemRow['auction_id'];
            if (
                $auctionId
                && !in_array($auctionId, $processedAuctionIds, true)
            ) {
                $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
                if (!$auction) {
                    log_error(
                        "Available auction not found for invoice payment tracking layout rendering"
                        . composeSuffix(['a' => $auctionId, 'i' => $invoice->Id])
                    );
                    continue;
                }

                // variable auction_total computation
                $hpBpWithTax = 0.;
                if ($invoice->isLegacyTaxDesignation()) {
                    $query =
                        "SELECT SUM(hammer_price + buyers_premium) AS total FROM invoice_item AS ii " .
                        "LEFT JOIN auction_lot_item AS ali ON ali.lot_item_id = ii.lot_item_id " .
                        "LEFT JOIN auction AS a ON a.id = ali.auction_id " .
                        "WHERE ii.invoice_id = " . $this->escape($invoice->Id) . " " .
                        "AND ali.auction_id = " . $this->escape($auctionId) . " " .
                        "AND ii.active = TRUE " .
                        "AND ii.hammer_price IS NOT NULL;";
                    try {
                        $dbResult = $this->query($query);
                    } catch (QMySqliDatabaseException $e) {
                        log_error($e->getCode() . ' - ' . $e->getMessage());
                        throw new RuntimeException($e->getMessage(), $e->getCode());
                    }
                    $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
                    $hpBp = $row['total']; //hp + bp
                    $salesTax = $this->createLegacyInvoiceTaxCalculator()->calcTotalSalesTaxApplied($invoice->Id, $isReadOnlyDb);
                    // Summation of all hp + bp + salestax of all lots per auction on the invoice
                    $hpBpWithTax = $hpBp + $salesTax;
                } elseif ($invoice->isStackedTaxDesignation()) {
                    $hpBpWithTax = $invoice->calcHpBpWithTax();
                }

                $vars = [
                    'invoice_id' => $invoice->Id,
                    'user_id' => $winningUser->Id,
                    'username' => $winningUser->Username,
                    'invoice_total' => $amount,
                    'auction_id' => $auction->Id,
                    'auction_name' => $this->getAuctionRenderer()->renderName($auction),
                    'auction_total' => Floating::roundOutput($hpBpWithTax),
                ];

                $trackingCode = $auction->PaymentTrackingCode;
                foreach ($vars as $name => $value) {
                    $trackingCode = str_replace('{' . $name . '}', $value, $trackingCode);
                }

                $output .= $trackingCode . ' <br /><br /> ';
                $processedAuctionIds[] = $auctionId;
            }
        }
        return $output;
    }
}
