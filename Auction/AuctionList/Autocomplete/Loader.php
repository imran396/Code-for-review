<?php
/**
 * Data providing class
 *
 * SAM-4055: Auction list auto-complete
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Mar, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\AuctionList\Autocomplete;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class Loader
 */
class Loader extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use OptionsAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function load(): array
    {
        /**
         * We dont need to prepare Auction repository and fetch data from DB if we have -1 @see Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID
         * at auctionId filters of Auction list auto-complete options.
         * Because we dont have auction with ID -1 at DB and will receive mysql error.
         */
        if ($this->isUnassignedAuctionSelectedOnly()) {
            return [
                ['id' => Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID,]
            ];
        }

        $autoCompleteOptions = $this->getOptions();
        $repo = $this->prepareAuctionRepository()
            ->order('auction_date', false);

        $auctionStatusId = $autoCompleteOptions->getFilterAuctionStatusIds();
        if ($auctionStatusId) {
            $repo->filterAuctionStatusId($auctionStatusId);
        }

        $accountIds = $autoCompleteOptions->getFilterAccountIds();
        if ($accountIds) {
            $repo->filterAccountId($accountIds);
        }

        $totalLotsGreaterThan = $autoCompleteOptions->getTotalLotsGreaterThan();
        if ($totalLotsGreaterThan !== null) {
            $repo->joinAuctionCacheFilterTotalLotsGreater($totalLotsGreaterThan);
        }

        $bidderUserId = $autoCompleteOptions->getFilterBidderUserId();
        if ($bidderUserId !== null) {
            $repo->joinAuctionBidderFilterUserId($bidderUserId);
        }

        $searchKey = $autoCompleteOptions->getAuctionName();
        if ($searchKey) {
            $repo->likeName($searchKey);
            $repo->likeSaleNoConcatenated($searchKey);
            $repo->like(
                "IF(a.auction_type = '" . Constants\Auction::TIMED . "', a.end_date, a.start_closing_date)",
                "%{$searchKey}%"
            );
        }

        $filterAuctionIds = $this->removeUnassignedAuctionIdFromAuctionIds($autoCompleteOptions->getFilterAuctionIds());
        if ($filterAuctionIds) {
            $repo->filterId($filterAuctionIds);
        }

        $currencyId = $autoCompleteOptions->getFilterCurrencyId();
        if ($currencyId) {
            $repo->filterCurrency($currencyId);
        }

        $limit = $autoCompleteOptions->getLimit();
        if ($limit > 0) {
            $repo->limit($limit);
        }

        $filteredAuctionRows = $repo->loadRows();

        // Add prioritized auctions to list
        $rows = $this->fillWithPrioritizedAuctions($filteredAuctionRows);

        return $rows;
    }

    /**
     * @return AuctionReadRepository
     */
    protected function prepareAuctionRepository(): AuctionReadRepository
    {
        $repo = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($this->getOptions()->isReadOnlyDb())
            ->joinAccountFilterActive(true)
            ->joinAuctionTimezone()
            ->select(
                [
                    'a.account_id',
                    "IF(a.auction_type = '" . Constants\Auction::TIMED . "', "
                    . "a.end_date, "
                    . "a.start_closing_date) AS auction_date",
                    'a.auction_type',
                    'a.end_date',
                    'a.event_type',
                    'a.id',
                    'a.name',
                    'a.sale_num',
                    'a.sale_num_ext',
                    'a.start_closing_date',
                    'a.start_bidding_date',
                    'a.test_auction',
                    'atz.location AS timezone_location'
                ]
            );
        return $repo;
    }

    /**
     * Fill filtered auction rows with prioritized auctions in the beginning of array with considering their order,
     * remove repeatedly listed auctions.
     * @param array $rows
     * @return array
     */
    protected function fillWithPrioritizedAuctions(array $rows): array
    {
        $prioritizedAuctionRows = $this->loadPrioritizedAuctionRows();
        $rows = array_merge($prioritizedAuctionRows, $rows);
        $rows = array_unique($rows, SORT_REGULAR);
        return $rows;
    }

    /**
     * Load prioritized auction rows and locate them in array accordingly auction id order
     * @return array
     */
    protected function loadPrioritizedAuctionRows(): array
    {
        $prioritizedAuctionIds = $this->getOptions()->getPrioritizedAuctionIds();
        $prioritizedAuctionRows = [];
        if ($prioritizedAuctionIds) {
            // load rows for unassigned auction
            $unassignedAuctionId = Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID;
            if (in_array($unassignedAuctionId, $prioritizedAuctionIds, true)) {
                $prioritizedAuctionRows[] = [
                    'id' => $unassignedAuctionId,
                ];
            }
            // load rows for general auctions
            $filteredPrioritizedAuctionIds = $this->removeUnassignedAuctionIdFromAuctionIds($prioritizedAuctionIds);
            /** @noinspection PhpConditionAlreadyCheckedInspection */
            if ($filteredPrioritizedAuctionIds) {
                $auctionRows = $this->prepareAuctionRepository()
                    ->filterId($filteredPrioritizedAuctionIds)
                    ->loadRows();
                foreach ($filteredPrioritizedAuctionIds as $auctionId) {
                    foreach ($auctionRows as $row) {
                        if ((int)$row['id'] === $auctionId) {
                            $prioritizedAuctionRows[] = $row;
                        }
                    }
                }
            }
        }
        return $prioritizedAuctionRows;
    }

    /**
     * @return bool
     */
    private function isUnassignedAuctionSelectedOnly(): bool
    {
        return $this->getOptions()->getFilterAuctionIds() === [Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID];
    }

    /**
     * @param int[] $auctionIds
     * @return array
     */
    private function removeUnassignedAuctionIdFromAuctionIds(array $auctionIds): array
    {
        $unassignedAuctionId = Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID;
        if (
            $auctionIds
            && in_array($unassignedAuctionId, $auctionIds, true)
        ) {
            $unassignedAuctionIdPosition = array_search($unassignedAuctionId, $auctionIds, true);
            if ($unassignedAuctionIdPosition !== false) {
                unset($auctionIds[$unassignedAuctionIdPosition]);
                return $auctionIds;
            }
        }
        return $auctionIds;
    }
}
