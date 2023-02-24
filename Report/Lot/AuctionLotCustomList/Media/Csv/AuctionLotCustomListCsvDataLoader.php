<?php
/**
 * SAM-4644: Refactor "Custom CSV Export" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Media\Csv;

use CustomCsvExportData;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportData\AuctionLotCustomListCsvReportDataCollection as ReportDataCollection;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField\AuctionLotCustomListCsvReportFieldCollection as ReportFieldCollection;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\CustomCsvExportData\CustomCsvExportDataReadRepository;
use Sam\Storage\ReadRepository\Entity\CustomCsvExportData\CustomCsvExportDataReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepository;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepository;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepository;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * This class is responsible for loading auction lot custom list report data.
 * Using only for reports in CSV format.
 *
 * Class AuctionLotCustomListCsvDataLoader
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv
 */
class AuctionLotCustomListCsvDataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use CustomCsvExportDataReadRepositoryCreateTrait;
    use LocationReadRepositoryCreateTrait;
    use LotCategoryReadRepositoryCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotImageReadRepositoryCreateTrait;
    use LotItemCustDataReadRepositoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use UserReadRepositoryCreateTrait;

    /**
     * @var LotItemCustField[]
     */
    private ?array $lotCustomFields = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $configId
     * @return CustomCsvExportData[]
     */
    public function loadFieldsConfig(int $configId): array
    {
        return $this->prepareCustomCsvExportDataRepository($configId)->loadEntities();
    }

    /**
     * @param int $configId
     * @return CustomCsvExportDataReadRepository
     */
    private function prepareCustomCsvExportDataRepository(int $configId): CustomCsvExportDataReadRepository
    {
        $repository = $this->createCustomCsvExportDataReadRepository()
            ->filterConfigId($configId)
            ->filterActive(true)
            ->orderByFieldOrder()
            ->orderByFieldName()
            ->orderById();
        return $repository;
    }

    /**
     * Load report data
     * @param int $auctionId
     * @param ReportFieldCollection $reportFields
     * @return ReportDataCollection
     */
    public function loadReportData(int $auctionId, ReportFieldCollection $reportFields): ReportDataCollection
    {
        $lotFields = $reportFields->getMapping();
        $lotRows = $this->prepareAuctionLotItemRepository($auctionId, $lotFields)->loadRows();

        $collection = ReportDataCollection::new()->buildFromLots($lotRows);

        if ($reportFields->hasLotImageField()) {
            $lotsIds = $collection->getLotItemIdList();
            $imageRows = $this->prepareLotImageRepository($lotsIds)->loadRows();
            $collection = $collection->addLotImages($imageRows);
        }

        if ($reportFields->hasCategoryField()) {
            $lotsIds = $collection->getLotItemIdList();
            $categoryRows = $this->prepareLotCategoryRepository($lotsIds)->loadRows();
            $collection = $collection->addLotCategories($categoryRows);
        }

        if ($reportFields->hasCustomField()) {
            $lotsIds = $collection->getLotItemIdList();
            $customFieldsData = $this->loadCustomFieldsData($lotsIds);
            $collection = $collection->addCustomFieldsData($customFieldsData);
        }

        if ($reportFields->hasConsignorField()) {
            $consignorUserIds = $collection->extractFieldValues('consignor_id');
            $consignorRows = $this->prepareConsignorRepository($consignorUserIds)->loadRows();
            $collection = $collection->addConsignors($consignorRows);
        }

        if ($reportFields->hasLocationField()) {
            $locationIds = $collection->extractFieldValues('location_id');
            $locationRows = $this->prepareLocationRepository($locationIds)->loadRows();
            $collection = $collection->addLocations($locationRows);
        }

        return $collection;
    }

    /**
     * @param int $auctionId
     * @param array $select
     * @return AuctionLotItemReadRepository
     */
    private function prepareAuctionLotItemRepository(int $auctionId, array $select): AuctionLotItemReadRepository
    {
        if (!in_array('lot_item_id', $select, true)) {
            $select[] = 'ali.lot_item_id';
        }

        $repository = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinTimedOnlineItem()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->orderByLotNumPrefix()
            ->select($select);
        return $repository;
    }

    /**
     * @param array $lotsIds
     * @return LotImageReadRepository
     */
    private function prepareLotImageRepository(array $lotsIds): LotImageReadRepository
    {
        $repository = $this->createLotImageReadRepository()
            ->enableReadOnlyDb(true)
            ->filterLotItemId($lotsIds)
            ->orderByOrder()
            ->select(['id', 'image_link', 'lot_item_id']);
        return $repository;
    }

    /**
     * @param array $lotsIds
     * @return LotCategoryReadRepository
     */
    private function prepareLotCategoryRepository(array $lotsIds): LotCategoryReadRepository
    {
        $repository = $this->createLotCategoryReadRepository()
            ->enableReadOnlyDb(true)
            //->filterActive(true)
            ->joinLotItemCategoryFilterLotItemId($lotsIds)
            ->orderByLotItemCategoryId()
            ->select(['lc.name', 'lic.lot_item_id']);
        return $repository;
    }

    /**
     * @param array $locationIds
     * @return LocationReadRepository
     */
    private function prepareLocationRepository(array $locationIds): LocationReadRepository
    {
        $locationIds = array_filter($locationIds);
        $locationIds = array_unique($locationIds);

        $repository = $this->createLocationReadRepository()
            ->enableReadOnlyDb(true)
            ->filterId($locationIds)
            ->select(['id', 'name']);
        return $repository;
    }

    /**
     * @param array $userIds
     * @return UserReadRepository
     */
    private function prepareConsignorRepository(array $userIds): UserReadRepository
    {
        $userIds = array_filter($userIds);
        $userIds = array_unique($userIds);

        $repository = $this->createUserReadRepository()
            ->enableReadOnlyDb(true)
            ->filterId($userIds)
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->select(['id', 'username']);
        return $repository;
    }

    /**
     * @param array $lotsIds
     * @return array
     */
    private function loadCustomFieldsData(array $lotsIds): array
    {
        $customFieldRows = $this->prepareCustomDataRepository($lotsIds)->loadRows();
        $customFieldRows = array_map(
            function (array $row) {
                $customField = $this->loadLotCustomField((int)$row['lot_item_cust_field_id']);
                if (!$customField) {
                    return null;
                }
                $row['customFieldObject'] = $customField;
                return $row;
            },
            $customFieldRows
        );
        $customFieldRows = array_filter($customFieldRows);
        return $customFieldRows;
    }

    /**
     * @param array $lotsIds
     * @return LotItemCustDataReadRepository
     */
    private function prepareCustomDataRepository(array $lotsIds): LotItemCustDataReadRepository
    {
        $repository = $this->createLotItemCustDataReadRepository()
            ->enableReadOnlyDb(true)
            ->filterActive(true)
            ->filterLotItemId($lotsIds)
            ->select(['lot_item_id', 'lot_item_cust_field_id', '`numeric`', '`text`']);
        return $repository;
    }

    /**
     * @param int $id
     * @return LotItemCustField|null
     */
    private function loadLotCustomField(int $id): ?LotItemCustField
    {
        if ($this->lotCustomFields === null) {
            $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        }
        foreach ($this->lotCustomFields as $custField) {
            if ($custField->Id === $id) {
                return $custField;
            }
        }
        return null;
    }
}
