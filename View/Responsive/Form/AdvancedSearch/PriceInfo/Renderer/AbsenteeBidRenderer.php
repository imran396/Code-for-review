<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAbsenteeBidsUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Bidding\Qform\MobileCell;
use Sam\Bidding\Qform\WebCell;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\CurrencyCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\HtmlWrapRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;

/**
 * Class AbsenteeBidRenderer
 */
class AbsenteeBidRenderer extends CustomizableClass
{
    use CachedTranslatorAwareTrait;
    use CurrencyCacherAwareTrait;
    use HtmlWrapRendererCreateTrait;
    use NumberFormatterAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        CachedTranslator $cachedTranslator,
        NumberFormatterInterface $numberFormatter
    ): static {
        $this->setCachedTranslator($cachedTranslator);
        $this->setNumberFormatter($numberFormatter);
        return $this;
    }

    /**
     * Render html for absentee bids block
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @return string
     */
    public function render(AdvancedSearchLotDto $dto, array $auctionAccess): string
    {
        $auctionId = $dto->auctionId;
        $lotItemId = $dto->lotItemId;
        $auctionLotId = $dto->auctionLotId;
        $absenteeBidDisplay = $dto->absenteeBidsDisplay;
        $bidCount = $dto->bidCount;
        $currentBid = $dto->currentBid;

        $isAccessLotBiddingInfo = $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_INFO][$auctionId] ?? false;
        if (!$isAccessLotBiddingInfo) {
            return '';
        }

        if (!$auctionLotId) {
            log_error(
                'Auction lot id undefined, when rendering absentee bid block'
                . composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
            );
            return '';
        }

        if (!$this->isAbsenteeInfo($dto, $auctionAccess)) {
            return '';
        }
        $bidTranslatableKey = $bidCount > 1 ? 'GENERAL_BIDS' : 'GENERAL_BID';
        $bids = $bidCount . ' ' . $this->getCachedTranslator()->translate($bidTranslatableKey, 'general');
        $output = $bids;
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();

        /** Live absentee bids >> Display number of bids and
         * bid amounts / bidder numbers is selected **/
        if ($auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsNumberOfAbsenteeLink($absenteeBidDisplay)) {
            $isAccessLotBiddingHistory = $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_HISTORY][$auctionId] ?? false;
            if ($isAccessLotBiddingHistory) {
                $url = $this->getUrlBuilder()->build(
                    ResponsiveAbsenteeBidsUrlConfig::new()->forWeb($lotItemId, $auctionId)
                );
                $output = "<a href=\"{$url}\">{$output}</a>";
            }
        } elseif (
            $auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsNumberOfAbsenteeHigh($absenteeBidDisplay)
            && Floating::gt($currentBid, 0.)
        ) {
            $currencyDecoratedTemplate = MobileCell::getCurrencyDecoratedTemplate();
            $currencyPrefix = MobileCell::getControlIdPrefix(WebCell::CONTROL_CURRENCY);
            $currencySign = $this->getCurrencyCacher()->getDefaultCurrencySign($auctionId);
            $currencyDecorated = sprintf(
                $currencyDecoratedTemplate,
                $currencyPrefix . $auctionLotId,
                $currencySign
            );
            $absenteeBidFormatted = $this->getNumberFormatter()->formatMoney($currentBid);
            $bidCountDecoratedTemplate = MobileCell::getBidCountDecoratedTemplate();
            $bidCountDecorated = sprintf($bidCountDecoratedTemplate, $bids);
            $output = $currencyDecorated . $absenteeBidFormatted . ' ' . $bidCountDecorated;
        }
        $output = $this->createHtmlWrapRenderer()->withSpan(
            $output,
            ['class' => 'absentee', 'id' => 'blkAbs' . $auctionLotId]
        );

        return $output;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @return bool
     */
    protected function isAbsenteeInfo(AdvancedSearchLotDto $dto, array $auctionAccess): bool
    {
        $auctionId = $dto->auctionId;
        $isAccessLotBiddingInfo = $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_INFO][$auctionId] ?? false;
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        $isAbsenteeInfo =
            //$row['auction_type'] === Constants\Auction::LIVE &&
            $isAccessLotBiddingInfo
            && in_array($dto->absenteeBidsDisplay, Constants\SettingAuction::DISPLAY_DETAILED_BIDS_INFO_MODES, true)
            && $dto->bidCount > 0
            && $auctionLotStatusPureChecker->isActive($dto->lotStatusId);
        return $isAbsenteeInfo;
    }
}
