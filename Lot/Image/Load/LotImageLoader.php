<?php
/**
 * SAM-4464: Apply Lot Image Loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/19/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Load;

use LotImage;
use Sam\Core\Constants;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepository;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;

/**
 * Class LotImageLoader
 * @package Sam\Lot\Image\Load
 */
class LotImageLoader extends EntityLoaderBase
{
    use LotImageReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $lotImageId
     * @param bool $isReadOnlyDb
     * @return LotImage|null
     */
    public function load(?int $lotImageId, bool $isReadOnlyDb = false): ?LotImage
    {
        if (!$lotImageId) {
            return null;
        }

        return $this->createLotImageReadRepository()
            ->filterId($lotImageId)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntity();
    }

    /**
     * Load all images for lot
     * @param int|null $lotItemId
     * @param int[] $skipLotImageIds
     * @param bool $isReadOnlyDb
     * @return LotImage[]
     */
    public function loadForLot(?int $lotItemId, array $skipLotImageIds = [], bool $isReadOnlyDb = false): array
    {
        if (!$lotItemId) {
            return [];
        }

        return $this->createLotImageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->skipId($skipLotImageIds)
            ->orderByOrder()
            ->loadEntities();
    }

    /**
     * Load main image
     * @param int|null $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotImage|null
     */
    public function loadDefaultForLot(?int $lotItemId, bool $isReadOnlyDb = false): ?LotImage
    {
        if (!$lotItemId) {
            return null;
        }

        return $this->createLotImageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->orderByOrder()
            ->orderById()
            ->loadEntity();
    }

    /**
     * Return quantity of lot images for lot
     * @param int|null $lotItemId null result to 0
     * @return int
     */
    public function countByLotItemId(?int $lotItemId): int
    {
        if (!$lotItemId) {
            return 0;
        }

        return $this->createLotImageReadRepository()
            ->filterLotItemId($lotItemId)
            ->count();
    }

    /**
     * @param int|null $lotItemId
     * @param string|null $imageLink
     * @param bool $isReadOnlyDb
     * @return LotImage|null
     */
    public function loadByLotItemIdAndImageLink(
        ?int $lotItemId,
        ?string $imageLink,
        bool $isReadOnlyDb = false
    ): ?LotImage {
        if (
            !$lotItemId
            || !$imageLink
        ) {
            return null;
        }

        return $this->createLotImageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->filterImageLink($imageLink)
            ->loadEntity();
    }

    /**
     * Load lot images by file name
     *
     * @param string|null $imageLink
     * @param array $skipLotItemIds excluded lot_item ids
     * @param array $skipLotImageIds excluded lot_image ids
     * @param bool $isReadOnlyDb
     * @return LotImage[]
     */
    public function loadByImageLink(
        ?string $imageLink,
        array $skipLotItemIds = [],
        array $skipLotImageIds = [],
        bool $isReadOnlyDb = false
    ): array {
        if (!$imageLink) {
            return [];
        }

        return $this
            ->prepareRepositoryForLoadingByImageLink($imageLink, $skipLotItemIds, $skipLotImageIds, $isReadOnlyDb)
            ->loadEntities();
    }

    /**
     * Load lot images by file name and auction id
     *
     * @param int|null $auctionId
     * @param string|null $imageLink
     * @param array $skipLotItemIds excluded lot_item ids
     * @param array $skipLotImageIds excluded lot_image ids
     * @param bool $isReadOnlyDb
     * @return LotImage[]
     */
    public function loadByAuctionIdAndImageLink(
        ?int $auctionId,
        ?string $imageLink,
        array $skipLotItemIds = [],
        array $skipLotImageIds = [],
        bool $isReadOnlyDb = false
    ): array {
        if (
            !$auctionId
            || !$imageLink
        ) {
            return [];
        }

        return $this
            ->prepareRepositoryForLoadingByImageLink($imageLink, $skipLotItemIds, $skipLotImageIds, $isReadOnlyDb)
            ->joinAuctionLotItemFilterAuctionId($auctionId)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->loadEntities();
    }

    /**
     * Load first lot images by file name and auction id
     *
     * @param int|null $auctionId
     * @param string|null $imageLink
     * @param bool $isReadOnlyDb
     * @return LotImage|null
     */
    public function loadFirstByAuctionIdAndImageLink(
        ?int $auctionId,
        ?string $imageLink,
        bool $isReadOnlyDb = false
    ): ?LotImage {
        if (
            !$auctionId
            || !$imageLink
        ) {
            return null;
        }

        return $this
            ->prepareRepositoryForLoadingByImageLink($imageLink, [], [], $isReadOnlyDb)
            ->joinAuctionLotItemFilterAuctionId($auctionId)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->limit(1)
            ->loadEntity();
    }

    /**
     * Load lot images by file name and account id
     *
     * @param int|null $accountId
     * @param string|null $imageLink
     * @param array $skipLotItemIds excluded lot_item ids
     * @param array $skipLotImageIds excluded lot_image ids
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadByAccountIdAndImageLink(
        ?int $accountId,
        ?string $imageLink,
        array $skipLotItemIds = [],
        array $skipLotImageIds = [],
        bool $isReadOnlyDb = false
    ): array {
        if (
            !$accountId
            || !$imageLink
        ) {
            return [];
        }

        return $this
            ->prepareRepositoryForLoadingByImageLink($imageLink, $skipLotItemIds, $skipLotImageIds, $isReadOnlyDb)
            ->joinLotItemFilterAccountId($accountId)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->loadEntities();
    }

    /**
     * @param string $imageLink
     * @param array $skipLotItemIds
     * @param array $skipLotImageIds
     * @param bool $isReadOnlyDb
     * @return LotImageReadRepository
     */
    protected function prepareRepositoryForLoadingByImageLink(
        string $imageLink,
        array $skipLotItemIds,
        array $skipLotImageIds,
        bool $isReadOnlyDb = false
    ): LotImageReadRepository {
        return $this->createLotImageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinLotItemFilterActive(true)
            ->filterImageLink($imageLink)
            ->skipId($skipLotImageIds)
            ->skipLotItemId($skipLotItemIds);
    }
}
