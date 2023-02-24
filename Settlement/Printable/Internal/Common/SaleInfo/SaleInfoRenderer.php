<?php
/**
 * SAM-9581: Sales Date,Sales Number and Name do not shows at Printable View at Frontend and admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-19, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\Common\SaleInfo;

use Auction;
use DateTime;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Settlement\Printable\Internal\Translation\SettlementTranslatorCreateTrait;

/**
 * Class SaleInfoRenderer
 * @package Sam\Settlement\Printable\Internal\Common\SaleInfo
 */
class SaleInfoRenderer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use DateHelperAwareTrait;
    use DateRendererCreateTrait;
    use SettlementTranslatorCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(int $auctionId, bool $isUseTranslatableLabels): string
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            return '';
        }

        $saleName = $this->renderSaleName($auction);
        $saleDate = $this->renderSaleDate($auction);
        $tr = $this->createSettlementTranslator()->getTranslatedCommon($isUseTranslatableLabels);
        $html = <<<HTML
<div class="sale-name">
    <span class="label">{$tr->saleLbl}: </span><span class="value">$saleName</span>
</div>
<div class="sale-date">
    <span class="label">{$tr->saleDateLbl}: </span><span class="value">$saleDate</span>
</div>
HTML;
        return $html;
    }

    /**
     * @param Auction $auction
     * @return string
     */
    private function renderSaleName(Auction $auction): string
    {
        $url = $this->getUrlBuilder()->build(
            ResponsiveAuctionInfoUrlConfig::new()->forWeb(
                $auction->Id,
                null,
                [
                    UrlConfigConstants::OP_ACCOUNT_ID => $auction->AccountId,
                    UrlConfigConstants::OP_AUCTION_INFO_LINK => $auction->AuctionInfoLink
                ]
            )
        );
        $auctionRenderer = $this->getAuctionRenderer();
        $saleNo = $auctionRenderer->renderSaleNo($auction);
        $name = $auctionRenderer->renderName($auction);
        $output = sprintf('<a href="%s" target="_blank">%s(%s)</a>', $url, $name, $saleNo);
        return $output;
    }

    /**
     * @param Auction $auction
     * @return string
     */
    private function renderSaleDate(Auction $auction): string
    {
        $date = $auction->basicDisplayDate();
        $dateTz = $this->getDateHelper()->convertUtcToTzById($date, $auction->TimezoneId);
        $dateHtml = '';
        if (
            $auction->isLiveOrHybrid()
            || $auction->isTimedScheduled()
        ) {
            $dateHtml = $this->renderFormattedDate($dateTz, $auction->AccountId);
        } elseif ($auction->isTimedOngoing()) {
            $dateHtml = Constants\Auction::$eventTypeFullNames[Constants\Auction::ET_ONGOING];
        }

        return sprintf('<span>%s</span>', $dateHtml);
    }

    /**
     * @param DateTime|null $date
     * @param int|null $accountId
     * @return string
     */
    private function renderFormattedDate(?DateTime $date, ?int $accountId): string
    {
        if (!$date) {
            return '';
        }

        $format = $this->createSettlementTranslator()->getTranslatedCommon()->saleDateFormat;
        $accountId = Cast::toInt($accountId, Constants\Type::F_INT_POSITIVE);
        $dateHelper = $this->getDateHelper();
        $dateFormatted = $dateHelper->formattedDate($date, $accountId, null, null, $format);

        if (strrpos($format, "F") === false) {
            return $dateFormatted;
        }

        $month = date("F", $date->getTimestamp());
        $langMonth = $this->createDateRenderer()->monthTranslated((int)$month);
        $dateFormatted = str_replace($month, $langMonth, $dateFormatted);

        return $dateFormatted;
    }
}
