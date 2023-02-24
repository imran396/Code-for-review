<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidBook\Html;

use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Auction\BidBook\Html\Internal\Load\AuctionBidBookReportDataLoaderCreateTrait;
use Sam\Report\Auction\BidBook\Html\Internal\Load\LotData;
use Sam\Report\Auction\BidBook\Html\Internal\Render\BidBookReportRendererCreateTrait;

/**
 * Class AuctionBidBookHtmlReporter
 * @package Sam\Report\Auction\BidBook\Html
 */
class AuctionBidBookHtmlReporter extends CustomizableClass
{
    use AuctionBidBookReportDataLoaderCreateTrait;
    use BidBookReportRendererCreateTrait;

    public const DISPLAY_FORMAT_TABLE = 'table';
    public const DISPLAY_FORMAT_LIST = 'list';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function makeReport(int $auctionId, int $startLotOrder, int $endLotOrder, string $displayFormat, bool $includeImage): string
    {
        $auction = $this->createAuctionBidBookReportDataLoader()->loadAuction($auctionId);
        if (!$auction) {
            throw CouldNotFindAuction::withId($auctionId);
        }

        $currencySign = $this->createAuctionBidBookReportDataLoader()->loadAuctionCurrencySign($auctionId);
        $lots = $this->createAuctionBidBookReportDataLoader()->loadLots($startLotOrder, $endLotOrder, $auctionId, true);
        $content = $displayFormat === self::DISPLAY_FORMAT_TABLE
            ? $this->makeTableContent($lots, $auction->AccountId, $currencySign, $includeImage)
            : $this->makeListContent($lots, $auction->AccountId, $currencySign, $auction->TestAuction, $includeImage);

        $output = sprintf(
            '<div class="%s">%s%s</div>',
            $displayFormat === self::DISPLAY_FORMAT_TABLE ? 'tabular' : '',
            $this->createBidBookReportRenderer()->construct($auction->AccountId)->makeHeader($auction),
            $content
        );
        return $output;
    }

    /**
     * @param LotData[] $lots
     * @param string $currencySign
     * @param bool $includeImage
     * @return string
     */
    protected function makeTableContent(array $lots, int $accountId, string $currencySign, bool $includeImage): string
    {
        $auctionBidBookReportDataLoader = $this->createAuctionBidBookReportDataLoader();
        $bidBookReportRenderer = $this->createBidBookReportRenderer()->construct($accountId);
        $rows = [];
        foreach ($lots as $lotData) {
            $highBids = $auctionBidBookReportDataLoader->loadHighBids($lotData->lotItemId, $lotData->auctionId, true);
            $rows[] = $bidBookReportRenderer->makeTableRow($lotData, $highBids, $currencySign, $includeImage);
        }
        return $bidBookReportRenderer->makeTable($rows);
    }

    protected function makeListContent(array $lots, int $accountId, string $currencySign, bool $isTestAuction, bool $includeImage): string
    {
        $auctionBidBookReportDataLoader = $this->createAuctionBidBookReportDataLoader();
        $bidBookReportRenderer = $this->createBidBookReportRenderer()->construct($accountId);
        $items = [];
        foreach ($lots as $lotData) {
            $highBids = $auctionBidBookReportDataLoader->loadHighBids($lotData->lotItemId, $lotData->auctionId, true);
            $items[] = $bidBookReportRenderer->makeListItem($lotData, $highBids, $currencySign, $isTestAuction, $includeImage);
        }
        return implode('', $items);
    }
}
