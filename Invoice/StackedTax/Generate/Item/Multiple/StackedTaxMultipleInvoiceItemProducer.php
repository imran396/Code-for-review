<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           04.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Multiple;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProducerAwareTrait;
use Sam\Invoice\StackedTax\Generate\Item\Single\StackedTaxSingleInvoiceItemProductionInput;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Invoice\StackedTax\Generate\Item\Multiple\StackedTaxMultipleInvoiceItemProductionResult as Result;

/**
 * Class MultipleInvoiceItemProducer
 * @package Sam\Invoice\StackedTax\Generate\Item
 */
class StackedTaxMultipleInvoiceItemProducer extends CustomizableClass
{
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use StackedTaxSingleInvoiceItemProducerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param StackedTaxSingleInvoiceItemProductionInput[] $inputs
     * @return Result
     */
    public function produce(array $inputs): Result
    {
        $multipleInvoiceItemProductionResult = Result::new()->construct();
        $singleInvoiceProducer = $this->getStackedTaxSingleInvoiceItemProducer();
        foreach ($inputs as $input) {
            $isFound = $this->createInvoiceItemReadRepository()
                ->filterActive(true)
                ->filterAuctionId($input->auctionId)
                ->filterLotItemId($input->lotItem->Id)
                ->filterRelease([false, null])
                ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses) // JIC, because ii.active = false must be in deleted invoice
                ->exist();
            if ($isFound) {
                log_debug('Already in invoice lot item with auction' . composeSuffix($input->logData()));
                return $multipleInvoiceItemProductionResult->addError(Result::ERR_LOT_ITEM_ALREADY_IN_INVOICE);
            }

            $singleInvoiceItemProductionResult = $singleInvoiceProducer->produce($input);
            if ($singleInvoiceItemProductionResult->hasError()) {
                log_debug(
                    'Failed to add in lot item with auction - ' . $singleInvoiceItemProductionResult->errorMessage()
                    . ', Input is: ' . composeLogData($input->logData())
                );
                return $multipleInvoiceItemProductionResult->addSingleInvoiceItemProductionFailedError($singleInvoiceItemProductionResult);
            }
//            if ($singleInvoiceItemProductionResult->hasInfo()) {
//                log_debug(
//                    'Skipped adding lot item to invoice, continue to next - ' . $singleInvoiceItemProductionResult->infoMessage()
//                    . ', Input is: ' . composeLogData($input->logData())
//                );
//                $multipleInvoiceItemProductionResult->addSingleInvoiceItemProductionSkippedInfo($singleInvoiceItemProductionResult);
//            }
        }
        return $multipleInvoiceItemProductionResult;
    }
}
