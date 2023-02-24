<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Save;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\LineItem\Edit\Common\InvoiceLineItemInput as Input;
use Sam\Invoice\Common\LineItem\Edit\Save\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\LineItem\Edit\Save\InvoiceLineItemProductionResult as Result;
use Sam\Invoice\Common\LineItem\Edit\Validate\InvoiceLineItemValidationResult;
use Sam\Storage\WriteRepository\Entity\InvoiceLineItem\InvoiceLineItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceLineItemLotCat\InvoiceLineItemLotCatWriteRepositoryAwareTrait;

/**
 * Class InvoiceLineItemProducer
 * @package Sam\Invoice\Common\LineItem\Edit\Validate
 */
class InvoiceLineItemProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use EntityFactoryCreateTrait;
    use InvoiceLineItemWriteRepositoryAwareTrait;
    use InvoiceLineItemLotCatWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update modified InvoiceLineItem or create new one
     * @param Input $input
     * @return Result
     */
    public function produce(Input $input): Result
    {
        $dataProvider = $this->createDataProvider();
        $invoiceLineItem = $dataProvider->loadInvoiceLineItem($input->invoiceLineItemId);
        if (!$invoiceLineItem) {
            $invoiceLineItem = $this->createEntityFactory()->invoiceLineItem();
            $invoiceLineItem->AccountId = $input->accountId;
        }
        $invoiceLineItem->Active = true;
        $invoiceLineItem->Amount = Cast::toFloat($input->amount);
        $invoiceLineItem->AuctionType = $input->auctionType;
        $invoiceLineItem->BreakDown = $input->breakDown;
        $invoiceLineItem->Label = $input->label;
        $invoiceLineItem->LeuOfTax = $input->isLeuOfTax;
        $invoiceLineItem->PerLot = $input->isPerLot;
        $invoiceLineItem->Percentage = $input->isPercentage;
        $this->getInvoiceLineItemWriteRepository()->saveWithModifier($invoiceLineItem, $input->editorUserId);

        $invoiceLineItemsLotCatsResult = [];
        if ($input->isPerLot) {
            $invoiceLineItemsLotCats = $dataProvider->loadInvoiceLineItemLotCategoryByInvoiceLineId($invoiceLineItem->Id);
            foreach ($invoiceLineItemsLotCats as $invoiceLineItemLotCat) {
                $invoiceLineItemLotCat->Active = false;
                $this->getInvoiceLineItemLotCatWriteRepository()->saveWithModifier($invoiceLineItemLotCat, $input->editorUserId);
            }

            foreach ($input->lotCategoryIds as $key => $lotCatId) {
                if (
                    $lotCatId === null               // don't save "All" category,
                    && count($input->lotCategoryIds) > 1    // when other categories are selected (SAM-2616)
                ) {
                    continue;
                }
                $input->lotCategoryIds[$key] = $lotCatId;

                $invoiceCat = $dataProvider->loadInvoiceLineItemLotCategoryByInvoiceLineIdAndLotCatId($invoiceLineItem->Id, [$lotCatId]);

                if (!$invoiceCat) {
                    $invoiceCat = $this->createEntityFactory()->invoiceLineItemLotCat();
                    $invoiceCat->InvoiceLineId = $invoiceLineItem->Id;
                    $invoiceCat->LotCatId = $lotCatId;
                }
                $invoiceCat->Active = true;
                $this->getInvoiceLineItemLotCatWriteRepository()->saveWithModifier($invoiceCat, $input->editorUserId);
                $invoiceLineItemsLotCatsResult[] = $invoiceCat;
            }
        } else {
            $invoiceCat = $dataProvider->loadInvoiceLineItemLotCategoryByInvoiceLineIdAndLotCatId($invoiceLineItem->Id, [null], true);

            if (!$invoiceCat) {
                $invoiceCat = $this->createEntityFactory()->invoiceLineItemLotCat();
                $invoiceCat->InvoiceLineId = $invoiceLineItem->Id;
                $invoiceCat->LotCatId = null;
                $invoiceCat->Active = true;
                $this->getInvoiceLineItemLotCatWriteRepository()->saveWithModifier($invoiceCat, $input->editorUserId);
            }
            $invoiceLineItemsLotCatsResult[] = $invoiceCat;
        }
        return Result::new()->construct($invoiceLineItem, $invoiceLineItemsLotCatsResult);
    }


    /**
     * @param Input $input
     * @param InvoiceLineItemValidationResult $result
     * @return array
     */
    public function logData(Input $input, InvoiceLineItemValidationResult $result): array
    {
        $logData = [
            'amount' => $input->amount,
            'auction type' => $input->auctionType,
            'break down' => $input->breakDown,
            'invoice line id' => $input->invoiceLineItemId,
            'label' => $input->label,
            'leu of tax' => $input->isLeuOfTax,
            'is percentage' => $input->isPercentage,
            'is per lot' => $input->isPerLot,
            'lot category ids' => $input->lotCategoryIds,
        ];

        if ($result->hasError()) {
            $logData += [
                'error codes' => $result->getErrorCodes(),
                'error messages' => $result->getErrorMessages(),
            ];
        }
        return $logData;
    }
}
