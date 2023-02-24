<?php
/**
 * Extended WebCell for Mobile use
 */

namespace Sam\Bidding\Qform;

use QCallerException;
use Sam\Application\Url\Build\Config\AuctionLot\AnySingleAuctionLotUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAbsenteeBidsUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAuctionLotChangesUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveBiddingHistoryUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;

/**
 * Class MobileCell
 * @package Sam\Bidding\Qform
 */
class MobileCell extends WebCell
{
    use AuctionLoaderAwareTrait;

    protected ?string $viewMode = null;

    /**
     * Return html for regular bidding block on live lot
     * @return string
     */
    protected function getRegularBiddingLiveHtml(): string
    {
        $output = $this->getRegularBidHtml();
        $output .= $this->getBiddingStatusHtml();
        $output .= $this->getAbsenteeNotification();
        $output .= $this->getQuantityXMoneyHtml();
        $output .= $this->getReserveNotMetHtml();
        $output .= $this->getReserveMetHtml();
        //$output .= $this->getAbsenteeHtml();
        $output .= $this->getBuyNowHtml();
        $output .= $this->getViewDetailsHtml();
        //$output .= $this->getBiddingHistoryLink();
        $output .= $this->getWaitIconHtml();
        //        $output .= $this->getPlacedBidHiddenHtml();
        $output .= $this->getHiddenAskingBidHtml();
        return $output;
    }

    /**
     * Return html for ended lot block
     * @return string
     */
    protected function getClosedLotHtml(): string
    {
        $output = '';
        //$output .= $this->getClosedMessageHtml();
        $output .= $this->getBuyNowHtml();
        //        if ($this->isAuctionReadyUser())
        //            $output .= $this->getViewDetailsHtml();
        //$output .= $this->getBiddingHistoryLink();
        $output = $this->decorateWithDiv($output, self::BLOCK_CLOSED_LOT);
        return $output;
    }

    /**
     * Return html for regular bid textbox and place bid button block
     * wrapped with divs required for mobile
     * @return string
     */
    protected function getRegularBidHtml(): string
    {
        $txtRegularBid = $this->createRegularBidTextbox();
        $btnRegularBid = $this->createRegularBidButton();
        $lblRegularBidError = $this->createRegularBidErrorLabel();
        //$action = $this->getCurrencyDecorated()
        $action = '';
        $currencySign = $this->getCurrencySign();
        $textBox = $txtRegularBid->RenderWithError(false);
        if (in_array($this->viewMode, [Constants\Page::VM_GRID, Constants\Page::VM_LIST], true)) {
            $button = $btnRegularBid->Render(false, "CssClass=blu");
            $action = <<<HTML

<section class="left">
    <span class="currency-input">{$currencySign}{$textBox}</span>
</section>
<section class="right">
    <div class="unibtn">
        {$button}
    </div>
</section>

HTML;
        } elseif ($this->viewMode === Constants\Page::VM_COMPACT) {
            $button = $btnRegularBid->Render(false, "CssClass=orng no-left-radius");
            $action = <<<HTML

<div class="max_bid_right">
    <span class="max_bid">{$textBox}</span>
    <span class="max_bid_button">
        {$button}
    </span>
    <div class="max-bid-warning"></div>
</div>

HTML;
        }
        if ($this->viewMode !== Constants\Page::VM_COMPACT) {
            $maxBidWarningContainer = '<div class="max-bid-warning"></div>';
        } else {
            $maxBidWarningContainer = '';
        }
        $action .= '<div class="clearfix"></div>' . $maxBidWarningContainer;
        $error = $lblRegularBidError->Render(false);
        $output = $this->decorateWithDiv($action, self::BLOCK_REGULAR_BID_ACTION)
            . $this->decorateWithDiv($error, self::BLOCK_REGULAR_BID_ERROR);
        $output = $this->decorateWithDiv(
            $output,
            self::BLOCK_REGULAR_BID,
            'blkRegularBid' . $this->auctionLotId
        );
        return $output;
    }

