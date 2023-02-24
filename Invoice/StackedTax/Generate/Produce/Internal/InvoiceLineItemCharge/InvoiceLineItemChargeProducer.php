<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Save\DataSaverCreateTrait;

/**
 * Class AdditionalChargeProducer
 * @package Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge
 */
class InvoiceLineItemChargeProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use DataSaverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get and add the additional charges in invoice of "Extra Fee" type, i.e. based on InvoiceLineItem configuration.
     * @param Invoice $invoice
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     */
    public function applyExtraFee(Invoice $invoice, int $editorUserId, bool $isReadOnlyDb = false): void
    {
        $dataProvider = $this->createDataProvider();

        $count = $dataProvider->countInvoiceLineItems($invoice->AccountId, $isReadOnlyDb);
        if ($count === 0) {
            // check if there are invoice line item defined in the system if nothing found no need to process adding invoice line item
            log_trace('No invoice line item defined in the system!');
            return;
        }

        $isFound = $dataProvider->existInvoiceAdditionalByInvoiceId($invoice->Id, $isReadOnlyDb);
        if ($isFound) {
            // to make sure no duplicate adding of invoice line item on invoice generation
            log_trace('Invoice already have invoice line item added' . composeSuffix(['i' => $invoice->Id]));
            return;
        }

        log_trace('Adding "Extra Fee" charges on invoice' . composeSuffix(['i' => $invoice->Id]));
        $addedInvNotPerLot = [];
        $invoiceAuctionRows = $dataProvider->loadInvoiceAuctionRows($invoice->Id, $isReadOnlyDb);
        $invoiceItemRows = $dataProvider->loadInvoiceItemRows($invoice->Id, $isReadOnlyDb);
        foreach ($invoiceItemRows as $invoiceItemRow) {
            $lotItemId = (int)$invoiceItemRow['lot_item_id'];
            $auctionId = (int)$invoiceItemRow['auction_id'];
            $lotName = (string)$invoiceItemRow['lot_name'];
            $hammerPrice = (float)$invoiceItemRow['hammer_price'];
            $lotNo = $invoiceItemRow['lot_no'] ?: 'N/A';
            $auctionType = $invoiceAuctionRows[$auctionId]['auction_type'] ?? '';
            $lotCategories = $dataProvider->loadLotItemCategories($lotItemId, $isReadOnlyDb);
            $chargeInfo = [];
            foreach ($lotCategories as $lotCategory) {
                $lotCategoryId = $lotCategory->LotCategoryId;
                $categoryWithAncestorIds = $dataProvider->loadCategoryWithAncestorIds($lotCategoryId, $isReadOnlyDb);
                $invoiceLineItemRows = $dataProvider->loadInvoiceLineItemRows($invoice->AccountId, $auctionType, $categoryWithAncestorIds, $isReadOnlyDb);
                // invoice line item per category
                foreach ($invoiceLineItemRows as $invoiceLineItemRow) {
                    $label = $invoiceLineItemRow['label'];
                    $isPerLot = (bool)$invoiceLineItemRow['per_lot'];
                    $amount = (float)$invoiceLineItemRow['amount'];
                    $isPercentage = (bool)$invoiceLineItemRow['percentage'];
                    $isLeuOfTax = (bool)$invoiceLineItemRow['leu_of_tax'];

                    if ($label) {
                        $amount = $dataProvider->calcAdditionalCharge(
                            $invoice,
                            $isPerLot,
                            $isLeuOfTax,
                            $isPercentage,
                            $amount,
                            $hammerPrice
                        );
                        if (
                            !$isPerLot
                            && in_array($label, $addedInvNotPerLot, true)
                        ) {
                            $amount = 0.;
                        }
                    }

                    if (!$isPerLot) {
                        $addedInvNotPerLot[] = $label;
                    }

                    if (!isset($chargeInfo[$lotCategoryId])) {
                        $chargeInfo[$lotCategoryId] = [$label => $amount];
                    } elseif (!isset($chargeInfo[$lotCategoryId][$label])) {
                        $chargeInfo[$lotCategoryId][$label] = $amount;
                    } else {
                        $chargeInfo[$lotCategoryId][$label] += $amount;
                    }
                }
            }

            // invoice line item that has category all
            $invLineItemRows = $dataProvider->loadInvoiceLineItemRowsForAllCategories($invoice->AccountId, $auctionType, $isReadOnlyDb);

            foreach ($invLineItemRows as $row) {
                $label = $row['label'];
                $isPerLot = (bool)$row['per_lot'];
                $amount = (float)$row['amount'];
                $isPercentage = (bool)$row['percentage'];
                $isLeuOfTax = (bool)$row['leu_of_tax'];

                if ($label) {
                    $amount = $dataProvider->calcAdditionalCharge(
                        $invoice,
                        $isPerLot,
                        $isLeuOfTax,
                        $isPercentage,
                        $amount,
                        $hammerPrice
                    );
                    if (
                        !$isPerLot
                        && in_array($label, $addedInvNotPerLot, true)
                    ) {
                        $amount = 0.;
                    }
                }

                if (!$isPerLot) {
                    $addedInvNotPerLot[] = $label;
                }

                if (count($chargeInfo) > 0) {
                    foreach ($chargeInfo as $lotCategoryId => $chargeAmount) {
                        if (!isset($chargeAmount[$label])) {
                            $chargeInfo[$lotCategoryId][$label] = $amount;
                        } else {
                            $chargeInfo[$lotCategoryId][$label] += $amount;
                        }
                    }
                } else {
                    $chargeInfo['ALL'] = [$label => $amount];
                }
            }

            $this->addCharges(
                $invoice,
                $editorUserId,
                $chargeInfo,
                $lotNo,
                $lotName
            );
        }
    }

    /**
     * Add the additional charges in invoice that has highest amount
     * @param Invoice $invoice
     * @param int $editorUserId
     * @param array $chargeInfo Array
     * @param string $lotNo
     * @param string $lotName
     * @param bool $isReadOnlyDb
     */
    protected function addCharges(
        Invoice $invoice,
        int $editorUserId,
        array $chargeInfo,
        string $lotNo = '',
        string $lotName = '',
        bool $isReadOnlyDb = false
    ): void {
        if (count($chargeInfo) === 0) {
            // no invoice line item return;
            return;
        }

        $dataProvider = $this->createDataProvider();
        $dataSaver = $this->createDataSaver();
        $accountId = $invoice->AccountId;
        $invoiceId = $invoice->Id;

        $categoryIdWithHighest = null;
        $catWithHighest = 0.;
        foreach ($chargeInfo as $categoryId => $chargeAmount) {
            $sumCharge = array_sum($chargeAmount);
            if (Floating::gt($sumCharge, $catWithHighest)) {
                $catWithHighest = $sumCharge;
                $categoryIdWithHighest = $categoryId;
            }
        }

        if (array_key_exists($categoryIdWithHighest, $chargeInfo)) {
            $chargesAmounts = $chargeInfo[$categoryIdWithHighest];
        } else {
            $chargesAmounts = [];
        }

        foreach ($chargesAmounts as $label => $amount) {
            if (Floating::lteq($amount, 0.)) {
                continue;
            }

            $isFound = $dataProvider->existInvoiceAdditionalByInvoiceIdAndName($invoiceId, $label, $isReadOnlyDb);
            if ($isFound) {
                log_debug(
                    'Update extra charge'
                    . composeSuffix(['i' => $invoiceId, 'amount' => $amount, 'label' => $label])
                );
                $invoiceAdditionals = $dataProvider->loadInvoiceAdditionalByInvoiceIdAndName($invoiceId, $label, $isReadOnlyDb);
                foreach ($invoiceAdditionals as $invoiceAdditional) {
                    $invoiceAdditional->Amount += $amount;
                    $dataSaver->saveInvoiceAdditional($invoiceAdditional, $editorUserId);
                }
            } else {
                $invoiceLineItem = $dataProvider->loadInvoiceLineItemByLabelAndAccountId($label, $accountId, $isReadOnlyDb);
                $breakDown = $invoiceLineItem->BreakDown ?? '';
                if ($breakDown === Constants\Invoice::BD_ITEM_BY_ITEM) {
                    $label .= ' (Lot ' . $lotNo . ' - ' . $lotName . ')';
                } else {
                    log_debug(
                        'Adding extra charge'
                        . composeSuffix(['i' => $invoiceId, 'amount' => $amount, 'label' => $label])
                    );
                }
                $dataSaver->addInvoiceAdditionalExtraFee($invoiceId, $label, $amount, $editorUserId);
            }
        }
    }
}
