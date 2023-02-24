<?php
/**
 * It is using for fetching and removing auctions from sale group and also adding auction into sale group.
 *
 * Related tickets:
 * SAM-3865 : Sale group manager class https://bidpath.atlassian.net/browse/SAM-3865
 *
 * @author        Imran Rahman
 * Filename       SaleGroupManager.php
 * @version       SAM 2.0
 * @since         October 21, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 */

namespace Sam\Auction\SaleGroup;

use Auction;
use AuctionBidder;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class SaleGroupManager
 * @package Sam\Auction\SaleGroup
 */
class SaleGroupManager extends CustomizableClass
{
    use AuctionBidderReadRepositoryCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use AuctionWriteRepositoryAwareTrait;

    /**
     * Extending class needs to be class or implement getInstance method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get the Auctions that belong this sale group
     *
     * @param string $saleGroup the sale group name
     * @param int $accountId the auction account
     * @param bool $shouldIncludeWithoutGroup optional if true, will include auctions that doesn't have a sale group yet
     * @param int[] $skipIds auction not to be included
     * @param bool $isReadOnlyDb
     * @return Auction[]|null
     */
    public function loadAuctions(
        string $saleGroup,
        int $accountId,
        bool $shouldIncludeWithoutGroup = false,
        array $skipIds = [],
        bool $isReadOnlyDb = false
    ): ?array {
        $auctionRepository = $this->prepareAuctionRepository(
            $saleGroup,
            $accountId,
            $shouldIncludeWithoutGroup,
            $skipIds,
            $isReadOnlyDb
        );
        $auctions = $auctionRepository ? $auctionRepository->loadEntities() : [];
        return $auctions;
    }

    public function loadSelected(
        array $select,
        string $saleGroup,
        int $accountId,
        bool $shouldIncludeWithoutGroup = false,
        array $skipIds = [],
        bool $isReadOnlyDb = false
    ): array {
        $auctionRepository = $this->prepareAuctionRepository(
            $saleGroup,
            $accountId,
            $shouldIncludeWithoutGroup,
            $skipIds,
            $isReadOnlyDb
        );
        if (!$auctionRepository) {
            return [];
        }
        return $auctionRepository
            ->select($select)
            ->loadRows();
    }

    /**
     * Get the Auctions that belong this sale group
     *
     * @param string $saleGroup the sale group name
     * @param int $accountId the auction account
     * @param bool $shouldIncludeWithoutGroup optional if true, will include auctions that doesn't have a sale group yet
     * @param int[] $skipIds auction not to be included
     * @param bool $isReadOnlyDb
     * @return Auction[]|null
     */
    public function loadAuctionRows(
        string $saleGroup,
        int $accountId,
        bool $shouldIncludeWithoutGroup = false,
        array $skipIds = [],
        bool $isReadOnlyDb = false
    ): ?array {
        $auctionRepository = $this->prepareAuctionRepository(
            $saleGroup,
            $accountId,
            $shouldIncludeWithoutGroup,
            $skipIds,
            $isReadOnlyDb
        );
        $select = [
            'a.account_id',
            'a.test_auction',
            'a.created_on',
            'a.id',
            'a.name',
            'IF(a.sale_group = "", 0, 1) AS selected',
            'a.sale_num',
            'a.sale_num_ext',
            'adc_by_su.value AS auction_seo_url',
        ];
        return $auctionRepository?->select($select)->loadRows();
    }

    /**
     * Get the Auctions count that belong this sale group
     *
     * @param string $saleGroup the sale group name
     * @param int $accountId the auction account
     * @param bool $shouldIncludeWithoutGroup optional if true, will include auctions that doesn't have a sale group yet
     * @param int[] $skipIds auction not to be included
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function countAuctions(
        string $saleGroup,
        int $accountId,
        bool $shouldIncludeWithoutGroup = false,
        array $skipIds = [],
        bool $isReadOnlyDb = false
    ): ?int {
        $auctionRepository = $this->prepareAuctionRepository(
            $saleGroup,
            $accountId,
            $shouldIncludeWithoutGroup,
            $skipIds,
            $isReadOnlyDb
        );
        $count = $auctionRepository?->count();
        return $count;
    }

    /**
     * @param string $saleGroup
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return AuctionBidder[]
     */
    public function loadAuctionBidders(string $saleGroup, int $userId, bool $isReadOnlyDb = false): array
    {
        $auctionBidderRepository = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinAuctionFilterSaleGroup($saleGroup)
            ->filterUserId($userId);
        $auctionBidders = $auctionBidderRepository->loadEntities();
        return $auctionBidders;
    }

