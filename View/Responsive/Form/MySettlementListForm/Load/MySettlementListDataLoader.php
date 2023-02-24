<?php
/**
 * My Settlement List Data Loader
 *
 * SAM-6309: Refactor My Settlement List page at client side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MySettlementListForm\Load;

use Sam\Account\CrossAccountTransparency\CrossAccountTransparencyCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepository;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepositoryCreateTrait;

/**
 * Class MySettlementListDataLoader
 */
class MySettlementListDataLoader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CrossAccountTransparencyCheckerCreateTrait;
    use EditorUserAwareTrait;
    use LimitInfoAwareTrait;
    use SettlementReadRepositoryCreateTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected ?int $filterStatus = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $status null means that there is no selected status
     * @return static
     */
    public function filterStatus(?int $status): static
    {
        $this->filterStatus = Cast::toInt($status, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterStatus(): ?int
    {
        return $this->filterStatus;
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

        if ($this->getSortColumn()) {
            switch ($this->getSortColumn()) {
                case 'settlement-num':
                    $repo->orderBySettlementNo($this->isAscendingOrder());
                    break;
                case 'date':
                    $repo->orderByCreatedOn($this->isAscendingOrder());
                    break;
                case 'num-items':    //relegate sorting of num items to QQ::Expand because it is a virtual attribute
                    break;
                case 'bidder':
                    $repo->joinUserOrderByUsername($this->isAscendingOrder());
                    break;
            }
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = MySettlementListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return SettlementReadRepository
     */
    protected function prepareSettlementRepository(): SettlementReadRepository
    {
        $filterAccountId = $this->getSystemAccountId();
        if (
            !$this->cfg()->get('core->portal->enabled')
            || $this->createCrossAccountTransparencyChecker()->isAvailableByAccount($this->getSystemAccount())
        ) {
            $filterAccountId = null;
        }

        $settlementRepository = $this->createSettlementReadRepository()
            ->enableReadOnlyDb(true)
            ->filterConsignorId($this->getEditorUserId())
            ->filterSettlementStatusId(Constants\Settlement::$publicAvailableSettlementStatuses)
            ->joinUser()
            ->select(
                [
                    's.account_id',
                    's.id',
                    's.settlement_no',
                    's.settlement_date',
                    's.created_on',
                    'u.username',
                    's.settlement_status_id',
                    '(SELECT SUM(subtotal) AS Total FROM settlement_item WHERE settlement_id = s.id AND active = true) AS total_amt',
                    '(SELECT IFNULL((SELECT id FROM auction WHERE id = (SELECT auction_id FROM settlement_item WHERE settlement_id = s.id and auction_id > 0 limit 1)), 0)) AS sale_id',
                ]
            );

        if ($filterAccountId) {
            $settlementRepository->filterAccountId($filterAccountId);
        }

        $filterSettlementStatusIds = in_array(
            $this->getFilterStatus(),
            Constants\Settlement::$availableSettlementStatuses,
            true
        )
            ? $this->getFilterStatus()
            : [Constants\Settlement::SS_PENDING, Constants\Settlement::SS_PAID, Constants\Settlement::SS_OPEN];
        $settlementRepository->filterSettlementStatusId($filterSettlementStatusIds);

        return $settlementRepository;
    }
}
