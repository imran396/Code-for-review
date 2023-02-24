<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\ArtistResaleRight\ArtistResaleRightChargeSaverCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceAuction\InvoiceAuctionSaverCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem\InvoiceItemSaverCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionResult as Result;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput as Input;

class StackedTaxSingleInvoiceItemProducer extends CustomizableClass
{
    use ArtistResaleRightChargeSaverCreateTrait;
    use InvoiceAuctionSaverCreateTrait;
    use InvoiceItemSaverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Creates invoice item based on PreInvoiceItemDto data
     * @param Input $input
     * @return Result
     */
    public function produce(Input $input): Result
    {
        $singleInvoiceItemProductionResult = Result::new()->construct();

        $invoiceItemSavingResult = $this->createInvoiceItemSaver()->produceInvoiceItem($input);
        $singleInvoiceItemProductionResult->setInvoiceItemSavingResult($invoiceItemSavingResult);
        if ($invoiceItemSavingResult->hasError()) {
            log_debug("Invoice Item production failed - " . $invoiceItemSavingResult->errorMessage());
            return $singleInvoiceItemProductionResult;
        }
        // IK, 2023-01: There is no info case at the moment, logic is kept for the possible future use.
        if ($invoiceItemSavingResult->hasInfo()) {
            log_debug("Invoice Item production skipped - " . $invoiceItemSavingResult->infoMessage());
            return $singleInvoiceItemProductionResult;
        }

        $invoiceAdditional = $this->createInvoiceAdditionalSaver()->produceArtistResaleRightCharge($input);
        if ($invoiceAdditional) {
            $singleInvoiceItemProductionResult->addInvoiceAdditional($invoiceAdditional);
        }

        $invoiceAuction = $this->createInvoiceAuctionSaver()->snapshotAuctionPersisted($input);
        if ($invoiceAuction) {
            $singleInvoiceItemProductionResult->setInvoiceAuction($invoiceAuction);
        }

        return $singleInvoiceItemProductionResult;
    }
}
