<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\Internal\Load;

use LotItem;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;

/**
 * Class FilenamePatternAssociationLotLoader
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\Internal\Load
 * @interanl
 */
class FilenamePatternAssociationLotLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use LotItemCustDataReadRepositoryCreateTrait;
    use LotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $lotNumber
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotItem[]
     */
    public function loadByLotNumber(string $lotNumber, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $lotItems = $this->prepareRepository($auctionId, $isReadOnlyDb)
            ->joinAuctionLotItem()
            ->inlineCondition('CONCAT(ali.lot_num_prefix, ali.lot_num, ali.lot_num_ext) = ' . $this->escape($lotNumber))
            ->loadEntities();
        return $lotItems;
    }

    /**
     * @param int $itemNumber
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotItem[]
     */
    public function loadByItemNumber(int $itemNumber, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $lotItems = $this->prepareRepository($auctionId, $isReadOnlyDb)
            ->filterItemNum($itemNumber)
            ->loadEntities();
        return $lotItems;
    }

    /**
     * @param string $customFieldValue
     * @param LotItemCustField $lotCustomField
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotItem[]
     */
    public function loadByCustomField(string $customFieldValue, LotItemCustField $lotCustomField, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $customDataRepository = $this->createLotItemCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['licd.lot_item_id'])
            ->filterActive(true)
            ->filterLotItemCustFieldId($lotCustomField->Id);
        if ($lotCustomField->isNumeric()) {
            $customDataRepository = $customDataRepository->filterNumeric((int)$customFieldValue);
        } else {
            $customDataRepository = $customDataRepository->filterText($customFieldValue);
        }

        $lotItemIds = $customDataRepository->loadRows();
        if (!$lotItemIds) {
            return [];
        }

        $lotItemIds = ArrayHelper::flattenArray($lotItemIds);
        $lotItems = $this->prepareRepository($auctionId, $isReadOnlyDb)
            ->filterId($lotItemIds)
            ->loadEntities();
        return $lotItems;
    }

    /**
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotItemReadRepository
     */
    protected function prepareRepository(int $auctionId, bool $isReadOnlyDb): LotItemReadRepository
    {
        $repository = $this->createLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinAuctionLotItemFilterAuctionId($auctionId)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses);
        return $repository;
    }
}