    /**
     * Add Auctions to this sale group
     *
     * @param string $saleGroup the sale group name
     * @param int[] $auctionIds
     * @param int $editorUserId
     * @param bool $isSimultaneous
     * @param int|null $skipAuctionId
     * @return void
     */
    public function addAuctions(
        string $saleGroup,
        array $auctionIds,
        int $editorUserId,
        bool $isSimultaneous = false,
        ?int $skipAuctionId = null
    ): void {
        if (
            !$saleGroup
            || !count($auctionIds)
        ) {
            return;
        }

        // cleanup first
        $firstAuctionId = $auctionIds[0];
        $firstAuction = $this->getAuctionLoader()->load($firstAuctionId);
        if (!$firstAuction) {
            log_error("Available auction not found for adding to sale group" . composeSuffix(['a' => $firstAuctionId]));
            return;
        }
        $this->removeAuctionsByAccountId($saleGroup, $firstAuction->AccountId, $editorUserId, $skipAuctionId);

        $auctions = $this->createAuctionReadRepository()
            ->filterId($auctionIds)
            ->loadEntities();
        foreach ($auctions as $auction) {
            $auction->SaleGroup = $saleGroup;
            $auction->Simultaneous = $isSimultaneous;
            $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
        }
    }

    /**
     * Remove Auctions from this sale group
     *
     * @param string $saleGroup
     * @param int $accountId
     * @param int $editorUserId
     * @param int|null $skipAuctionId
     * @return void
     */
    public function removeAuctionsByAccountId(
        string $saleGroup,
        int $accountId,
        int $editorUserId,
        ?int $skipAuctionId = null
    ): void {
        $auctionRepository = $this->createAuctionReadRepository()
            ->filterAccountId($accountId)
            ->filterSaleGroup($saleGroup)
            ->skipId((array)$skipAuctionId);
        $auctions = $auctionRepository->loadEntities();
        $this->removeAuctions($auctions, $editorUserId);
    }

    /***
     * Remove Auctions from this sale group
     *
     * @param string $saleGroup
     * @param int[] $auctionIds
     * @param int $editorUserId
     * @return void
     * @noinspection PhpUnused
     */
    public function removeAuctionsById(string $saleGroup, array $auctionIds, int $editorUserId): void
    {
        if (!empty($auctionIds)) {
            $auctionRepository = $this->createAuctionReadRepository()
                ->filterId($auctionIds)
                ->filterSaleGroup($saleGroup);
            $auctions = $auctionRepository->loadEntities();
            $this->removeAuctions($auctions, $editorUserId);
        }
    }

    /**
     * @param Auction[] $auctions
     * @param int $editorUserId
     * @return void
     */
    protected function removeAuctions(array $auctions, int $editorUserId): void
    {
        foreach ($auctions as $auction) {
            $auction->SaleGroup = '';
            $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
        }
    }

    /**
     * @param string $saleGroup
     * @param int $accountId
     * @param bool $shouldIncludeWithoutGroup
     * @param int[] $skipAuctionIds
     * @param bool $isReadOnlyDb
     * @return AuctionReadRepository|null
     */
    protected function prepareAuctionRepository(
        string $saleGroup,
        int $accountId,
        bool $shouldIncludeWithoutGroup,
        array $skipAuctionIds,
        bool $isReadOnlyDb = false
    ): ?AuctionReadRepository {
        if (
            $saleGroup === ''
            && $shouldIncludeWithoutGroup === false
        ) {
            return null;
        }

        $auctionRepository = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionDetailsCacheBySeoUrl()
            ->skipId($skipAuctionIds);

        if ($shouldIncludeWithoutGroup) {
            $auctionRepository->filterSaleGroup(['', $saleGroup]);
        } else {
            $auctionRepository->filterSaleGroup($saleGroup);
        }

        $auctionRepository
            ->orderBySaleGroup(false)
            ->orderByCreatedOn(false)
            ->orderBySaleNum(false)
            ->orderBySaleNumExt(false);
        return $auctionRepository;
    }

    /**
     * Remove simultaneous auction
     * @param Auction $auction
     * @param int $editorUserId
     * @return void
     */
    public function removeAuction(Auction $auction, int $editorUserId): void
    {
        if ($auction->SaleGroup !== '') {
            $count = $this->countAuctions($auction->SaleGroup, $auction->AccountId);
            if ($count === 1) {
                $auctions = $this->loadAuctions($auction->SaleGroup, $auction->AccountId);
                $auctionGroup = next($auctions);
                if ($auctionGroup) {
                    $auction->SaleGroup = '';
                    $auction->Simultaneous = false;
                    $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
                    $auctionGroup->SaleGroup = '';
                    $auctionGroup->Simultaneous = false;
                    $this->getAuctionWriteRepository()->saveWithModifier($auctionGroup, $editorUserId);
                }
            }
        }
    }

    /**
     * Check existence of auction with respective sale group name in account
     * @param string $saleGroup
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBySaleGroup(string $saleGroup, int $accountId, bool $isReadOnlyDb = false): bool
    {
        return $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterSaleGroup($saleGroup)
            ->exist();
    }
}
