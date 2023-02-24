<?php
/**
 * SAM-10902: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the "Add New Sold Lots" button action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\AddLot;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\PreInvoicingDataLoaderCreateTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProducerAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProductionInput;
use Sam\Settings\SettingsManagerAwareTrait;

class LegacyInvoiceSoldLotAdder extends CustomizableClass
{
    use InvoiceItemLoaderAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;
    use PreInvoicingDataLoaderCreateTrait;
    use SettingsManagerAwareTrait;
    use LegacySingleInvoiceItemProducerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Invoice $invoice
     * @param int $editorUserId
     * @return int add lots count // TODO: think about result-object with more details
     */
    public function add(Invoice $invoice, int $editorUserId): int
    {
        $invoicedAuctionDtos = $this->getInvoiceItemLoader()->loadInvoicedAuctionDtos($invoice->Id);
        $invoicedAuctionIds = ArrayCast::arrayColumnInt($invoicedAuctionDtos, 'auctionId');
        $wonLotItems = $this->createPreInvoicingDataLoader()->loadLotItems(
            $invoice->AccountId,
            $invoice->BidderId,
            null,
            null,
            $invoicedAuctionIds
        );

        $isMultipleSaleInvoice = (bool)$this->getSettingsManager()->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $invoice->AccountId);

        if (!$isMultipleSaleInvoice) {
            foreach ($wonLotItems as $key => $wonLotItem) {
                if (!$wonLotItem->isSaleSoldAuctionAmong($invoicedAuctionIds)) {
                    unset($wonLotItems[$key]);
                }
            }
            array_multisort($wonLotItems);
        }

        array_multisort($wonLotItems);

        $singleInvoiceProducer = $this->getLegacySingleInvoiceItemProducer();
        foreach ($wonLotItems as $wonLotItem) {
            $invoiceItemProductionInput = LegacySingleInvoiceItemProductionInput::new()->construct(
                $invoice->Id,
                $wonLotItem,
                $wonLotItem->AuctionId,
                $wonLotItem->WinningBidderId,
                $editorUserId
            );
            $singleInvoiceProducer->produce($invoiceItemProductionInput);
        }

        $count = count($wonLotItems);
        if ($count) {
            $this->getLegacyInvoiceSummaryCalculator()->recalculate($invoice->Id, $editorUserId);
        }

        return $count;
    }
}
