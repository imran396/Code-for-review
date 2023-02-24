<?php
/**
 * Static html renderer for output on page load.
 * It will be dynamically updated at client side by JS
 *
 * Related tickets:
 * SAM-3292: Auction/lot starting status adjustments
 *
 * Project        sam
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 10, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\AuctionLot\Sync;

use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Renderer
 * @package Sam\AuctionLot\Sync
 */
class Renderer extends CustomizableClass
{
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    // Time Statuses
    private const TS_UPCOMING = 1;
    private const TS_IN_PROGRESS = 2;
    private const TS_LOT_ENDED = 3;
    private const TS_AUCTION_CLOSED = 4;

    // renderer state properties
    protected ?int $accountId = null;
    /**
     * translated texts
     */
    public string $langWinBid = '';
    public string $langWinHdr = '';
    public string $langViewDetails = '';
    public string $langUnsold = '';
    public string $langUnawarded = '';
    public string $langTimeLeft = '';
    public string $langTimeEnded = '';
    public string $langStartHdr = '';
    public string $langStatus = '';
    public string $langSold = '';
    public string $langSaleInProgress = '';
    public string $langSaleEnded = '';
    public string $langSaleStart = '';
    public string $langReserveMet = '';
    public string $langReserveNotMet = '';
    public string $langOutbid = '';
    public string $langNextBidText = '';
    public string $langNotApplicable = '';
    public string $langMaxBid = '';
    public string $langLotStart = '';
    public string $langLotEnded = '';
    public string $langLoading = '';
    public string $langCurrentAbsentee = '';
    public string $langBiddingPaused = '';
    public string $langCurHdr = '';
    public string $langClosedHdr = '';
    public string $langBulkMasterBeats = '';
    public string $langBids = '';
    public string $langBid = '';
    public string $langAwarded = '';
    public string $langAuctionEnded = '';
    public string $langAskHdr = '';
    /**
     * templates
     */
    public string $countdownLinkTpl = '<a href="%s" class="%s">%s</a>';
    public string $liveSaleStartTpl = '<span>%s:</span>&nbsp;%s';
    public string $timedSaleStartTpl = '<span>%s:</span>&nbsp;%s';
    public string $timedSaleInProgressTpl;  // see initTemplates()
    public string $classUpcoming = 'upcoming';
    public string $classInProgress = 'in-progress';
    public string $classEnded = 'ended';
    public string $classPaused = 'paused';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * init some method for setting default data
     * @return static
     */
    public function init(): static
    {
        $this->initTranslations();
        $this->initTemplates();
        return $this;
    }

    /**
     * Detect time status for lot of hybrid auction
     * @param int $auctionStatus
     * @param int $lotStatus
     * @return int|null
     */
    public function getTimeStatusForHybrid(
        int $auctionStatus,
        int $lotStatus
    ): ?int {
        $timeStatus = null;
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if ($auctionStatusPureChecker->isClosed($auctionStatus)) {
            $timeStatus = self::TS_AUCTION_CLOSED;
        } elseif ($auctionLotStatusPureChecker->isActive($lotStatus)) {
            if ($auctionStatusPureChecker->isActive($auctionStatus)) {
                $timeStatus = self::TS_UPCOMING;
            } elseif ($auctionStatusPureChecker->isStartedOrPaused($auctionStatus)) {
                $timeStatus = self::TS_IN_PROGRESS;
            }
        } else {
            $timeStatus = self::TS_LOT_ENDED;
        }
        return $timeStatus;
    }

    /**
     * Return initial mock for time status block.
     * It should be updated by JS right after page load.
     * We don't render countdown there.
     *
     * @param int|null $auctionId ali.auction_id
     * @param int|null $lotItemId ali.lot_item_id
     * @param int|null $lotStatus ali.lot_status_id
     * @param int|null $auctionStatus a.auction_status_id
     * @param string $seoUrl alic.seo_url
     * @return string
     */
    public function getTimeStatusOutputForHybrid(
        ?int $auctionId,
        ?int $lotItemId,
        ?int $auctionStatus,
        ?int $lotStatus,
        string $seoUrl
    ): string {
        $auctionStatus = (int)$auctionStatus;
        $lotStatus = (int)$lotStatus;
        $lotDetailsUrl = $this->getLotDetailsUrl($lotItemId, $auctionId, $seoUrl);
        $liveSaleUrl = $this->getLiveSaleUrl($auctionId);
        $timeStatus = $this->getTimeStatusForHybrid($auctionStatus, $lotStatus);
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $output = '';
        if ($timeStatus === self::TS_AUCTION_CLOSED) {
            $output = sprintf(
                $this->countdownLinkTpl,
                $lotDetailsUrl,
                $this->classEnded,
                $this->langSaleEnded
            );
        } elseif ($timeStatus === self::TS_UPCOMING) {
            // countdown mock not implemented, render empty string
        } elseif ($timeStatus === self::TS_IN_PROGRESS) {
            // countdown mock not implemented, render empty string
            if ($auctionStatusPureChecker->isPaused($auctionStatus)) {
                $output .= sprintf(
                    $this->countdownLinkTpl,
                    $liveSaleUrl,
                    $this->classInProgress,
                    $this->langBiddingPaused
                );
            }
        } elseif ($timeStatus === self::TS_LOT_ENDED) {
            $url = $auctionStatusPureChecker->isStartedOrPaused($auctionStatus)
                ? $liveSaleUrl : $lotDetailsUrl;
            $output = sprintf(
                $this->countdownLinkTpl,
                $url,
                $this->classEnded,
                $this->langLotEnded
            );
        }
        return $output;
    }

