<?php
/**
 * Unassigned Lot Items Data Loader
 *
 * SAM-6273: Refactor Lot Items Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 9, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotItemsPanel\Load;

use LotItemCustField;
use Sam\Account\Filter\AccountApplicationFilterDetectorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Qform\LotList\LotListCustomFieldAdminFilterHelperAwareTrait;
use Sam\Report\Lot\Inventory\FilterAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Admin\Form\LotItemsPanel\UnassignedLotItemsConstants;

/**
 * Class UnassignedLotItemsDataLoader
 */
class UnassignedLotItemsDataLoader extends CustomizableClass
{
    use AccountApplicationFilterDetectorAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use LimitInfoAwareTrait;
    use LotListCustomFieldAdminFilterHelperAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * @var array<int, LotItemCustField>
     */
    protected array $shownLotCustomFields = [];

    /**
     * @var array<int, LotItemCustField>
     */
    protected array $filterLotCustomFields = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return int - return value of Unassigned Lot Items count
     */
    public function count(bool $isReadOnlyDb = false): int
    {
        return $this->buildDataSource($isReadOnlyDb)->getCount();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return array - return values for Unassigned Lot Items
     */
    public function load(bool $isReadOnlyDb = false): array
    {
        return $this->buildDataSource($isReadOnlyDb)->getResults();
    }

    public function setShownLotCustomFields(array $shownLotCustomFields): static
    {
        $this->shownLotCustomFields = $shownLotCustomFields;
        return $this;
    }

    public function setFilterLotCustomFields(array $filterLotCustomFields): static
    {
        $this->filterLotCustomFields = $filterLotCustomFields;
        return $this;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return DataSourceMysql
     */
    protected function buildDataSource(bool $isReadOnlyDb): DataSourceMysql
    {
        $resultSetFields = $this->buildResultSetFields();
        $mappedLotCustomFields = array_merge($this->filterLotCustomFields, $this->shownLotCustomFields);
        $mappedLotCustomFields = ArrayHelper::uniqueEntities($mappedLotCustomFields, 'Id');

        $dataSource = DataSourceMysql::new()
            ->enablePublic(false)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->setLimit($this->getLimit())
            ->setMappedLotCustomFields($mappedLotCustomFields)
            ->setOffset($this->getOffset())
            ->setResultSetFields($resultSetFields)
            ->setUserId($this->getEditorUserId());

        $this->applyOrder($dataSource);
        $this->applyFiltration($dataSource);
        return $dataSource;
    }

    /**
     * @return array
     */
    protected function buildResultSetFields(): array
    {
        $resultSetFields = [
            'account_id',
            'account_name',
            'auction_info',
            'consignor_id',
            'consignor_username',
            'created_username',
            'hammer_price',
            'high_estimate',
            'id',
            'image_count',
            'inv_id',
            'item_num',
            'item_num_ext',
            'lot_desc',
            'lot_name',
            'low_estimate',
            'modified_username',
            'starting_bid',
            'winner_company',
            'winner_username',
        ];
        foreach ($this->shownLotCustomFields as $customField) {
            $resultSetFields[] = $this->getBaseCustomFieldHelper()->makeFieldAlias($customField->Name);
        }
        return $resultSetFields;
    }

    /**
     * @param DataSourceMysql $dataSource
     */
    protected function applyFiltration(DataSourceMysql $dataSource): void
    {
        $filterAccountIds = ArrayCast::makeIntArray(
            $this->getFilterAccountId(),
            Constants\Type::F_INT_POSITIVE
        );
        $filterAccountIds = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($this->getSystemAccountId())
            ->setSelectedAccountId($filterAccountIds)
            ->detect();
        $dataSource->filterAccountIds($filterAccountIds);

        $filterAuctionIds = ArrayCast::makeIntArray(
            $this->getFilterAuctionId(),
            Constants\Type::F_INT_POSITIVE
        );
        $dataSource->filterAuctionIds($filterAuctionIds);

        $filterLotCategoryIds = ArrayCast::makeIntArray(
            $this->getLotCategoryId(),
            Constants\Type::F_INT_POSITIVE
        );
        $dataSource->filterLotCategoryIds($filterLotCategoryIds);

        $filterConsignorUserIds = ArrayCast::makeIntArray(
            $this->getConsignorUserId(),
            Constants\Type::F_INT_POSITIVE
        );
        $dataSource->filterConsignorIds($filterConsignorUserIds);

        $filterParams = $this->getLotListCustomFieldAdminFilterHelper()->getFilterParams();
        $dataSource->filterCustomFields($filterParams);

        $filterOverallLotStatus = $this->getOverallLotStatus();
        $dataSource->filterOverallLotStatus($filterOverallLotStatus);

        $searchKey = $this->getSearchKey();
        $dataSource->setSearchKey($searchKey);
    }

    /**
     * @param DataSourceMysql $dataSource
     */
    protected function applyOrder(DataSourceMysql $dataSource): void
    {
        $alias = Cast::toString($this->getSortColumn()) ?: UnassignedLotItemsConstants::ORD_DEFAULT;
        $direction = $this->isAscendingOrder() ? 'asc' : 'desc';
        $dataSource->orderBy("{$alias} {$direction}");
    }
}
