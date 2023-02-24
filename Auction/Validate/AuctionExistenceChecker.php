<?php
/**
 *SAM-4069 : Auction Existence checker
 * https://bidpath.atlassian.net/browse/SAM-4069
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         March 22, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Auction\Validate;

use Sam\Auction\SaleNo\Parse\SaleNoParserCreateTrait;
use Sam\Core\Auction\SaleNo\Parse\SaleNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\AuctionAllFilterTrait;

/**
 * Class AuctionExistenceChecker
 * @package Sam\Auction\Validate
 */
class AuctionExistenceChecker extends CustomizableClass
{
    use AuctionAllFilterTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use SaleNoParserCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Check whether auction is exists or not
     * @param int|null $auctionId . null means missing auction id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if (!$auctionId) {
            return false;
        }
        $fn = function () use ($auctionId, $isReadOnlyDb) {
            $isFound = $this->prepareRepository($isReadOnlyDb)
                ->filterId($auctionId)
                ->exist();
            return $isFound;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::AUCTION_ID, $auctionId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $isFound = $this->getEntityMemoryCacheManager()
            ->existWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $isFound;
    }

    /**
     * Check whether an active sale with that sale number and ext exists
     *
     * @param int|null $saleNum sale number, null leads to not found result
     * @param string $saleNumExt sale number ext
     * @param int[] $skipAuctionIds optional Auction->Id to exclude from search
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBySaleNo(
        ?int $saleNum,
        string $saleNumExt,
        array $skipAuctionIds = [],
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): bool {
        if (!$saleNum) {
            return false;
        }

        $auctionRepository = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterSaleNum($saleNum)
            ->filterSaleNumExt($saleNumExt)
            ->skipId($skipAuctionIds);
        if ($accountId !== null) {
            $auctionRepository->filterAccountId($accountId);
        }
        $isFound = $auctionRepository->exist();
        return $isFound;
    }

    /**
     * @param SaleNoParsed $saleNoParsed
     * @param array $skipAuctionIds
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBySaleNoParsed(
        SaleNoParsed $saleNoParsed,
        array $skipAuctionIds = [],
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->existBySaleNo(
            $saleNoParsed->saleNum,
            $saleNoParsed->saleNumExtension,
            $skipAuctionIds,
            $accountId,
            $isReadOnlyDb
        );
    }

    /**
     * Exist by full saleNo
     * @param string $saleNo Concatenated full sale#
     * @param int[] $skipAuctionIds optional Auction->Id to exclude from search
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByFullSaleNo(
        string $saleNo,
        array $skipAuctionIds = [],
        ?int $accountId = null,
        bool $isReadOnlyDb = false
    ): bool {
        $saleNoParser = $this->createSaleNoParser()->construct();
        if (!$saleNoParser->validate($saleNo)) {
            return false;
        }

        $saleNoParsed = $saleNoParser->parse($saleNo);
        $isFound = $this->existBySaleNoParsed(
            $saleNoParsed,
            $skipAuctionIds,
            $accountId,
            $isReadOnlyDb
        );
        return $isFound;
    }
}
