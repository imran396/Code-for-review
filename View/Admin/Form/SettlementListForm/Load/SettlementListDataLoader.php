<?php
/**
 * Settlement List Data Loader
 *
 * SAM-6279: Refactor Settlement List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 10, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementListForm\Load;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepository;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepositoryCreateTrait;
use Sam\View\Admin\Form\SettlementListForm\SettlementListConstants;

/**
 * Class SettlementListDataLoader
 */
class SettlementListDataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use DbConnectionTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SettlementReadRepositoryCreateTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    /** @var int|null */
    protected ?int $filterSettlementStatus = null;
    /** @var int|null */
    protected ?int $filterConsignorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $status null means that there is no status passed - filter by all available statuses
     * @return static
     */
    public function filterSettlementStatus(?int $status): static
    {
        $this->filterSettlementStatus = $status;
        return $this;
    }

    /**
     * @param int|null $consignorUserId null means that there is no consignor id passed
     * @return static
     */
    public function filterConsignorUserId(?int $consignorUserId): static
    {
        $this->filterConsignorUserId = Cast::toInt($consignorUserId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterConsignorUserId(): ?int
    {
        return $this->filterConsignorUserId;
    }

    /**
     * @return int - return value of Settlements count
     */
    public function count(): int
    {
        return $this->prepareSettlementRepository()->count();
    }

    /**
     * @return array - return values for Settlements
     */
    public function load(): array
    {
        $repo = $this->prepareSettlementRepository();

        switch ($this->getSortColumn()) {
            case SettlementListConstants::ORD_SETTLEMENT_DATE:
                $repo->order('settlement_date', $this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_USERNAME:
                $repo->joinUserOrderByUsername($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_COST_TOTAL:
                $repo->orderByCostTotal($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_TAXABLE_TOTAL:
                $repo->orderByTaxableTotal($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_NON_TAXABLE_TOTAL:
                $repo->orderByNonTaxableTotal($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_EXPORT_TOTAL:
                $repo->orderByExportTotal($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_TAX_EXCLUSIVE:
                $repo->orderByTaxExclusive($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_TAX_INCLUSIVE:
                $repo->orderByTaxInclusive($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_TAX_SERVICES:
                $repo->orderByTaxServices($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_SETTLEMENT_NO:
                $repo->orderBySettlementNo($this->isAscendingOrder());
                break;
            case SettlementListConstants::ORD_FEES_COMMISSION_TOTAL:
                $repo->orderByFeesCommissionTotal($this->isAscendingOrder());
                break;
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = SettlementListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return SettlementReadRepository
     */
    protected function prepareSettlementRepository(): SettlementReadRepository
    {
        $settlementRepository = $this->createSettlementReadRepository()
            ->joinAccountFilterActive(true)
            ->joinUser()
            ->joinUserInfo()
            ->joinUserBilling()
            ->joinUserShipping()
            ->select(
                [
                    's.id AS id',
                    's.settlement_no AS settlement_no',
                    's.settlement_status_id AS settlement_status_id',
                    'u.username AS username',
                    'ui.first_name AS first_name',
                    'ui.last_name AS last_name',
                    'ui.phone AS iphone',
                    'ub.phone AS bphone',
                    'us.phone AS sphone',
                    's.cost_total AS cost_total',
                    's.taxable_total AS taxable_total',
                    's.non_taxable_total AS non_taxable_total',
                    's.export_total AS export_total',
                    'IFNULL(s.comm_total, 0) ' .
                    '+ IFNULL(s.extra_charges, 0) ' .
                    '+ (select SUM(fee) FROM settlement_item si WHERE si.settlement_id = s.id) ' .
                    'AS fees_comm_total ',
                    's.tax_inclusive AS tax_inclusive',
                    's.tax_exclusive AS tax_exclusive',
                    's.tax_services AS tax_services',
                    "IF(s.settlement_date IS NULL OR s.settlement_date = '', "
                    . "s.created_on, s.settlement_date) AS settlement_date",
                ]
            );

        if ($this->isAccountFiltering()) {
            if ($this->getFilterAccountId()) {
                $settlementRepository->filterAccountId($this->getFilterAccountId());
            }
        } else {
            $settlementRepository->filterAccountId($this->getSystemAccountId());
        }

        if (isset(Constants\Settlement::$settlementStatusNames[$this->filterSettlementStatus])) {
            $settlementRepository->filterSettlementStatusId($this->filterSettlementStatus);
        } else {
            $settlementRepository->filterSettlementStatusId(Constants\Settlement::$availableSettlementStatuses);
        }

        if ($this->getFilterConsignorUserId()) {
            $settlementRepository->filterConsignorId($this->getFilterConsignorUserId());
        }

        if ($this->getFilterAuctionId()) {
            $settlementRepository->inlineCondition(
                "(SELECT COUNT(1) FROM settlement_item " .
                "WHERE settlement_id = s.id " .
                "AND auction_id = " . $this->escape($this->getFilterAuctionId()) . ") > 0"
            );
        }

        return $settlementRepository;
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            true
        );
    }
}
