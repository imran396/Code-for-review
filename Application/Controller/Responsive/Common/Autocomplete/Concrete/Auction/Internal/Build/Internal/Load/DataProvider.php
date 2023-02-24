<?php
/**
 * SAM-5466: Advanced search panel auction auto-complete configuration
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build\Internal\Load;

use Sam\Core\Lot\LotList\MyItems\AuctionAll;
use Sam\Core\Lot\LotList\MyItems\AuctionBidding;
use Sam\Core\Lot\LotList\MyItems\AuctionNotWon;
use Sam\Core\Lot\LotList\MyItems\AuctionWatchlist;
use Sam\Core\Lot\LotList\MyItems\AuctionWon;
use Sam\Core\Lot\LotList\MyItems\DataSource\AuctionAllMysql;
use Sam\Core\Lot\LotList\MyItems\DataSource\AuctionBiddingMysql;
use Sam\Core\Lot\LotList\MyItems\DataSource\AuctionConsignedMysql;
use Sam\Core\Lot\LotList\MyItems\DataSource\AuctionNotWonMysql;
use Sam\Core\Lot\LotList\MyItems\DataSource\AuctionWatchlistMysql;
use Sam\Core\Lot\LotList\MyItems\DataSource\AuctionWonMysql;
use Sam\Core\Lot\LotList\Search\AuctionSearch;
use Sam\Core\Lot\LotList\Search\DataSource\AuctionSearchMysql;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate\PageTypeChecker;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isSearchPage(string $pageType): bool
    {
        return PageTypeChecker::new()->isSearch($pageType);
    }

    public function isMyItemsAllPage(string $pageType): bool
    {
        return PageTypeChecker::new()->isMyItemsAll($pageType);
    }

    public function isMyItemsWonPage(string $pageType): bool
    {
        return PageTypeChecker::new()->isMyItemsWon($pageType);
    }

    public function isMyItemsNotWonPage(string $pageType): bool
    {
        return PageTypeChecker::new()->isMyItemsNotWon($pageType);
    }

    public function isMyItemsBiddingPage(string $pageType): bool
    {
        return PageTypeChecker::new()->isMyItemsBidding($pageType);
    }

    public function isAuctionWatchlistPage(string $pageType): bool
    {
        return PageTypeChecker::new()->isMyItemsWatchlist($pageType);
    }

    public function isAuctionConsignedPage(string $pageType): bool
    {
        return PageTypeChecker::new()->isMyItemsConsigned($pageType);
    }

    public function createAuctionSearchMysqlDataSource(): AuctionSearchMysql
    {
        return AuctionSearchMysql::new();
    }

    public function createAuctionSearchLotList(): AuctionSearch
    {
        return AuctionSearch::new();
    }

    public function createAuctionAllMysqlDataSource(): AuctionAllMysql
    {
        return AuctionAllMysql::new();
    }

    public function createAuctionAllLotList(): AuctionAll
    {
        return AuctionAll::new();
    }

    public function createAuctionWonMysqlDataSource(): AuctionWonMysql
    {
        return AuctionWonMysql::new();
    }

    public function createAuctionWonLotList(): AuctionWon
    {
        return AuctionWon::new();
    }

    public function createAuctionNotWonMysqlDataSource(): AuctionNotWonMysql
    {
        return AuctionNotWonMysql::new();
    }

    public function createAuctionNotWonLotList(): AuctionNotWon
    {
        return AuctionNotWon::new();
    }

    public function createAuctionBiddingMysqlDataSource(): AuctionBiddingMysql
    {
        return AuctionBiddingMysql::new();
    }

    public function createAuctionBiddingLotList(): AuctionBidding
    {
        return AuctionBidding::new();
    }

    public function createAuctionWatchlistMysqlDataSource(): AuctionWatchlistMysql
    {
        return AuctionWatchlistMysql::new();
    }

    public function createAuctionWatchlistLotList(): AuctionWatchlist
    {
        return AuctionWatchlist::new();
    }

    public function createAuctionConsignedMysqlDataSource(): AuctionConsignedMysql
    {
        return AuctionConsignedMysql::new();
    }

    public function createAuctionConsignedMysqlLotList(): AuctionConsignedMysql
    {
        return AuctionConsignedMysql::new();
    }
}