    /**
     * Time status output for un-assigned to any auction lot (See Consigned Items page)
     * @return string
     */
    public function getTimeStatusOutputForUnassigned(): string
    {
        return '';
    }

    /**
     * Initialize Translations
     */
    protected function initTranslations(): void
    {
        $accountId = $this->getAccountId();
        $tr = $this->getTranslator();
        $this->langAskHdr = $tr->translate('CATALOG_TABLE_PRICE_ASKINGBID', 'catalog', $accountId);
        $this->langAuctionEnded = $tr->translate('ITEM_SALEENDED', 'item', $accountId);
        $this->langAwarded = $tr->translate('CATALOG_AWARDED', 'catalog', $accountId);
        $this->langBid = $tr->translate('GENERAL_BID', 'general', $accountId);
        $this->langBiddingPaused = $tr->translate('GENERAL_BIDDING_PAUSED', 'general', $accountId);
        $this->langBids = $tr->translate('GENERAL_BIDS', 'general', $accountId);
        $this->langBulkMasterBeats = $tr->translate('CATALOG_BULKMASTER_BEATSGROUP', 'catalog', $accountId);
        $this->langClosedHdr = $tr->translate('GENERAL_CLOSED', 'general', $accountId);
        $this->langCurHdr = $tr->translate('CATALOG_TABLE_PRICE_CURRENTBID', 'catalog', $accountId);
        $this->langCurrentAbsentee = $tr->translate('CATALOG_CURRENT_ABSENTEE', 'catalog', $accountId);
        $this->langLoading = $tr->translate('CATALOG_LOADING', 'catalog', $accountId);
        $this->langLotStart = $tr->translate('ITEM_LOTSTART', 'item', $accountId);
        $this->langLotEnded = $tr->translate('ITEM_LOTENDED', 'item', $accountId);
        $this->langMaxBid = $tr->translate('ITEM_MAXBID_HEADER', 'item', $accountId);
        $this->langNotApplicable = $tr->translate('GENERAL_NOT_APPLICABLE', 'general', $accountId);
        $this->langNextBidText = $tr->translate('CATALOG_NEXTBID_BUTTON_TEXT', 'catalog', $accountId);
        $this->langOutbid = $tr->translate('GENERAL_OUTBID', 'general', $accountId);
        $this->langReserveNotMet = $tr->translate('CATALOG_RESERVENOTMET', 'catalog', $accountId);
        $this->langReserveMet = $tr->translate('CATALOG_RESERVEMET', 'catalog', $accountId);
        $this->langSaleStart = $tr->translate('ITEM_SALESTART', 'item', $accountId);
        $this->langSaleEnded = $tr->translate('ITEM_SALEENDED', 'item', $accountId);
        $this->langSaleInProgress = $tr->translate('ITEM_SALEINPROGRESS', 'item', $accountId);
        $this->langSold = $tr->translate('CATALOG_SOLD', 'catalog', $accountId);
        $this->langStartHdr = $tr->translate('CATALOG_TABLE_PRICE_STARTINGBID', 'catalog', $accountId);
        $this->langStatus = $tr->translate('CATALOG_STATUS', 'catalog', $accountId);
        $this->langTimeEnded = $tr->translate('CATALOG_TIMELEFT_ENDED', 'catalog', $accountId);
        $this->langTimeLeft = $tr->translate('CATALOG_TIMELEFT', 'catalog', $accountId);
        $this->langUnawarded = $tr->translate('CATALOG_UNAWARDED', 'catalog', $accountId);
        $this->langUnsold = $tr->translate('CATALOG_UNSOLD', 'catalog', $accountId);
        $this->langViewDetails = $tr->translate('CATALOG_VIEWDETAILS', 'catalog', $accountId);
        $this->langWinHdr = $tr->translate('CATALOG_TABLE_PRICE_WINNINGBID', 'catalog', $accountId);
        $this->langWinBid = $tr->translate('ITEM_WINNINGBID', 'item', $accountId);
    }

    /**
     * Init string templates
     */
    protected function initTemplates(): void
    {
        $this->liveSaleStartTpl = sprintf($this->liveSaleStartTpl, $this->langSaleStart, '%s');
        $this->timedSaleStartTpl = sprintf($this->timedSaleStartTpl, $this->langSaleStart, '%s');
        $this->timedSaleInProgressTpl = $this->langTimeLeft . ':&nbsp;%s';
    }

    /**
     * @param int $accountId
     * @return static
     */
    public function setAccountId(int $accountId): static
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return int system account id
     */
    public function getAccountId(): int
    {
        if ($this->accountId === null) {
            $this->accountId = $this->getSystemAccountId();
        }
        return $this->accountId;
    }

    /**
     * Return url to lot details page.
     * You can pass placeholder '%s'
     *
     * @param int|null $lotItemId
     * @param int|null $auctionId You can pass "0" for unassigned to auction lot
     * @param string $seoUrl
     * @return string
     */
    public function getLotDetailsUrl(?int $lotItemId, ?int $auctionId, string $seoUrl): string
    {
        $url = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forWeb($lotItemId, $auctionId, $seoUrl)
        );
        return $url;
    }

    /**
     * Return url to live sale page
     * You can pass placeholder '%s'
     *
     * @param int|null $auctionId
     * @return string
     */
    public function getLiveSaleUrl(?int $auctionId): string
    {
        $url = $this->getUrlBuilder()->build(
            ResponsiveLiveSaleUrlConfig::new()->forRedirect($auctionId)
        );
        return $url;
    }
}
