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

namespace Sam\Invoice\Legacy\Generate\Produce\Internal\InvoiceLineItemCharge;

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Legacy\Calculate\AdditionalCharge\LegacyInvoiceAdditionalChargeCalculatorCreateTrait;
use Sam\Invoice\Common\LineItem\Load\InvoiceLineItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCategory\LotItemCategoryReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;

/**
 * Class AdditionalChargeProducer
 * @package Sam\Invoice\Legacy\Generate\Produce\Internal\InvoiceLineItemCharge
 */
class InvoiceLineItemChargeProducer extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use LegacyInvoiceAdditionalChargeCalculatorCreateTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAdditionalReadRepositoryCreateTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceLineItemLoaderAwareTrait;
    use InvoiceLineItemReadRepositoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotItemCategoryReadRepositoryCreateTrait;
    use LotRendererAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get and add the additional charges in invoice
     * @param int $invoiceId (int) invoice.id
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     */
    public function applyCharges(int $invoiceId, int $editorUserId, bool $isReadOnlyDb = false): void
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Available invoice not found, when applying charges" . composeSuffix(['i' => $invoiceId]));
            return;
        }

        $count = $this->createInvoiceLineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($invoice->AccountId)
            ->filterActive(true)
            ->count();

        if ($count === 0) {
            // check if there are invoice line item defined in the system if nothing found no need to process adding invoice line item
            log_debug('No invoice line item defined in the system!');
            return;
        }

        $isFound = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->exist();

        if ($isFound) {
            // to make sure no duplicate adding of invoice line item on invoice generation
            log_debug('Invoice already have invoice line item added' . composeSuffix(['i' => $invoiceId]));
            return;
        }
        log_debug('Adding charges on invoice' . composeSuffix(['i' => $invoiceId]));

        $invoiceItemRepository = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterInvoiceId($invoiceId)
            // ->joinAccountFilterActive(true)
            // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            // ->joinLotItemFilterActive(true)
            // ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            // ->joinUserWinningBidderFilterUserStatusId(Constants\User::US_ACTIVE)
            ->orderByLotItemId()
            ->select(
                [
                    'ii.lot_item_id',
                    'ii.auction_id',
                    'ii.hammer_price',
                    'ii.lot_name',
                ]
            );

        $invoiceItemRows = $invoiceItemRepository->loadRows();
        $invoiceLineItemRepository = $this->createInvoiceLineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($invoice->AccountId)
            ->filterActive(true)
            ->joinInvoiceLineItemLotCatFilterActive(true)
            ->select(
                [
                    'ili.id',
                    'ili.label',
                    'ili.amount',
                    'ili.auction_type',
                    'IF(ili.per_lot, 1,0) AS per_lot',
                    'ili.active',
                    'ililc.lot_cat_id',
                    'ili.break_down',
                    'if (ili.percentage, TRUE, FALSE) as percentage',
                    'if (ili.leu_of_tax, TRUE, FALSE) as leu_of_tax',
                ]
            );

        $addedInvNotPerLot = [];

        foreach ($invoiceItemRows as $invoiceItemRow) {
            $lotItemId = (int)$invoiceItemRow['lot_item_id'];
            $auctionId = (int)$invoiceItemRow['auction_id'];
            $itemName = (string)$invoiceItemRow['lot_name'];
            $hammerPrice = (float)$invoiceItemRow['hammer_price'];
            $lotNo = '';
            $auctionType = '';

            //get auction type
            if ($auctionId > 0) {
                $auctionRepository = $this->createAuctionReadRepository()
                    ->enableReadOnlyDb($isReadOnlyDb)
                    ->select(['auction_type'])
                    ->filterId($auctionId);
                $auctionTypeRow = $auctionRepository->loadRow();
                if ($auctionTypeRow) {
                    $auctionType = $auctionTypeRow['auction_type'];
                }
            }

            $aliRow = $this->createAuctionLotItemReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemId)
                ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->select(
                    [
                        'ali.lot_item_id',
                        'ali.lot_num_prefix',
                        'ali.lot_num',
                        'ali.lot_num',
                        'ali.lot_num_ext',
                    ]
                )
                ->loadRow();

            if ($aliRow) {
                $lotNo = $aliRow['lot_num']
                    ? $this->getLotRenderer()->makeLotNo($aliRow['lot_num'], $aliRow['lot_num_ext'], $aliRow['lot_num_prefix'])
                    : 'N/A';
            }

            $lotCategories = $this->createLotItemCategoryReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterLotItemId($lotItemId)
                ->loadEntities();

            $chargeInfo = [];

            foreach ($lotCategories as $lotCategory) {
                $lotCategoryId = $lotCategory->LotCategoryId;
                $categoryWithAncestorIds = $this->getLotCategoryLoader()
                    ->loadCategoryWithAncestorIds($lotCategoryId, $isReadOnlyDb);
                $invoiceLineItemRows = $invoiceLineItemRepository
                    ->filterAuctionType(['A', $auctionType])
                    ->innerJoinInvoiceLineItemLotCatFilterLotCatId($categoryWithAncestorIds)
                    ->loadRows();
                // invoice line item per category
                foreach ($invoiceLineItemRows as $invoiceLineItemRow) {
                    $label = $invoiceLineItemRow['label'];
                    $isPerLot = (bool)$invoiceLineItemRow['per_lot'];
                    $amount = (float)$invoiceLineItemRow['amount'];
                    $isPercentage = (bool)$invoiceLineItemRow['percentage'];
                    $isLeuOfTax = (bool)$invoiceLineItemRow['leu_of_tax'];

                    if ($label) {
                        $amount = $this->createInvoiceAdditionalChargeCalculator()->calcAdditionalCharge(
                            $invoiceId,
                            $isPerLot,
                            $isLeuOfTax,
                            $isPercentage,
                            $amount,
                            $hammerPrice,
                            $isReadOnlyDb
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
            $invLineItemRows = $invoiceLineItemRepository
                ->filterAuctionType(['A', $auctionType])
                ->innerJoinInvoiceLineItemLotCatFilterLotCatId(null)
                ->orderById()
                ->groupById()
                ->loadRows();

            foreach ($invLineItemRows as $row) {
                $label = $row['label'];
                $isPerLot = (bool)$row['per_lot'];
                $amount = (float)$row['amount'];
                $isPercentage = (bool)$row['percentage'];
                $isLeuOfTax = (bool)$row['leu_of_tax'];

                if ($label) {
                    $amount = $this->createInvoiceAdditionalChargeCalculator()->calcAdditionalCharge(
                        $invoiceId,
                        $isPerLot,
                        $isLeuOfTax,
                        $isPercentage,
                        $amount,
                        $hammerPrice,
                        $isReadOnlyDb
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
                $invoiceId,
                $editorUserId,
                $chargeInfo,
                $lotNo,
                $itemName
            );
            unset($chargeInfo);
        }
    }

    /**
     * Add the additional charges in invoice that has highest amount
     * @param int $invoiceId (int) invoice.id
     * @param int $editorUserId
     * @param array $chargeInfo Array
     * @param string $lotNo
     * @param string $itemName
     * @param bool $isReadOnlyDb
     */
    protected function addCharges(
        int $invoiceId,
        int $editorUserId,
        array $chargeInfo,
        string $lotNo = '',
        string $itemName = '',
        bool $isReadOnlyDb = false
    ): void {
        if (count($chargeInfo) === 0) {
            // no invoice line item return;
            return;
        }

        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error("Available invoice not found, when adding charges" . composeSuffix(['i' => $invoiceId]));
            return;
        }
        $accountId = $invoice->AccountId;

        $categoryIdWithHighest = null;
        $catWithHighest = 0;
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
            $isFound = $this->createInvoiceAdditionalReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterInvoiceId($invoiceId)
                ->filterName($label)
                ->exist();

            $invoiceLineItem = $this->getInvoiceLineItemLoader()
                ->loadByLabelAndAccount($label, $accountId, $isReadOnlyDb);
            $breakDown = $invoiceLineItem->BreakDown ?? '';

            if (Floating::gt($amount, 0)) {
                if ($isFound) {
                    log_debug(
                        'Update extra charge'
                        . composeSuffix(['i' => $invoiceId, 'amount' => $amount, 'label' => $label])
                    );
                    $invoiceAdditionals = $this->createInvoiceAdditionalReadRepository()
                        ->enableReadOnlyDb($isReadOnlyDb)
                        ->filterInvoiceId($invoiceId)
                        ->filterName($label)
                        ->loadEntities();
                    foreach ($invoiceAdditionals as $invoiceAdditional) {
                        $invoiceAdditional->Amount += $amount;
                        $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
                    }
                } else {
                    if ($breakDown === Constants\Invoice::BD_ITEM_BY_ITEM) {
                        $label .= ' (Lot ' . $lotNo . ' - ' . $itemName . ')';
                    } else {
                        log_debug(
                            'Adding extra charge'
                            . composeSuffix(['i' => $invoiceId, 'amount' => $amount, 'label' => $label])
                        );
                    }
                    $this->getInvoiceAdditionalChargeManager()->add(
                        Constants\Invoice::IA_EXTRA_FEE,
                        $invoiceId,
                        $label,
                        $amount,
                        $editorUserId
                    );
                }
            }
        }
    }
}
