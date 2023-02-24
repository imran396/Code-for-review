<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckListForm\Load;

use DateTime;
use Exception;
use Sam\Account\Filter\AccountApplicationFilterDetectorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepository;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepositoryCreateTrait;
use Sam\View\Admin\Form\SettlementCheckListForm\SettlementCheckListConstants;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\SettlementCheckListForm\Load
 */
class DataProvider extends CustomizableClass
{
    use AccountApplicationFilterDetectorAwareTrait;
    use DateHelperAwareTrait;
    use LimitInfoAwareTrait;
    use SettlementCheckReadRepositoryCreateTrait;
    use SortInfoAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function count(
        SettlementCheckFilterCondition $filterCondition,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): int {
        return $this->prepareRepository($filterCondition, $systemAccountId, $isReadOnlyDb)->count();
    }

    /**
     * @param SettlementCheckFilterCondition $filterCondition
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return SettlementCheckListDto[]
     */
    public function load(
        SettlementCheckFilterCondition $filterCondition,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): array {
        $select = [
            'sch.*',
            'pmnt.amount AS payment_amount',
            's.settlement_no',
        ];
        $repo = $this
            ->prepareRepository($filterCondition, $systemAccountId, $isReadOnlyDb)
            ->joinPayment()
            ->joinSettlement()
            ->select($select);
        $repo = $this->sort($repo);

        $offset = $this->getOffset();
        if ($offset) {
            $repo->offset($offset);
        }
        $limit = $this->getLimit();
        if ($limit) {
            $repo->limit($limit);
        }

        $rows = $repo->loadRows();

        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = SettlementCheckListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    protected function sort(SettlementCheckReadRepository $repository): SettlementCheckReadRepository
    {
        $sortColumn = $this->getSortColumn();
        if ($sortColumn) {
            switch ($sortColumn) {
                case SettlementCheckListConstants::ORD_ADDRESS:
                    $repository->orderByAddress($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_AMOUNT:
                    $repository->orderByAmount($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_AMOUNT_SPELLING:
                    $repository->orderByAmountSpelling($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_CLEARED_ON:
                    $repository->orderByClearedOn($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_CREATED_ON:
                    $repository->orderByCreatedOn($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_POSTED_ON:
                    $repository->orderByPostedOn($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_PRINTED_ON:
                    $repository->orderByPrintedOn($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_VOIDED_ON:
                    $repository->orderByVoidedOn($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_MEMO:
                    $repository->orderByMemo($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_NOTE:
                    $repository->orderByNote($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_PAYEE:
                    $repository->orderByPayee($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_CHECK_NO:
                    $repository->orderByCheckNo($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_SETTLEMENT_NO:
                    $repository->orderBySettlementId($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_STATUS:
                    $repository->orderByStatus($this->isAscendingOrder());
                    break;
                case SettlementCheckListConstants::ORD_APPLIED_PAYMENT:
                    $repository->order('pmnt.amount', $this->isAscendingOrder());
                    break;

                default:
                    $repository->orderById($this->isAscendingOrder());
                    break;
            }
        }

        return $repository;
    }

    protected function prepareRepository(
        SettlementCheckFilterCondition $filterCondition,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): SettlementCheckReadRepository {
        $filterAccountId = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($systemAccountId)
            ->setSelectedAccountId($filterCondition->accountIds)
            ->detect();

        $repository = $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($filterCondition->settlementIds)
            ->filterStatus($filterCondition->status)
            ->joinSettlementFilterAccountId($filterAccountId)
            ->joinSettlementFilterSettlementStatusId(Constants\Settlement::$availableSettlementStatuses);


        $createdOnFrom = $this->convertRangeStartDateToUtc($filterCondition->createdOnFrom, $systemAccountId);
        if ($createdOnFrom) {
            $repository = $repository->filterCreatedOnGreaterOrEqual($createdOnFrom);
        }

        $createdOnTo = $this->convertRangeEndDateToUtc($filterCondition->createdOnTo, $systemAccountId);
        if ($createdOnTo) {
            $repository = $repository->filterCreatedOnLessOrEqual($createdOnTo);
        }
        $printedOnFrom = $this->convertRangeStartDateToUtc($filterCondition->printedOnFrom, $systemAccountId);
        if ($printedOnFrom) {
            $repository = $repository->filterPrintedOnGreaterOrEqual($printedOnFrom);
        }
        $printedOnTo = $this->convertRangeEndDateToUtc($filterCondition->printedOnTo, $systemAccountId);
        if ($printedOnTo) {
            $repository = $repository->filterPrintedOnLessOrEqual($printedOnTo);
        }
        $postedOnFrom = $this->convertRangeStartDateToUtc($filterCondition->postedOnFrom, $systemAccountId);
        if ($postedOnFrom) {
            $repository = $repository->filterPostedOnGreaterOrEqual($postedOnFrom);
        }
        $postedOnTo = $this->convertRangeEndDateToUtc($filterCondition->postedOnTo, $systemAccountId);
        if ($postedOnTo) {
            $repository = $repository->filterPostedOnLessOrEqual($postedOnTo);
        }
        $clearedOnFrom = $this->convertRangeStartDateToUtc($filterCondition->clearedOnFrom, $systemAccountId);
        if ($clearedOnFrom) {
            $repository = $repository->filterClearedOnGreaterOrEqual($clearedOnFrom);
        }
        $clearedOnTo = $this->convertRangeEndDateToUtc($filterCondition->clearedOnTo, $systemAccountId);
        if ($clearedOnTo) {
            $repository = $repository->filterClearedOnLessOrEqual($clearedOnTo);
        }
        $voidedOnFrom = $this->convertRangeStartDateToUtc($filterCondition->voidedOnFrom, $systemAccountId);
        if ($voidedOnFrom) {
            $repository = $repository->filterVoidedOnGreaterOrEqual($voidedOnFrom);
        }
        $voidedOnTo = $this->convertRangeEndDateToUtc($filterCondition->voidedOnTo, $systemAccountId);
        if ($voidedOnTo) {
            $repository = $repository->filterVoidedOnLessOrEqual($voidedOnTo);
        }
        if ($filterCondition->payee !== '') {
            $repository = $repository->filterPayee($filterCondition->payee);
        }
        if ($filterCondition->checkNo !== '') {
            $repository = $repository->filterCheckNo((int)$filterCondition->checkNo);
        }

        return $repository;
    }

    protected function convertRangeStartDateToUtc(string $date, int $systemAccountId): string
    {
        if (!$date) {
            return '';
        }

        try {
            $dateObject = new DateTime($date);
            $dateObject = $dateObject->setTime(0, 0);
            $dateObject = $this->getDateHelper()->convertSysToUtc($dateObject, $systemAccountId);
            return $dateObject->format(Constants\Date::ISO);
        } catch (Exception) {
            return '';
        }
    }

    protected function convertRangeEndDateToUtc(string $date, int $systemAccountId): string
    {
        if (!$date) {
            return '';
        }

        try {
            $dateObject = new DateTime($date);
            $dateObject = $dateObject->setTime(23, 59);
            $dateObject = $this->getDateHelper()->convertSysToUtc($dateObject, $systemAccountId);
            return $dateObject->format(Constants\Date::ISO);
        } catch (Exception) {
            return '';
        }
    }
}