    /**
     * Return html for auction registration block
     * @return string
     */
    protected function getAuctionRegistrationHtml(): string
    {
        $btnRegister = $this->createRegisterButton();
        $output = $btnRegister->Render(false, "CssClass=orng");
        $output = '<div class="unibtn">' . $this->decorateWithDiv($output, self::BLOCK_AUCTION_REGISTRATION) . '</div>';
        $output .= $this->getQuantityXMoneyHtml();
        $output .= $this->getReserveNotMetHtml();
        $output .= $this->getAbsenteeHtml();
        return $output;
    }

    /**
     * Return html for "buy now" block
     * override to point to lot-details-m
     * @return string
     */
    public function getBuyNowHtml(): string
    {
        $output = '';
        if (
            $this->isBuyNowAvailable()
            && in_array($this->viewMode, [Constants\Page::VM_LIST, Constants\Page::VM_GRID], true)
        ) {
            $qty = $this->getQuantityXMoneyOnButton();
            $qty = empty($qty) ? $qty : ' ' . $this->getCurrencySign() . $this->buyNowAmount . $qty;
            $controlId = $this->getControlId(self::CONTROL_BTN_BUY_NOW);
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionLotUrlConfig::new()->forRedirect(
                    Constants\Url::P_AUCTIONS_CONFIRM_BUY,
                    $this->lotItemId,
                    $this->getAuctionId()
                )
            );
            $buyNow = '<input type="button" id="' . $controlId . '" ' .
                'class="button orng" value="' . $this->translate('TABLE_BUYNOW') . $qty . '" ' .
                'onclick="sam.redirect(\'' . $url . '\');" />';
            $output = '<div class="unibtn">' . $this->decorateWithDiv($buyNow, self::BLOCK_BUY_NOW) . '</div>';
        }
        return $output;
    }

    /**
     * Return html for "view details" link
     * override to point to lot-details-m
     * @return string
     */
    protected function getViewDetailsHtml(): string
    {
        $url = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forWeb(
                $this->lotItemId,
                $this->getAuctionId(),
                $this->seoUrl,
                [UrlConfigConstants::OP_ACCOUNT_ID => $this->accountId]
            )
        );
        $viewDetails = '<a href="' . $url . '">' . $this->translate('VIEWDETAILS') . '</a>';
        $output = $this->decorateWithDiv($viewDetails, self::BLOCK_VIEW_DETAILS);
        return $output;
    }

    /**
     * Return html for "bidding history" link block
     * extended to use mobile links
     * @return string
     */
    protected function getBiddingHistoryLink(): string
    {
        $output = '';
        $auctionId = $this->getAuctionIdForBiddingHistoryLink();
        if ($auctionId) {
            $link = $this->getUrlBuilder()->build(
                ResponsiveBiddingHistoryUrlConfig::new()->forWeb($this->lotItemId, $auctionId)
            );
            $output = '<a href="' . $link . '">' . $this->translate('BIDDINGHISTORY') . '</a>';
            $output = $this->decorateWithSpan($output, self::BLOCK_BID_HISTORY);
        }
        return $output;
    }

    /**
     * Render html for absentee bids block
     * @return string
     */
    protected function getAbsenteeHtml(): string
    {
        //do not output absentee html if not permitted for this auction
        $auction = $this->getAuctionLoader()->load((int)$this->getAuctionId());
        if (!$auction) {
            log_error(
                "Available auction not found, when rendering absentee bid html"
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return '';
        }
        $isShowBidHistory = $this->createAuctionAccessChecker()->hasPermission(Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_HISTORY, $auction);
        if (!$isShowBidHistory) {
            return '';
        }

        $output = '';
        if ($this->isAbsenteeInfo()) {
            $bids = $this->bidCount . ' ';
            $bids .= $this->bidCount > 1 ? $this->translate('BIDS') : $this->translate('BID');
            $output = $bids;
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();

            /** Live absentee bids >> Display number of bids and
             * bid amounts / bidder numbers is selected **/
            if ($auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsNumberOfAbsenteeLink($this->absenteeBidsDisplay)) {
                $url = $this->getUrlBuilder()->build(
                    ResponsiveAbsenteeBidsUrlConfig::new()->forWeb($this->lotItemId, $this->getAuctionId())
                );
                $output = '<a href="' . $url . '">' . $output . '</a>';
            } elseif ($auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsNumberOfAbsenteeHigh($this->absenteeBidsDisplay)) {
                if ($this->currentBidAmount > 0) {
                    $output =
                        $this->translate('CURRENT_ABSENTEE') . ': ' .
                        $this->getCurrencyDecorated() . $this->getNumberFormatter()->formatMoney($this->currentBidAmount)
                        . ' ' . sprintf($this->getBidCountDecoratedTemplate(), $bids);
                }
            }
        }
        $output = $this->decorateWithSpan(
            $output,
            self::BLOCK_ABSENTEE,
            $this->getControlId(self::CONTROL_BLK_ABSENTEE)
        );
        return $output;
    }

    /**
     * Return html for special terms and conditions block
     * @return string
     */
    protected function getSpecialTermsHtml(): string
    {
        $output = '';
        $url = $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forRedirect(
                Constants\Url::P_REGISTER_SPECIAL_TERMS_AND_CONDITIONS,
                $this->lotItemId,
                $this->getAuctionId()
            )
        );
        $activeByDatesRange = $this->getAuctionStatusChecker()->detectIfRegistrationActiveByDatesRange(
            $this->getAuction()->StartRegisterDate,
            $this->getAuction()->EndRegisterDate
        );

        $controlId = $this->getControlId(self::CONTROL_BTN_SPECIAL_TERMS);
        $button = '<div class="unibtn"><input type="button" id="' . $controlId . '" ' .
            'class="button orng" value="' . $this->translate('SPEC_TERMS') . '" ' .
            'onclick="sam.redirect(\'' . $url . '\');" ' . ($activeByDatesRange ? '' : 'disabled') . '/></div>';
        $output .= $this->decorateWithDiv($button, self::BLOCK_SPECIAL_TERMS);
        return $output;
    }

    /**
     * Return html for lot changes
     * @return string
     */
    protected function getLotChangesHtml(): string
    {
        $output = '';
        $url = $this->getUrlBuilder()->build(
            ResponsiveAuctionLotChangesUrlConfig::new()
                ->forRedirect([$this->lotItemId], $this->getAuctionId())
        );
        $controlId = $this->getControlId(self::CONTROL_BTN_LOT_CHANGES);
        $button = '<input type="button" id="' . $controlId . '" ' .
            'class="orng button-lot-changes" value="' . $this->translate('LOT_CHANGES') . '" ' .
            'onclick="sam.redirect(\'' . $url . '\');" />';
        $output .= $this->decorateWithDiv($button, self::BLOCK_LOT_CHANGES);
        return $output;
    }

    /**
     * Return html for restricted buyer group block
     * @return string
     */
    protected function getRestrictedBuyerGroupHtml(): string
    {
        $output = '';
        $controlId = $this->getControlId(self::CONTROL_BTN_RESTRICTED_GROUP);
        $button =
            '<div class="unibtn"><input type="button" id="' . $controlId . '" ' .
            'class="button grey" value="' . $this->translate('RESTRICTED_GROUP') . '" disabled /></div>';
        $output .= $this->decorateWithDiv($button, self::BLOCK_RESTRICTED_BUYERS_GROUP);
        $output .= $this->getViewDetailsHtml();
        return $output;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        try {
            if ($name === 'ViewMode') {
                return $this->viewMode = (string)$value;
            }
            return parent::__set($name, $value);
        } catch (QCallerException $e) {
            $e->IncrementOffset();
            return $e;
        }
    }
}
