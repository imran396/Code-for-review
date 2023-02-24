<?php
/**
 * SAM-10138: Add the ability to check lists to export to CSV
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 29, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Settlement\CheckList;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepository;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepositoryCreateTrait;
use Sam\View\Admin\Form\SettlementCheckListForm\SettlementCheckListConstants;

/**
 * Class DataLoader
 * @package Sam\Report\Settlement\CheckList
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use SettlementCheckReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $settlementCheckIds
     * @param string $sortColumnIndex
     * @param bool $isAscending
     * @return array
     */
    public function load(
        array $settlementCheckIds,
        string $sortColumnIndex,
        bool $isAscending
    ): array {
        $select = [
            'sch.*',
            'pmnt.amount AS payment_amount',
            's.settlement_no',
        ];

        $repo = $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb(true)
            ->filterId($settlementCheckIds)
            ->joinPayment()
            ->joinSettlement()
            ->select($select);

        $repo = $this->applyOrdering($repo, $sortColumnIndex, $isAscending);

        return $repo->loadRows();
    }

    /**
     * @param SettlementCheckReadRepository $repository
     * @param string $sortColumn
     * @param bool $isAscending
     * @return SettlementCheckReadRepository
     */
    protected function applyOrdering(
        SettlementCheckReadRepository $repository,
        string $sortColumn,
        bool $isAscending
    ): SettlementCheckReadRepository {
        switch ($sortColumn) {
            case SettlementCheckListConstants::ORD_SETTLEMENT_NO:
                $repository->joinSettlementOrderBySettlementNo($isAscending);
                break;
            case SettlementCheckListConstants::ORD_CHECK_NO:
                $repository->orderByCheckNo($isAscending);
                break;
            case SettlementCheckListConstants::ORD_PAYEE:
                $repository->orderByPayee($isAscending);
                break;
            case SettlementCheckListConstants::ORD_AMOUNT:
                $repository->orderByAmount($isAscending);
                break;
            case SettlementCheckListConstants::ORD_AMOUNT_SPELLING:
                $repository->orderByAmountSpelling($isAscending);
                break;
            case SettlementCheckListConstants::ORD_MEMO:
                $repository->orderByMemo($isAscending);
                break;
            case SettlementCheckListConstants::ORD_NOTE:
                $repository->orderByNote($isAscending);
                break;
            case SettlementCheckListConstants::ORD_ADDRESS:
                $repository->orderByAddress($isAscending);
                break;
            case SettlementCheckListConstants::ORD_STATUS:
                $repository->orderByStatus($isAscending);
                break;
            case SettlementCheckListConstants::ORD_APPLIED_PAYMENT:
                $repository->joinPaymentOrderByAmount($isAscending);
                break;
            case SettlementCheckListConstants::ORD_CREATED_ON:
                $repository->orderByCreatedOn($isAscending);
                break;
            case SettlementCheckListConstants::ORD_PRINTED_ON:
                $repository->orderByPrintedOn($isAscending);
                break;
            case SettlementCheckListConstants::ORD_POSTED_ON:
                $repository->orderByPostedOn($isAscending);
                break;
            case SettlementCheckListConstants::ORD_CLEARED_ON:
                $repository->orderByClearedOn($isAscending);
                break;
            case SettlementCheckListConstants::ORD_VOIDED_ON:
                $repository->orderByVoidedOn($isAscending);
                break;
            default:
                $repository->orderById($isAscending);
                break;
        }

        return $repository;
    }
}
