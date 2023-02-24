<?php
/**
 * SAM-5466: Advanced search panel auction auto-complete configuration
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build;

use \Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build\AuctionAutocompleteDataBuildingInput as Input;
use Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionAutocompleteDataBuilder
 * @package Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build
 */
class AuctionAutocompleteDataBuilder extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function build(Input $input): array
    {
        [$dataSource, $lotList] = $this->buildDataSourceAndLotList($input);
        if (
            !$dataSource
            || !$lotList
        ) {
            return [];
        }

        $dataSource->setUserId($input->editorUserId);
        if ($input->auctionId > 0) {
            $dataSource->filterAuctionIds([$input->auctionId]);
        }

        if (!empty($input->searchKey)) {
            $dataSource->likeAuctionName($input->searchKey);
        }

        if ($input->auctionTypes) {
            $dataSource->filterAuctionTypes($input->auctionTypes);
        }

        $dataSource->filterAuctionAccessCheck([Constants\Auction::ACCESS_RESTYPE_AUCTION_VISIBILITY]);
        $dataSource->setSystemAccountId($input->systemAccountId);

        if ($input->filterAccountId) {
            $dataSource->filterAccountIds([$input->filterAccountId]);
        }

        $dataSource->setLimit($input->limit);
        $dataSource->orderBy('auction_date, a.name');
        $resultSetFields = [
            'account_id',
            'auction_date',
            'auction_id',
            'auction_name',
            'auction_status_id',
            'auction_type',
            'sale_num',
            'sale_num_ext',
        ];
        $dataSource->addResultSetFields($resultSetFields);
        $lotList->setDataSource($dataSource);
        $rows = $lotList->getLotListArray();
        return $rows;
    }

    protected function buildDataSourceAndLotList(Input $input): array
    {
        $dataProvider = $this->createDataProvider();
        if ($dataProvider->isSearchPage($input->pageType)) {
            $dataSource = $dataProvider->createAuctionSearchMysqlDataSource();
            $lotList = $dataProvider->createAuctionSearchLotList();
        } elseif ($dataProvider->isMyItemsAllPage($input->pageType)) {
            $dataSource = $dataProvider->createAuctionAllMysqlDataSource();
            $lotList = $dataProvider->createAuctionAllLotList();
        } elseif ($dataProvider->isMyItemsWonPage($input->pageType)) {
            $dataSource = $dataProvider->createAuctionWonMysqlDataSource();
            $lotList = $dataProvider->createAuctionWonLotList();
        } elseif ($dataProvider->isMyItemsNotWonPage($input->pageType)) {
            $dataSource = $dataProvider->createAuctionNotWonMysqlDataSource();
            $lotList = $dataProvider->createAuctionNotWonLotList();
        } elseif ($dataProvider->isMyItemsBiddingPage($input->pageType)) {
            $dataSource = $dataProvider->createAuctionBiddingMysqlDataSource();
            $lotList = $dataProvider->createAuctionBiddingLotList();
        } elseif ($dataProvider->isAuctionWatchlistPage($input->pageType)) {
            $dataSource = $dataProvider->createAuctionWatchlistMysqlDataSource();
            $lotList = $dataProvider->createAuctionWatchlistLotList();
        } elseif ($dataProvider->isAuctionConsignedPage($input->pageType)) {
            $dataSource = $dataProvider->createAuctionConsignedMysqlDataSource();
            $lotList = $dataProvider->createAuctionConsignedMysqlLotList();
        } else {
            $dataSource = null;
            $lotList = null;
        }
        return [$dataSource, $lotList];
    }
}
