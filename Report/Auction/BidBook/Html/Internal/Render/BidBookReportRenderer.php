<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidBook\Html\Internal\Render;

use Auction;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Bidding\StartingBid\FlexibleStartingBidCalculatorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Image\ImageHelperCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Auction\BidBook\Html\Internal\Load\BidData;
use Sam\Report\Auction\BidBook\Html\Internal\Load\LotData;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BidBookReportRenderer
 * @package Sam\Report\Auction\BidBook\Html\Internal\Render
 */
class BidBookReportRenderer extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use FlexibleStartingBidCalculatorCreateTrait;
    use ImageHelperCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;

    protected bool $showHighEstimate;
    protected bool $showLowEstimate;
    protected int $accountId;
    protected string $lotDetailThumbSize;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $accountId): static
    {
        $this->showHighEstimate = (bool)$this->getSettingsManager()->get(Constants\Setting::SHOW_HIGH_EST, $accountId);
        $this->showLowEstimate = (bool)$this->getSettingsManager()->get(Constants\Setting::SHOW_LOW_EST, $accountId);
        $this->lotDetailThumbSize = $this->createImageHelper()->detectSizeFromMapping(
            $this->cfg()->get('core->image->mapping->lotDetailThumb')
        );
        $this->accountId = $accountId;
        $this->getNumberFormatter()->construct($accountId);
        return $this;
    }

    public function makeHeader(Auction $auction): string
    {
        $auctionName = $this->getAuctionRenderer()->renderName($auction);
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $currentDate = $this->getCurrentDateUtc()->format('F j, Y');
        $header = <<<HTML
<table class="bid-book-wrap">
    <tr>
        <td colspan="3" class="sale-name">{$auctionName}</td>
    </tr>
    <tr>
        <td class="bid-book-header">Bid Book</td>
        <td class="sale-report">
            Sale No: <span class="sale-no">{$saleNo}</span> <br />
            Report Date: {$currentDate} <br />
        </td>
        <td class="empty-filler">&nbsp;</td>
    </tr>
</table>
HTML;

        return $header;
    }

    public function makeTable(array $rows): string
    {
        $tableTemplate = static fn(string $rows) => <<<HTML
<div class="lot-row lot-row-nowrap">
    <table class="lot-info">
        <thead class="head_rows">
            <td>Lot</td>
            <td>Details</td>
            <td>Absentee</td>
            <td>Reserve/Cost</td>
        </thead>
    {$rows}
    </table>
</div>
HTML;

        $output = '';
        $chunks = array_chunk($rows, 10);
        foreach ($chunks as $chunk) {
            $output .= $tableTemplate(implode('', $chunk));
        }
        return $output;
    }

    /**
     * @param LotData $lotData
     * @param BidData[] $highBids
     * @param string $currencySign
     * @param bool $includeImage
     * @return string
     */
    public function makeTableRow(
        LotData $lotData,
        array $highBids,
        string $currencySign,
        bool $includeImage
    ): string {
        $tableRow = <<<HTML
<tr class="rows">
    <td style="width:5%;" class="lot_number"><span class="lot_number_int">{$this->makeLotNo($lotData)}</span></td>
    <td style="width:35%;" class="lot_details">
        <span class="lot_details_img">{$this->makeImage($lotData, $includeImage)}</span>
        <span class="lot_details_info">{$this->makeLotDescription($lotData)}</span>
    </td>
    <td style="width:15%;" class="absentee"> 
        {$this->makeFirstMaxBid($highBids, $currencySign)} {$this->makeFirstHighBidder($highBids, false)}
        <br />
        {$this->makeSecondMaxBid($highBids, $currencySign)} {$this->makeSecondHighBidder($highBids, false)}
    </td>
    <td style="width:15%;" class="estimates">{$this->makeEstimate($lotData, $currencySign)}</td>
</tr>
HTML;
        return $tableRow;
    }

    public function makeListItem(
        LotData $lotData,
        array $highBids,
        string $currencySign,
        bool $isTestAuction,
        bool $includeImage
    ): string {
        $listItem = <<<HTML
<div class="lot-row-nowrap">
    <table class="lot-info">
        <tr class="r1">
            <td style="width:5%;" class="lot-label">{$this->makeImage($lotData, $includeImage)}</td>
            <td style="width:5%;" class="lot-label">Lot</td>
            <td style="width:10%;" class="lot-value">&nbsp;{$this->makeLotNo($lotData)}</td>
            <td style="width:6%;" class="opening-label">Starting/Recom.</td>
            <td style="width:14%;" class="opening-value">&nbsp;{$this->makeStartingBid($lotData, $currencySign)}</td>
            <td style="width:6%;" class="reserve-label">Reserve</td>
            <td style="width:14%;" class="reserve-value">&nbsp;{$this->formatMoney($lotData->reservePrice, $currencySign)}</td>
            <td style="width:45%;" colspan="2" class="lot-name">{$this->makeLotName($lotData, $isTestAuction)}</td>
        </tr>
        <tr class="r2">
            <td style="width:5%;" class="lot-label"></td>         
            <td style="width:5%;" class="r2c1">&nbsp;</td>
            <td style="width:30%;" colspan="3" class="lot-status">&nbsp;{$this->makeStatus($lotData)}</td>
            <td style="width:6%;" class="estimate-label">Estimate</td>
            <td style="width:14%;" class="estimate-value">&nbsp;{$this->makeEstimate($lotData, $currencySign)}</td>
            <td style="width:20%;" class="consignor-label">Consignor</td>
            <td style="width:25%;" class="consignor-value">&nbsp;{$this->makeConsignorInfo($lotData)}</td>
        </tr>
        <tr class="r3">
            <td class="absentee-maxbid" colspan="5">
                &nbsp;{$this->makeFirstMaxBid($highBids, $currencySign)}<br />{$this->makeSecondMaxBid($highBids, $currencySign)}
            </td>
            <td style="absentee-bidder" colspan="3">
                &nbsp;{$this->makeFirstHighBidder($highBids, true)}<br />{$this->makeSecondHighBidder($highBids, true)}
            </td>
        </tr>
    </table>
</div>
HTML;
        return $listItem;
    }

    protected function makeLotNo(LotData $lotData): string
    {
        return $this->getLotRenderer()->makeLotNo($lotData->lotNum, $lotData->lotNumExt, $lotData->lotNumPrefix);
    }

    protected function makeLotName(LotData $lotData, bool $isTestAuction): string
    {
        $lotName = $this->getLotRenderer()->makeName($lotData->lotName, $isTestAuction);
        return ee($lotName);
    }

    protected function formatMoney(?float $input, string $currencySign): string
    {
        $formatted = $this->getNumberFormatter()->formatMoney($input);
        return $currencySign . $formatted;
    }

    protected function makeConsignorInfo(LotData $lotData): string
    {
        $info = UserPureRenderer::new()->makeFullName($lotData->consignorFirstname, $lotData->consignorLastName);
        if ($lotData->consignorCompanyName) {
            $info .= "({$lotData->consignorCompanyName})";
        }
        return $info;
    }

    protected function makeEstimate(LotData $lotData, string $currencySign): string
    {
        $lowEstimateFormatted = $this->formatMoney($lotData->lowEstimate, $currencySign);
        $highEstimateFormatted = $this->formatMoney($lotData->highEstimate, $currencySign);
        if ($this->showLowEstimate && $this->showHighEstimate) {
            return "{$lowEstimateFormatted} - {$highEstimateFormatted}";
        }
        if ($this->showLowEstimate) {
            return $lowEstimateFormatted;
        }
        if ($this->showHighEstimate) {
            return $highEstimateFormatted;
        }
        return '';
    }

    protected function makeStartingBid(LotData $lotData, string $currencySign): string
    {
        $suggestedStartingBid = $this->createFlexibleStartingBidCalculator()
            ->setAuctionId($lotData->auctionId)
            ->setLotItemId($lotData->lotItemId)
            ->calculate();
        return $this->formatMoney($lotData->startingBid, $currencySign) . '/' . $this->formatMoney($suggestedStartingBid, $currencySign);
    }

    protected function makeLotDescription(LotData $lotData): string
    {
        $lotDescription = trim($lotData->lotDescription);
        $lotDescription = strip_tags($lotDescription);
        $lotDescription = substr($lotDescription, 0, 80);
        return $lotDescription;
    }

    /**
     * @param BidData[] $highBids
     * @param string $currencySign
     * @return string
     */
    protected function makeFirstMaxBid(array $highBids, string $currencySign): string
    {
        if (!count($highBids)) {
            return '';
        }
        $highestBid = reset($highBids);
        return $this->formatMoney($highestBid->maxBid, $currencySign);
    }

    /**
     * @param BidData[] $highBids
     * @param bool $withName
     * @return string
     */
    protected function makeFirstHighBidder(array $highBids, bool $withName): string
    {
        if (!count($highBids)) {
            return '';
        }
        $highestBid = reset($highBids);
        $bidderInfo = '(' . $highestBid->bidderNum . ')';
        if ($withName) {
            $bidderInfo = UserPureRenderer::new()->makeFullName($highestBid->bidderFirstName, $highestBid->bidderLastName) . ' ' . $bidderInfo;
        }
        return $bidderInfo;
    }

    /**
     * @param BidData[] $highBids
     * @param string $currencySign
     * @return string
     */
    protected function makeSecondMaxBid(array $highBids, string $currencySign): string
    {
        if (count($highBids) !== 2) {
            return '';
        }
        $secondHighBid = end($highBids);
        return $this->formatMoney($secondHighBid->maxBid, $currencySign);
    }

    /**
     * @param BidData[] $highBids
     * @param bool $withName
     * @return string
     */
    protected function makeSecondHighBidder(array $highBids, bool $withName): string
    {
        if (count($highBids) !== 2) {
            return '';
        }
        $secondHighBid = end($highBids);
        $bidderInfo = '(' . $secondHighBid->bidderNum . ')';
        if ($withName) {
            $bidderInfo = UserPureRenderer::new()->makeFullName($secondHighBid->bidderFirstName, $secondHighBid->bidderLastName) . ' ' . $bidderInfo;
        }
        return $bidderInfo;
    }

    protected function makeImage(LotData $lotData, bool $includeImage): string
    {
        if (
            !$includeImage
            || !$lotData->imageId
        ) {
            return '';
        }
        $lotImageUrl = $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotData->imageId, $this->lotDetailThumbSize, $this->accountId)
        );
        return '<img src="' . $lotImageUrl . '" class="img-con" style="float:left" />';
    }

    protected function makeStatus(LotData $data): string
    {
        $sellInfoPureChecker = LotSellInfoPureChecker::new();
        $isSold = $sellInfoPureChecker->isHammerPrice($data->hammerPrice)
            && $sellInfoPureChecker->isSaleSoldAuctionLinkedWith($data->auctionIdSold, $data->auctionId);
        return $isSold ? 'Sold' : 'Unsold';
    }
}
