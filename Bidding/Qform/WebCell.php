<?php
/**
 * Web component for bidding cell rendering used in lot datagrid (catalog, my items)
 * SAM-1920: Catalog and my items pages merge max bid and place bid column
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           26 Feb, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property string AbsenteeBidsDisplay    Live only
 * @property string AbsenteeNotification   Live only
 * @property int AccountId
 * @property bool AllowForceBid
 * @property bool Approved
 * @property float AskingBid
 * @property AuctionBidder AuctionBidder   If not set, find by AuctionId & UserId
 * @property bool AuctionClosed
 * @property int AuctionId
 * @property bool AuctionListingOnly
 * @property AuctionLotItemCache AuctionLotCache
 * @property int AuctionLotId
 * @property bool AuctionReverse
 * @property int AuctionStatusId
 * @property string AuctionType            Constants\Auction::LIVE/Timed
 * @property int BidCount              Live only
 * @property bool BiddingPaused
 * @property float BuyNowAmount
 * @property string BuyNowRestriction
 * @property bool BuyNowUnsold
 * @property bool ConditionalSales
 * @property bool ConfirmTimedBid
 * @property string CurrencySign           Optional (will be found by auction)
 * @property float CurrentBid
 * @property int CurrentBidderId
 * @property float CurrentMaxBid
 * @property float CurrentTransactionBid
 * @property int EventType
 * @property string ForceBidMethodName     "Force bid" placement action handler
 * @property float HammerPrice
 * @property bool InlineBidConfirm
 * @property int InvoiceId
 * @property bool IsBidder
 * @property bool KeepRegularBid        We need to keep amount in regular bid textbox in case of failed bid
 * @property bool ListingOnly
 * @property bool LotBiddingInfoAllowed
 * @property string LotChanges
 * @property int LotId
 * @property int LotNum
 * @property int LotStatusId
 * @property float MyMaxBid
 * @property bool NextBidEnabled
 * @property string NextBidMethodName      "Next bid" placement action handler
 * @property bool NoBidding
 * @property int Quantity
 * @property bool QuantityXMoney
 * @property bool Registered
 * @property string RegularBidMethodName   Regular bid placement action handler
 * @property bool RequireLotChangeConfirmation
 * @property bool ReserveNotMetNotice
 * @property bool ReserveMetNotice
 * @property float ReservePrice
 * @property int RtbCurrentLotId       Live only
 * @property int SettlementId
 * @property DateTime StartDate
 * @property float|null StartingBid             Normalized starting bid
 * @property int DateAssignmentStrategy
 * @property bool UserEmailVerified
 * @property int UserFlag
 * @property int UserId
 * @property bool UsNumberFormatting
 * @property bool VerifyEmail
 * @property string VerifyMethodName
 * @property int WinningAuctionId
 * @property bool WinningBidView
 *
 * TODOs
 * 1) Extract, unify and encapsulate bidding action handler methods and their validations in separate class.
 * We need to check their needs in case of passed ActionParameter and make its format unified for "catalog" and "my items"
 * Possibly, we need always to pass parameter <auction.id>|<auction_lot_item.id> or <auction.id>|<lot_item.id>
 * or only <auction_lot_item.id> for both "catalog" and "my pages" (seems it would be enough)
 * This need more research of bidding action handlers and check custom branches for possible usage.
 * 2) Extract, unify and encapsulate js/php logic for auto-synchronization, which is responsible for bidding cell data.
 * 2.1) Use self::getControlId(), self::getControlIdPrefix() outside where it is possible, instead of hardcoded values.
 * See example in my_item_base.php for BLOCK_REGULAR_BID.
 * 4) Possibly we could get rid of loading Auction there (it is taken from memory, so not important)
 * 5) Assign unique ids to currency SPANs or don't use id, but class selectors for DOM manipulations.
 * 6) Get rid of loading AuctionLotItemCache, but pass needed values through properties.
 * 10) See other TODOs
 */

namespace Sam\Bidding\Qform;

use AuctionBidder;
use AuctionLotItemCache;
use DateTime;
use QAjaxAction;
use QAlertAction;
use QButton;
use QCallerException;
use QClickEvent;
use Qform_ComponentFactory;
use QHiddenInput;
use QInvalidCastException;
use QJavaScriptAction;
use QLabel;
use QPanel;
use QServerAction;
use QTextBox;
use QWaitIcon;
use Sam\Application\Access\Auction\AuctionAccessCheckerCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\AuctionLot\AnySingleAuctionLotUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAbsenteeBidsUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAuctionLotChangesUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveBiddingHistoryUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Invoice\ResponsiveInvoiceViewUrlConfig;
use Sam\Application\Url\Build\Config\Settlement\ResponsiveSettlementViewUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\AuctionLot\Agreement\ChangesAgreement;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderTerms\BidderTermsAgreementManagerAwareTrait;
use Sam\Bidder\Outstanding\BidderOutstandingHelper;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Bidding\ExcessiveBid\AssumedAskingBidLiveCalculatorAwareTrait;
use Sam\Bidding\ReservePrice\LotReservePriceChecker;
use Sam\Bidding\ReservePrice\LotReservePriceCheckerCreateTrait;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityLiveChecker;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityTimedChecker;
use Sam\Core\Bidding\RegularBid\RegularBidPureChecker;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Quantity\LotQuantityPureCalculator;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureCheckerAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\BuyerGroup\Access\LotBuyerGroupAccessHelperAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactory;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Qform\Button;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Quantity\LotQuantityRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;
use UserInfo;

/**
 * @property string $AbsenteeBidsDisplay
 * @property string $AbsenteeNotification
 * @property int $AccountId
 * @property bool $AllowForceBid
 * @property bool $Approved
 * @property float $AskingBid
 * @property AuctionBidder $AuctionBidder
 * @property bool $AuctionClosed
 * @property int $AuctionId
 * @property bool $AuctionListingOnly
 * @property int $AuctionLotId
 * @property bool $AuctionReverse
 * @property int $AuctionStatusId
 * @property string $AuctionType
 * @property int $BidCount
 * @property bool $BiddingPaused
 * @property bool $BidderTermsAreAgreed
 * @property float $BuyNowAmount
 * @property string $BuyNowRestriction
 * @property bool $BuyNowUnsold
 * @property bool $ConditionalSales
 * @property bool $ConfirmTimedBid
 * @property string $CurrencySign
 * @property float $CurrentBid
 * @property int $CurrentBidderId
 * @property float $CurrentMaxBid
 * @property float $CurrentTransactionBid
 * @property DateTime $EndDate
 * @property int $EventType
 * @property string $ForceBidMethodName
 * @property float $HammerPrice
 * @property bool $InlineBidConfirm
 * @property int|null $InvoiceId
 * @property bool $IsBidder
 * @property bool $KeepRegularBid
 * @property bool $ListingOnly
 * @property bool $LotBiddingInfoAllowed
 * @property bool $LotBiddingHistoryAllowed
 * @property string $LotChanges
 * @property int $LotId
 * @property int $LotNum
 * @property int $LotStatusId
 * @property float $MyMaxBid
 * @property bool $NextBidEnabled
 * @property string $NextBidMethodName
 * @property bool $NoBidding
 * @property string $Page
 * @property float $Quantity
 * @property int $QuantityScale
 * @property bool $QuantityXMoney
 * @property bool $Registered
 * @property string $RegularBidMethodName
 * @property bool $RequireLotChangeConfirmation
 * @property bool $ReserveMetNotice
 * @property bool $ReserveNotMetNotice
 * @property float $ReservePrice
 * @property int $RtbCurrentLotId
 * @property string $SeoUrl
 * @property int|null $SettlementId
 * @property DateTime $StartDate
 * @property float|null $StartingBid
 * @property string $TermsAndConditions
 * @property int $DateAssignmentStrategy
 * @property bool $UserEmailVerified
 * @property int $UserFlag
 * @property int $UserId
 * @property UserInfo $UserInfo
 * @property bool $UsNumberFormatting
 * @property bool $VerifyEmail
 * @property string $VerifyMethodName
 * @property string $ViewMode
 * @property int $WinningAuctionId
 * @property bool $WinningBidView
 */
class WebCell extends QPanel
{
    use AbsenteeBidLoaderAwareTrait;
    use AssumedAskingBidLiveCalculatorAwareTrait;
    use AuctionAccessCheckerCreateTrait;
    use AuctionAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLotStatusPureCheckerAwareTrait;
    use AuctionStatusCheckerAwareTrait;
    use BackUrlParserAwareTrait;
    use BidderTermsAgreementManagerAwareTrait;
    use CachedTranslatorAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use LotBuyerGroupAccessHelperAwareTrait;
    use LotQuantityRendererCreateTrait;
    use LotReservePriceCheckerCreateTrait;
    use NumberFormatterAwareTrait;
    use RtbLoaderAwareTrait;
    use ServerRequestReaderAwareTrait;
    use TermsAndConditionsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserLoaderAwareTrait;

    public const BLOCK_BIDDING_CELL = 1;
    public const BLOCK_BIDDING_PAUSED = 2;
    public const BLOCK_LOGIN = 3;
    public const BLOCK_VERIFY = 4;
    public const BLOCK_AUCTION_REGISTRATION = 5;
    public const BLOCK_AUCTION_APPROVAL = 6;
    public const BLOCK_AUCTION_UPCOMING = 7;
    public const BLOCK_SPECIAL_TERMS = 8;
    public const BLOCK_LOT_CHANGES = 9;
    public const BLOCK_RESTRICTED_BUYERS_GROUP = 10;
    public const BLOCK_REGULAR_BID = 14;
    public const BLOCK_FORCE_BID = 15;
    public const BLOCK_NEXT_BID = 16;
    public const BLOCK_BUY_NOW = 17;
    public const BLOCK_BIDDING_STATUS = 18;
    public const BLOCK_RESERVE_NOT_MET = 19;
    public const BLOCK_VIEW_DETAILS = 20;
    public const BLOCK_INVOICE = 21;
    public const BLOCK_SETTLEMENT = 22;
    public const BLOCK_ABSENTEE = 23;
    public const BLOCK_BID_HISTORY = 24;
    public const BLOCK_QUANTITY_X_MONEY = 25;
    public const BLOCK_ABSENTEE_NOTIFICATION = 26;
    public const BLOCK_CLOSED_LOT = 27;
    public const BLOCK_CLOSED_MESSAGE = 28;
    public const BLOCK_REGULAR_BID_ACTION = 29;
    public const BLOCK_REGULAR_BID_ERROR = 30;
    public const BLOCK_FORCE_BID_ACTION = 31;
    public const BLOCK_FORCE_BID_ERROR = 32;
    public const BLOCK_NEXT_BID_ACTION = 33;
    public const BLOCK_NEXT_BID_ERROR = 34;
    public const BLOCK_GENERAL_ERROR = 35;
    public const BLOCK_OUTSTANDING_EXCEEDED = 36;
    public const BLOCK_RESERVE_MET = 37;

    protected static array $blockClasses = [
        self::BLOCK_BIDDING_CELL => 'bidding-cell',
        self::BLOCK_BIDDING_PAUSED => 'bidding-paused',
        self::BLOCK_LOGIN => 'login',
        self::BLOCK_VERIFY => 'verify',
        self::BLOCK_AUCTION_REGISTRATION => 'auction-registration',
        self::BLOCK_AUCTION_APPROVAL => 'auction-approval',
        self::BLOCK_AUCTION_UPCOMING => 'auction-upcoming',
        self::BLOCK_SPECIAL_TERMS => 'special-terms',
        self::BLOCK_LOT_CHANGES => 'lot-changes',
        self::BLOCK_RESTRICTED_BUYERS_GROUP => 'restricted-buyers-group',
        self::BLOCK_REGULAR_BID => 'regular-bid',
        self::BLOCK_REGULAR_BID_ACTION => 'regular-bid-action',
        self::BLOCK_REGULAR_BID_ERROR => 'regular-bid-error',
        self::BLOCK_FORCE_BID => 'force-bid',
        self::BLOCK_FORCE_BID_ACTION => 'force-bid-action',
        self::BLOCK_FORCE_BID_ERROR => 'force-bid-error',
        self::BLOCK_NEXT_BID => 'next-bid',
        self::BLOCK_NEXT_BID_ACTION => 'next-bid-action',
        self::BLOCK_NEXT_BID_ERROR => 'next-bid-error',
        self::BLOCK_BUY_NOW => 'buy-now',
        self::BLOCK_BIDDING_STATUS => 'bidding-status',
        self::BLOCK_RESERVE_NOT_MET => 'reserve-not-met',
        self::BLOCK_VIEW_DETAILS => 'view-details',
        self::BLOCK_INVOICE => 'invoice',
        self::BLOCK_SETTLEMENT => 'settlement',
        self::BLOCK_ABSENTEE => 'absentee',
        self::BLOCK_BID_HISTORY => 'bid-history',
        self::BLOCK_QUANTITY_X_MONEY => 'lot-quantity-x-money',
        self::BLOCK_ABSENTEE_NOTIFICATION => 'absentee-notification',
        self::BLOCK_CLOSED_LOT => 'closed-lot',
        self::BLOCK_CLOSED_MESSAGE => 'closed-msg',
        self::BLOCK_GENERAL_ERROR => 'general-error',
        self::BLOCK_OUTSTANDING_EXCEEDED => 'outstanding-exceeded',
        self::BLOCK_RESERVE_MET => 'reserve-met',
    ];

    public const CONTROL_BLK_BIDDING_CELL = 1;
    public const CONTROL_TXT_REGULAR_BID = 2;
    public const CONTROL_BTN_VERIFY = 3;
    //    public const CONTROL_HID_PLACED_BID = 4;
    public const CONTROL_RESERVE_NOT_MET = 5;
    public const CONTROL_TXT_FORCE_BID = 6;
    public const CONTROL_BTN_REGISTER = 7;
    public const CONTROL_BLK_BIDDING_STATUS = 8;
    public const CONTROL_ICO_WAIT = 12;
    public const CONTROL_BTN_REGULAR_BID = 13;
    public const CONTROL_BTN_FORCE_BID = 14;
    public const CONTROL_CURRENCY = 15;    // Currency sign, decorated with SPAN tag
    public const CONTROL_LNK_BUY_NOW = 16;
    public const CONTROL_BTN_NEXT_BID = 17;
    public const CONTROL_BTN_SPECIAL_TERMS = 18;
    public const CONTROL_BTN_LOT_CHANGES = 19;
    public const CONTROL_BTN_RESTRICTED_GROUP = 20;
    public const CONTROL_BTN_BUY_NOW = 21;
    public const CONTROL_HID_VISIBLE_ASKING_BID = 22;
    public const CONTROL_LBL_REGULAR_BID_ERROR = 23;
    public const CONTROL_LBL_FORCE_BID_ERROR = 24;
    public const CONTROL_LBL_NEXT_BID_ERROR = 25;
    public const CONTROL_LBL_GENERAL_ERROR = 26;
    public const CONTROL_BLK_ABSENTEE = 27;
    public const CONTROL_RESERVE_MET = 28;

    protected static array $controlIdPrefixes = [
        self::CONTROL_BLK_BIDDING_CELL => 'bidcell',
        self::CONTROL_TXT_REGULAR_BID => 'tmbid',
        self::CONTROL_BTN_VERIFY => 'btnV',
        //        self::CONTROL_HID_PLACED_BID => 'pb',
        self::CONTROL_TXT_FORCE_BID => 'tfbid',
        self::CONTROL_BTN_REGISTER => 'btnr',
        self::CONTROL_BLK_BIDDING_STATUS => 'yh',
        self::CONTROL_ICO_WAIT => 'bWait',
        self::CONTROL_BTN_REGULAR_BID => 'bPbid',
        self::CONTROL_BTN_FORCE_BID => 'bFbid',
        self::CONTROL_CURRENCY => 'scur',
        self::CONTROL_LNK_BUY_NOW => 'buy-now',
        self::CONTROL_BTN_NEXT_BID => 'bNext',
        self::CONTROL_BTN_RESTRICTED_GROUP => 'pRestrictedGroup',
        self::CONTROL_BTN_BUY_NOW => 'bBnow',
        self::CONTROL_BTN_SPECIAL_TERMS => 'pSpecTerms',
        self::CONTROL_BTN_LOT_CHANGES => 'pLotChanges',
        self::CONTROL_RESERVE_NOT_MET => 'reserve-not-met-',
        self::CONTROL_RESERVE_MET => 'reserve-met-',
        self::CONTROL_HID_VISIBLE_ASKING_BID => 'hidVisAskBid',
        self::CONTROL_LBL_REGULAR_BID_ERROR => 'lrberr',
        self::CONTROL_LBL_FORCE_BID_ERROR => 'lfberr',
        self::CONTROL_LBL_NEXT_BID_ERROR => 'lnberr',
        self::CONTROL_LBL_GENERAL_ERROR => 'lgberr',
        self::CONTROL_BLK_ABSENTEE => 'blkAbs',
    ];

    // For public properties
    protected ?string $absenteeBidsDisplay = null;
    protected ?string $absenteeNotification = null;
    protected ?int $accountId = null;    // for url building
    protected ?bool $isAllowForceBid = null;
    protected bool $isApproved = false;
    protected ?float $askingBid = null;
    protected ?AuctionLotItemCache $auctionLotCache = null;
    protected ?AuctionBidder $auctionBidder = null;
    protected ?bool $isAuctionClosed = null;
    protected bool $isAuctionListingOnly = false;
    protected ?int $auctionLotId = null;
    protected bool $isAuctionReverse = false;
    protected int $auctionStatusId = Constants\Auction::AS_NONE;
    protected string $auctionType = Constants\Auction::LIVE;
    protected ?int $bidCount = null;
    protected bool $isBiddingPaused = false;
    protected ?bool $isBidderTermsAreAgreed = null;
    protected ?float $buyNowAmount = null;
    protected string $buyNowRestriction = '';
    protected bool $isBuyNowUnsold = false;
    protected bool $isConditionalSales = false;
    protected bool $isConfirmTimedBid = false;
    protected ?string $currencySign = null;
    protected ?float $currentBidAmount = null;
    protected ?int $currentBidderId = null;
    protected ?float $currentMaxBid = null;
    protected ?float $currentTransactionBid = null;
    protected ?DateTime $endDate = null;
    protected ?int $eventType = null;
    protected ?string $forceBidMethodName = null;
    protected ?float $hammerPrice = null;
    protected ?bool $isInlineBidConfirm = null;
    protected ?int $invoiceId = null;                      // my items only
    protected ?bool $isBidder = null;
    protected ?bool $isKeepRegularBid = null;
    protected bool $isListingOnly = false;
    protected ?bool $isLotBiddingInfoAllowed = null;
    protected ?bool $isLotBiddingHistoryAllowed = null;
    protected ?string $lotChanges = null;
    protected int $lotItemId = 0;
    protected ?int $lotNum = null;
    protected int $lotStatusId = Constants\Lot::LS_UNASSIGNED;
    protected ?float $myMaxBid = null;
    protected ?bool $isNextBidEnabled = null;
    protected ?string $nextBidMethodName = null;
    protected ?bool $isNoBidding = null;
    protected ?float $quantity = null;
    protected ?int $quantityScale = null;
    protected bool $isQuantityXMoney = false;
    protected ?string $regularBidMethodName = null;
    protected ?bool $isRegistered = null;
    protected ?bool $isRequireLotChangeConfirmation = null;
    protected ?bool $isReserveNotMetNotice = null;
    protected ?bool $isReserveMetNotice = null;
    protected ?bool $isUsNumberFormatting = null;
    protected ?float $reservePrice = null;
    protected int $rtbCurrentLotId = 0;
    protected ?int $settlementId = null;            // my items only
    protected ?DateTime $startDate = null;
    protected ?float $startingBid = null;
    protected ?string $termsAndConditions = null;
    protected ?int $dateAssignmentStrategy = null;
    protected ?bool $isUserEmailVerified = null;
    protected ?int $userFlag = null;
    protected ?int $userId = null;
    protected ?UserInfo $userInfo = null;
    protected ?bool $isVerifyEmail = null;
    protected ?string $verifyMethodName = null;
    protected ?int $winningAuctionId = null;               // my items only
    protected ?bool $isWinningBidView = null;
    protected ?string $seoUrl = null;

    // Controls
    protected ?Button $btnRegister = null;
    protected ?Button $btnVerified = null;
    protected ?QTextBox $txtRegularBid = null;
    protected ?Button $btnRegularBid = null;
    protected ?QLabel $lblRegularBidError = null;
    protected ?QTextBox $txtForceBid = null;
    protected ?Button $btnForceBid = null;
    protected ?QLabel $lblForceBidError = null;
    protected ?Button $btnNextBid = null;
    protected ?QLabel $lblNextBidError = null;
    protected ?QLabel $lblGeneralError = null;
    protected ?QWaitIcon $icoWait = null;
    protected ?QHiddenInput $hidVisAskBid = null;
    protected ?LotAmountRendererInterface $lotAmountRenderer = null;

    /**
     * Render controls
     * @param bool $isDisplayOutput
     * @return string controls html
     */
    public function render($isDisplayOutput = true): string
    {
        $output = '';
        if (
            !$this->isListingOnly
            && !$this->isAuctionListingOnly
        ) {
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            if ($auctionStatusPureChecker->isLiveOrHybrid($this->auctionType)) {
                $output = $this->getCellLiveHtml();
            } else {
                $output = $this->getCellTimedHtml();
            }

            $output .= $this->getGeneralErrorHtml();

            $output = $this->decorateWithDiv(
                $output,
                self::BLOCK_BIDDING_CELL,
                $this->getControlId(self::CONTROL_BLK_BIDDING_CELL)
            );
        }
        if ($isDisplayOutput) {
            print($output);
            return '';
        }

        return $output;
    }

    /**
     * Return prefix of control id
     * @param int $controlType
     * @return string
     */
    public static function getControlIdPrefix(int $controlType): string
    {
        $prefix = self::$controlIdPrefixes[$controlType];
        return $prefix;
    }

    /**
     * Compose and return control id
     * @param int $controlType
     * @param int|null $auctionLotId
     * @return string
     */
    public static function composeControlId(int $controlType, ?int $auctionLotId): string
    {
        $controlId = self::getControlIdPrefix($controlType) . $auctionLotId;
        return $controlId;
    }

    /**
     * Return control id for a control
     * @param int $controlType
     * @return string
     */
    protected function getControlId(int $controlType): string
    {
        $controlId = self::composeControlId($controlType, $this->auctionLotId);
        return $controlId;
    }

    /**
     * Return class name for block
     * @param int $blockType
     * @return string
     */
    public static function getBlockClass(int $blockType): string
    {
        return self::$blockClasses[$blockType];
    }

    /**
     * Set error message for regular bid
     * @param string $error
     */
    public function setRegularBidError(string $error): void
    {
        $error = trim($error);
        $this->lblRegularBidError->HtmlEntities = false;
        $this->lblRegularBidError->Text = trim($error);
        if ($error) {
            $this->txtRegularBid->Focus();
            $this->txtRegularBid->Refresh();
        }
    }

    /**
     * Set error message for force bid
     * @param string $error
     */
    public function setForceBidError(string $error): void
    {
        $error = trim($error);
        $this->lblForceBidError->HtmlEntities = false;
        $this->lblForceBidError->Text = $error;
        if ($error) {
            $this->txtForceBid->Focus();
            $this->txtForceBid->Refresh();
        }
    }

    /**
     * Set error message for next bid
     * @param string $error
     */
    public function setNextBidError(string $error): void
    {
        $this->lblNextBidError->HtmlEntities = false;
        $this->lblNextBidError->Text = trim($error);
    }

    /**
     * Set general error message (not related to any bidding button)
     * @param string $error
     */
    public function setGeneralError(string $error): void
    {
        $this->lblGeneralError->Text = trim($error);
    }

    /**
     * Set error message for some bidding way
     * @param string $error
     * @param bool $isNextBid
     * @param bool $isForceBid
     */
    public function setBidError(string $error, bool $isNextBid = false, bool $isForceBid = false): void
    {
        if ($isForceBid) {
            $this->setForceBidError($error);
        } elseif ($isNextBid) {
            $this->setNextBidError($error);
        } else {
            $this->setRegularBidError($error);
        }
    }

    /**
     * Clear error messages
     */
    public function clearErrors(): void
    {
        if ($this->lblRegularBidError) {
            $this->lblRegularBidError->Text = '';
        }
        if ($this->lblForceBidError) {
            $this->lblForceBidError->Text = '';
        }
        if ($this->lblNextBidError) {
            $this->lblNextBidError->Text = '';
        }
        if ($this->lblGeneralError) {
            $this->lblGeneralError->Text = '';
        }
    }

    /**
     * Return translation for a key
     * @param string $key
     * @return string
     */
    protected function translate(string $key): string
    {
        $trans = [
            'RESERVENOTMET' => ['CATALOG_RESERVENOTMET', 'catalog'],
            'RESERVEMET' => ['CATALOG_RESERVEMET', 'catalog'],
            'CONFIRM_PLACE_BID' => ['CATALOG_CONFIRM_PLACE_BID', 'catalog'],
            'CURRENT_ABSENTEE' => ['CATALOG_CURRENT_ABSENTEE', 'catalog'],
            'FORCE_BID' => ['CATALOG_FORCE_BID', 'catalog'],
            'NEXTBID_BUTTON_TEXT' => ['CATALOG_NEXTBID_BUTTON_TEXT', 'catalog'],
            'TABLE_BUYNOW' => ['CATALOG_TABLE_BUYNOW', 'catalog'],
            'UPCOMING' => ['CATALOG_UPCOMING', 'catalog'],
            'VIEWDETAILS' => ['CATALOG_VIEWDETAILS', 'catalog'],
            'BID' => ['GENERAL_BID', 'general'],
            'BIDDING_PAUSED' => ['GENERAL_BIDDING_PAUSED', 'general'],
            'BIDS' => ['GENERAL_BIDS', 'general'],
            'CHANGEBID' => ['GENERAL_CHANGEBID', 'general'],
            'CLOSED' => ['GENERAL_CLOSED', 'general'],
            'LOGINTOBID' => ['GENERAL_LOGINTOBID', 'general'],
            'NO_BIDDER_PRIVILEGE' => ['GENERAL_NO_BIDDER_PRIVILEGE', 'general'],
            'OUTBID' => ['GENERAL_OUTBID', 'general'],
            'PENDINGAPPROVAL' => ['GENERAL_PENDINGAPPROVAL', 'general'],
            'PLACEBID' => ['GENERAL_PLACEBID', 'general'],
            'REGISTERTOBID' => ['GENERAL_REGISTERTOBID', 'general'],
            'RESTRICTED_GROUP' => ['GENERAL_RESTRICTED_GROUP', 'general'],
            'VERIFY_ACCT' => ['GENERAL_VERIFY_ACCT', 'general'],
            'BIDDINGHISTORY' => ['MYITEMS_BIDDINGHISTORY', 'myitems'],
            'INVOICELINK' => ['MYITEMS_INVOICELINK', 'myitems'],
            'SETTLEMENTSLINK' => ['MYITEMS_SETTLEMENTSLINK', 'myitems'],
        ];
        $trans['MY_BID'] = $this->isAuctionReverse
            ? ['CATALOG_TABLE_MYMINBID', 'catalog']
            : ['CATALOG_TABLE_MYMAXBID', 'catalog'];
        $trans['LOT_CHANGES'] = ['CATALOG_LOT_CHANGES', 'catalog'];
        $trans['SPEC_TERMS'] = ['CATALOG_SPEC_TERMS', 'catalog'];
        $text = $this->getTranslator()->translate($trans[$key][0], $trans[$key][1]);
        return $text;
    }

    /**
     * Return html of timed bidding cell
     * @return string
     *
     */
    protected function getCellTimedHtml(): string
    {
        if ($this->isBiddingPaused) {
            $output = $this->getBiddingPausedHtml();
        } else {
            $currentDate = $this->getCurrentDateUtc();
            $auctionLotStatusPureChecker = $this->getAuctionLotStatusPureChecker();
            if (
                $this->startDate
                && ($currentDate->getTimestamp() < $this->startDate->getTimestamp())
            ) // not yet started
            {
                if (!$this->userId) {
                    $output = $this->getLoginHtml();
                } elseif ($this->isVerifiedUser()) {
                    if (!$this->isRegistered) {
                        $output = $this->getAuctionRegistrationHtml();
                    } elseif (!$this->isApproved) {
                        $output = $this->getAuctionApprovalHtml();
                    } else { // Approved and registered
                        $output = $this->getAutionUpcomingHtml();
                    }
                } else {
                    $output = $this->getVerificationHtml();
                }
            } elseif (
                $auctionLotStatusPureChecker->isActive($this->lotStatusId)
                && $this->endDate
                && ($currentDate->getTimestamp() < $this->endDate->getTimestamp())
            ) // not yet ended, not purchased and not offer accepted
            {
                if (!$this->userId) {
                    $output = $this->getLoginHtml();
                } elseif ($this->isVerifiedUser()) {
                    $output = $this->getVerifiedUserControlsForTimedHtml();
                } else {
                    $output = $this->getVerificationHtml();
                }
            } else { // Ended lot
                $output = $this->getClosedLotHtml();
            }
        }
        return $output;
    }

    /**
     * Return html for registered and verified (if needed) on site user (not in specific auction)
     * @return string
     *
     */
    protected function getVerifiedUserControlsForTimedHtml(): string
    {
        $output = '';
        if (!$this->isRegistered) {
            $output .= $this->getAuctionRegistrationHtml();
        } elseif (!$this->isApproved) {
            $output .= $this->getAuctionApprovalHtml();
        } elseif ($this->isSpecialTermsApprovalRequired()) {
            $output .= $this->getSpecialTermsHtml();
        } elseif ($this->isLotChangesApprovalRequired()) {
            $output .= $this->getLotChangesHtml();
        } elseif ($this->isRestrictedBuyerGroup()) {
            $output .= $this->getRestrictedBuyerGroupHtml();
        } elseif ($this->isOutstandingLimitExceeded()) {
            $output .= $this->getOutstandingLimitExceededHtml();
        } else { // Approved and registered
            $output .= $this->getRegularBiddingTimedHtml();

            $reserveCheckResult = $this->createLotReservePriceChecker()
                ->setLotItemId($this->lotItemId)
                ->setAuction($this->getAuction())
                ->check();
            if ($reserveCheckResult === LotReservePriceChecker::IS_MET) {
                $output .= $this->getReserveMetHtml();
            } elseif ($reserveCheckResult === LotReservePriceChecker::NOT_MET) {
                $output .= $this->getReserveNotMetHtml();
            }

            $output .= $this->getInvoiceHtml();
            $output .= $this->getSettlementHtml();
        }
        return $output;
    }

    /**
     * Return html for regular bidding block on timed lot
     * @return string
     *
     */
    protected function getRegularBiddingTimedHtml(): string
    {
        $output = $this->getBiddingStatusHtml();
        if (!$this->isNoBidding) {
            $output .= $this->getNextBidHtml();
            $output .= $this->getRegularBidHtml();
            $output .= $this->getForceBidHtml();
            $output .= $this->getQuantityXMoneyHtml();
            $output .= $this->getHiddenAskingBidHtml();
        }
        $output .= $this->getBuyNowHtml();
        $output .= $this->getViewDetailsHtml();
        $output .= $this->getWaitIconHtml();
        if ($this->isLotBiddingHistoryAllowed) {
            $output .= $this->getBiddingHistoryLink();
        }
        return $output;
    }

    /**
     * Return html of live bidding cell
     * @return string
     *
     */
    protected function getCellLiveHtml(): string
    {
        if ($this->isBiddingPaused) {
            $output = $this->getBiddingPausedHtml();
        } elseif ($this->isClosedLot()) {
            $output = $this->getClosedLotHtml();
        } elseif (!$this->userId) {
            $output = $this->getLoginHtml();
        } elseif ($this->isVerifiedUser()) {
            $output = $this->getVerifiedUserControlsForLiveHtml();
        } else {
            $output = $this->getVerificationHtml(); // ???
        }
        return $output;
    }

    /**
     * Return html for registered and verified (if needed) on site user (not in specific auction)
     * @return string
     *
     */
    protected function getVerifiedUserControlsForLiveHtml(): string
    {
        $output = '';
        if (!$this->isRegistered) {
            $output .= $this->getAuctionRegistrationHtml();
        } elseif (!$this->isApproved) {
            $output .= $this->getAuctionApprovalHtml();
        } elseif ($this->isSpecialTermsApprovalRequired()) {
            $output .= $this->getSpecialTermsHtml();
        } elseif ($this->isLotChangesApprovalRequired()) {
            $output .= $this->getLotChangesHtml();
        } elseif ($this->isRestrictedBuyerGroup()) {
            $output .= $this->getRestrictedBuyerGroupHtml();
        } elseif ($this->isOutstandingLimitExceeded()) {
            $output .= $this->getOutstandingLimitExceededHtml();
        } else {
            $output .= $this->getRegularBiddingLiveHtml();
        }
        return $output;
    }

    /**
     * Return html for regular bidding block on live lot
     * @return string
     *
     */
    protected function getRegularBiddingLiveHtml(): string
    {
        $output = $this->getBiddingStatusHtml();
        $output .= $this->getRegularBidHtml();
        $output .= $this->getAbsenteeNotification();
        $output .= $this->getQuantityXMoneyHtml();
        $output .= $this->getReserveNotMetHtml();
        $output .= $this->getReserveMetHtml();
        $output .= $this->getAbsenteeHtml();
        $output .= $this->getBuyNowHtml();
        $output .= $this->getViewDetailsHtml();
        $output .= $this->getBiddingHistoryLink();
        $output .= $this->getWaitIconHtml();
        $output .= $this->getHiddenAskingBidHtml();
        return $output;
    }


    /** ********************************
     * Useful methods for general usage
     */

    /**
     * Check if "reserve not met" message should be shown
     * @return bool
     */
    protected function isReserveNotMetNotice(): bool
    {
        $reserveCheckResult = $this->createLotReservePriceChecker()
            ->setLotItemId($this->lotItemId)
            ->setAuction($this->getAuction())
            ->check();
        $isNotice = $this->isReserveNotMetNotice
            && $this->getAuctionLotStatusPureChecker()->isActive($this->lotStatusId)
            && $reserveCheckResult === LotReservePriceChecker::NOT_MET;
        return $isNotice;
    }

    /**
     * Check if "reserve not met" message should be shown
     * @return bool
     */
    protected function isReserveMetNotice(): bool
    {
        $reserveCheckResult = $this->createLotReservePriceChecker()
            ->setLotItemId($this->lotItemId)
            ->setAuction($this->getAuction())
            ->check();
        $isNotice = $this->isReserveMetNotice
            && $this->getAuctionLotStatusPureChecker()->isActive($this->lotStatusId)
            && $reserveCheckResult === LotReservePriceChecker::IS_MET;
        return $isNotice;
    }

    /**
     * Check if we should show absentee info block
     * @return bool
     */
    protected function isAbsenteeInfo(): bool
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $isAbsenteeInfo =
            $auctionStatusPureChecker->isLiveOrHybrid($this->auctionType)
            && !$auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsDoNotDisplay($this->absenteeBidsDisplay)
            && $this->isLotBiddingInfoAllowed
            && $this->bidCount > 0
            && $this->getAuctionLotStatusPureChecker()->isActive($this->lotStatusId);
        return $isAbsenteeInfo;
    }

    /**
     * Check if user is allowed to register in auction
     * User must be verified by e-mail, if respective option is On (setting_user.verify_email)
     * @return bool
     */
    protected function isVerifiedUser(): bool
    {
        $isVerified =
            !$this->isVerifyEmail    // no e-mail verification required
            || $this->isUserEmailVerified; // user is verified by e-mail
        return $isVerified;
    }

    /**
     * Check "next bid" button feature is available
     * @return bool
     */
    protected function isNextBidAvailable(): bool
    {
        $isNextBidEnabled =
            $this->isNextBidEnabled
            && $this->isLotBiddingInfoAllowed;
        return $isNextBidEnabled;
    }

    /**
     * Return html for "buy now" block
     * @return bool
     */
    protected function isBuyNowAvailable(): bool
    {
        $isAvailable = false;
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($this->auctionType)) {
            $isAvailable = BuyNowAvailabilityTimedChecker::new()
                // ->enableAllowedForUnsold($this->isBuyNowUnsold)
                ->enableApprovedBidder($this->isApproved)
                ->enableAuctionListingOnly($this->isAuctionListingOnly)
                ->enableAuctionLotListingOnly($this->isListingOnly)
                ->enableBiddingPaused($this->isBiddingPaused)
                ->setBuyNowAmount($this->buyNowAmount)
                ->setCurrentBid($this->currentBidAmount)
                ->setEndDateUtc($this->endDate)
                ->setLotStatus($this->lotStatusId)
                ->setStartDateUtc($this->startDate)
                ->setUserFlag((int)$this->userFlag)
                ->isAvailable();
        } elseif ($auctionStatusPureChecker->isLiveOrHybrid($this->auctionType)) {
            $isAvailable = BuyNowAvailabilityLiveChecker::new()
                ->enableAllowedForUnsold($this->isBuyNowUnsold)
                ->enableApprovedBidder($this->isApproved)
                ->enableAuctionListingOnly($this->isAuctionListingOnly)
                ->enableAuctionLotListingOnly($this->isListingOnly)
                ->enableBiddingPaused($this->isBiddingPaused)
                ->setAuctionStatus($this->auctionStatusId)
                ->setBuyNowAmount($this->buyNowAmount)
                ->setCurrentAbsenteeBid($this->currentBidAmount)
                ->setCurrentTransactionBid($this->currentTransactionBid)
                ->setLotItemId($this->lotItemId)
                ->setLotStatus($this->lotStatusId)
                ->setRestriction($this->buyNowRestriction)
                ->setRunningLotItemId($this->rtbCurrentLotId)
                ->setStartDateUtc($this->startDate)
                ->setUserFlag((int)$this->userFlag)
                ->isAvailable();
        }
        return $isAvailable;
    }

    /**
     * Check if lot has special terms and conditions and if user suggested them
     * @return bool
     */
    protected function isSpecialTermsApprovalRequired(): bool
    {
        $aucLotTerms = $this->getAuctionLotItemSpectialTerms();
        $isUserAgreement = $this->hasBidderTermsAgreement();
        $isRequired = $aucLotTerms
            && !$isUserAgreement;
        return $isRequired;
    }

    /**
     * @return bool
     */
    protected function hasBidderTermsAgreement(): bool
    {
        if ($this->isBidderTermsAreAgreed === null) {
            $this->isBidderTermsAreAgreed = $this->getBidderTermsAgreementManager()
                ->has($this->userId, $this->lotItemId, $this->getAuctionId());
        }
        return $this->isBidderTermsAreAgreed;
    }

    /**
     * Return ali.terms_and_conditions
     * @return string
     */
    protected function getAuctionLotItemSpectialTerms(): string
    {
        if ($this->termsAndConditions === null) {
            $this->termsAndConditions = $this->getTermsAndConditionsManager()
                ->loadForAuctionLot($this->lotItemId, $this->getAuctionId());
        }
        return $this->termsAndConditions;
    }

    /**
     * Check if lot changes acceptance is required
     * @return bool
     */
    protected function isLotChangesApprovalRequired(): bool
    {
        $changesAgreement = ChangesAgreement::new();
        $isRequired = $changesAgreement->isRequired($this->isRequireLotChangeConfirmation, $this->lotChanges)
            && !$changesAgreement->isAccepted($this->userId, $this->lotItemId, $this->getAuctionId());
        return $isRequired;
    }

    /**
     * Check if lot is related to restricted buyer group
     * @return bool
     */
    protected function isRestrictedBuyerGroup(): bool
    {
        $isRestricted = $this->getLotBuyerGroupAccessHelper()->isRestrictedBuyerGroup(
            $this->userId,
            $this->lotItemId
        );
        return $isRestricted;
    }

    /**
     * Check if user is ready for bidding at auction
     * @return bool
     */
    protected function isAuctionReadyUser(): bool
    {
        $isAuctionApproved =
            !$this->isBiddingPaused
            && $this->userId
            && $this->isVerifiedUser()
            && $this->isRegistered
            && $this->isApproved
            && !$this->isSpecialTermsApprovalRequired()
            && !$this->isLotChangesApprovalRequired()
            && !$this->isRestrictedBuyerGroup();
        return $isAuctionApproved;
    }

    /**
     * Check if lot is closed
     * @return bool
     */
    protected function isClosedLot(): bool
    {
        $isClosed = $this->isAuctionClosed
            || !$this->getAuctionLotStatusPureChecker()->isActive($this->lotStatusId);
        return $isClosed;
    }

    /**
     * Check if lot is sold (by its status)
     * @return bool
     */
    protected function isAmongWonStatuses(): bool
    {
        return $this->getAuctionLotStatusPureChecker()->isAmongWonStatuses($this->lotStatusId);
    }

    /**
     * Check if new bid is possible - bid limit is not exceeded
     * I.e., reverse auction
     * @return bool
     */
    protected function isBidPossible(): bool
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $isPossible =
            $auctionStatusPureChecker->isTimed($this->auctionType)    // reverse auction is allowed for timed only
            && $this->currentBidAmount !== null                 // at least one bid has been placed
            && $this->askingBid === null;                 // but asking bid is not possible
        return $isPossible;
    }

    /**
     * Check if outstanding limit exceed or current user isn't bidder at all
     * @return bool
     */
    protected function isOutstandingLimitExceeded(): bool
    {
        $isExceed = true;
        $auctionBidder = $this->getAuctionBidder();
        if ($auctionBidder) {
            $outstandingHelper = BidderOutstandingHelper::new()
                ->setUserInfo($this->UserInfo);
            $isExceed = $outstandingHelper->isLimitExceeded($auctionBidder);
        }
        return $isExceed;
    }

    /**
     * Return auction bidder
     * @return AuctionBidder|null
     */
    protected function getAuctionBidder(): ?AuctionBidder
    {
        if ($this->auctionBidder === null) {
            $this->auctionBidder = $this->getAuctionBidderLoader()->load($this->userId, $this->getAuctionId(), true);
        }
        return $this->auctionBidder;
    }

    /**
     * Decorate output string with DIV tag with respective class
     * @param string $output
     * @param int $blockType
     * @param string $controlId
     * @return string
     */
    protected function decorateWithDiv(string $output, int $blockType, string $controlId = ''): string
    {
        return $this->decorateWithTag('div', $output, $blockType, $controlId);
    }

    protected function decorateWithSpan(string $output, int $blockType, string $controlId = ''): string
    {
        return $this->decorateWithTag('span', $output, $blockType, $controlId);
    }

    protected function decorateWithTag(string $tag, string $output, int $blockType, string $controlId = ''): string
    {
        $class = self::$blockClasses[$blockType];
        if ($controlId) {
            $controlId = 'id="' . $controlId . '"';
        }
        $output = sprintf('<%s class="%s" %s>%s</%s>', $tag, $class, $controlId, $output, $tag);
        return $output;
    }

    /**
     * Return currency sign (and cache it)
     * @return string
     */
    protected function getCurrencySign(): string
    {
        if ($this->currencySign === null) {
            $this->currencySign = $this->getCurrencyLoader()->detectDefaultSign($this->getAuctionId());
        }
        return $this->currencySign;
    }

    /**
     * Return currency decorated with SPAN tag with id.
     * It is used by auto synchronization to update currency sign.
     * @return string
     */
    public function getCurrencyDecorated(): string
    {
        $controlId = $this->getControlId(self::CONTROL_CURRENCY);
        $currencySign = sprintf(
            self::getCurrencyDecoratedTemplate(),
            $controlId,
            $this->getCurrencySign()
        );
        return $currencySign;
    }

    /**
     * Return template for currency sign, which is decorated with SPAN tag with id.
     * It is used by auto synchronization to update currency sign.
     * @return string
     */
    public static function getCurrencyDecoratedTemplate(): string
    {
        $currencyDecoratedTemplate =
            '<span id="%s">' .
            "%s" .
            '</span>';
        return $currencyDecoratedTemplate;
    }

    /** ************************
     * Blocks rendering methods
     */

    /**
     * Return "bidding paused" message
     * @return string
     */
    protected function getBiddingPausedHtml(): string
    {
        $output = $this->translate('BIDDING_PAUSED');
        $output = $this->decorateWithDiv($output, self::BLOCK_BIDDING_PAUSED);
        return $output;
    }

    /**
     * Return "quantity x money" message
     * @return string
     */
    protected function getQuantityXMoneyHtml(): string
    {
        return $this->createLotQuantityRenderer()
            ->construct($this->getCachedTranslator(), $this->getLotAmountRenderer())
            ->renderQuantityIfBid(
                $this->quantity,
                $this->quantityScale,
                $this->isQuantityXMoney
            );
    }

    /**
     * Render html for absentee bids block
     * @return string
     */
    protected function getAbsenteeHtml(): string
    {
        $output = '';
        $auction = $this->getAuction();
        if (!$auction) {
            log_error(
                "Available auction not found, when rendering absentee bid html"
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return '';
        }
        $isAccessLotBiddingInfo = $this->createAuctionAccessChecker()->hasPermission(Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_INFO, $auction);
        if (!$isAccessLotBiddingInfo) {
            return $output;
        }
        if ($this->isAbsenteeInfo()) {
            $bids = $this->bidCount . ' ';
            $bids .= $this->bidCount > 1 ? $this->translate('BIDS')
                : $this->translate('BID');
            $output = $bids;
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();

            /** Live absentee bids >> Display number of bids and
             * bid amounts / bidder numbers is selected **/
            if ($auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsNumberOfAbsenteeLink($this->absenteeBidsDisplay)) {
                if ($this->isLotBiddingHistoryAllowed) {
                    $absenteeBidsUrl = $this->getUrlBuilder()->build(
                        ResponsiveAbsenteeBidsUrlConfig::new()->forWeb($this->lotItemId, $this->getAuctionId())
                    );
                    $output = '<a href="' . $absenteeBidsUrl . '">' . $output . '</a>';
                }
            } elseif ($auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsNumberOfAbsenteeHigh($this->absenteeBidsDisplay)) {
                if ($this->currentBidAmount > 0) {
                    $output =
                        $this->translate('CURRENT_ABSENTEE') . ': ' .
                        $this->getCurrencyDecorated() . $this->getNumberFormatter()->formatMoney($this->currentBidAmount)
                        . ' ' . sprintf(self::getBidCountDecoratedTemplate(), $bids);
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
     * Return template for absentee bid count, which is decorated by DIV tag
     * @return string
     */
    public static function getBidCountDecoratedTemplate(): string
    {
        $bidsTmpl = '<span>(%s)</span>';
        return $bidsTmpl;
    }

    /**
     * Return html for "reserve is met" message
     * @return string
     */
    protected function getReserveMetHtml(): string
    {
        $output = '';
        if ($this->isReserveMetNotice()) {
            $controlId = $this->getControlId(self::CONTROL_RESERVE_MET);
            $output = $this->translate('RESERVEMET');
            $output = $this->decorateWithDiv($output, self::BLOCK_RESERVE_MET, $controlId);
        }
        return $output;
    }


    /**
     * Return html for "reserve is not met" message
     * @return string
     */
    protected function getReserveNotMetHtml(): string
    {
        $output = '';
        if ($this->isReserveNotMetNotice()) {
            $controlId = $this->getControlId(self::CONTROL_RESERVE_NOT_MET);
            $output = $this->translate('RESERVENOTMET');
            $output = $this->decorateWithDiv($output, self::BLOCK_RESERVE_NOT_MET, $controlId);
        }
        return $output;
    }

    /**
     * Return html for login block
     * @return string
     */
    protected function getLoginHtml(): string
    {
        $url = $this->prepareLoginUrlForBidButton();
        $output = '<input type="button" class="button" onclick="sam.redirect(\'' . $url . '\');" ' .
            'value="' . $this->translate('LOGINTOBID') . '" />';
        $output = $this->decorateWithDiv($output, self::BLOCK_LOGIN);
        $output .= $this->getQuantityXMoneyHtml();
        $output .= $this->getReserveNotMetHtml();
        $output .= $this->getAbsenteeHtml();
        return $output;
    }

    /**
     * @return string
     */
    protected function prepareLoginUrlForBidButton(): string
    {
        $urlParser = $this->getUrlParser();
        $backUrl = $this->getServerRequestReader()->currentUrl();
        $backUrl = $urlParser->replaceParams($backUrl, [Constants\UrlParam::SALE_REG => $this->getAuctionId()]);
        $backUrl = $urlParser->replaceFragment($backUrl, 'lot' . $this->lotNum);

        $url = $this->getUrlBuilder()->build(ResponsiveLoginUrlConfig::new()->forRedirect());
        $url = $urlParser->replaceParams($url, [Constants\UrlParam::SALE => $this->getAuctionId()]);
        $url = $this->getBackUrlParser()->replace($url, $backUrl);

        return $url;
    }

    /**
     * Return html for site verification block
     * @return string
     *
     */
    protected function getVerificationHtml(): string
    {
        $btnVerify = $this->createVerifyButton();
        $output = $btnVerify->RenderWithError(false);
        $output = $this->decorateWithDiv($output, self::BLOCK_VERIFY);
        return $output;
    }

    /**
     * Create button for verification at site via e-mail
     * @return Button
     */
    protected function createVerifyButton(): Button
    {
        if (!$this->btnVerified) {
            $controlId = $this->getControlId(self::CONTROL_BTN_VERIFY);
            $this->btnVerified = Qform_ComponentFactory::new()
                ->createButton($this->objParentControl, $controlId);
            $this->btnVerified->Text = $this->translate('VERIFY_ACCT');
            $this->btnVerified->AddAction(
                new QClickEvent(),
                new QServerAction($this->verifyMethodName)
            );
        }
        return $this->btnVerified;
    }

    /**
     * Return html for auction registraton block
     * @return string
     *
     */
    protected function getAuctionRegistrationHtml(): string
    {
        $btnRegister = $this->createRegisterButton();
        $output = $btnRegister->Render(false);
        $output = $this->decorateWithDiv($output, self::BLOCK_AUCTION_REGISTRATION);
        $output .= $this->getQuantityXMoneyHtml();
        $output .= $this->getReserveNotMetHtml();
        $output .= $this->getAbsenteeHtml();
        return $output;
    }

    /**
     * Create button for registration at auction
     * @return Button
     */
    protected function createRegisterButton(): Button
    {
        if (!$this->btnRegister) {
            $controlId = $this->getControlId(self::CONTROL_BTN_REGISTER);
            $this->btnRegister = Qform_ComponentFactory::new()
                ->createButton($this->objParentControl, $controlId);
            $this->btnRegister->Text = $this->translate('REGISTERTOBID');
            $this->btnRegister->ActionParameter = $this->getAuctionId();
            if ($this->isBidder) {
                $url = '/auctions/register/id/%s/?';
                $url = sprintf($url, $this->getAuctionId());
                $backUrl = $this->getServerRequestReader()->currentUrl();
                $backUrl = $this->getUrlParser()->replaceFragment($backUrl, 'lot' . $this->lotNum);
                $url = $this->getBackUrlParser()->replace($url, $backUrl);
                $this->btnRegister->AddAction(
                    new QClickEvent(),
                    new QJavaScriptAction('sam.redirect(\'' . $url . '\');')
                );
            } else {
                $this->btnRegister->AddAction(
                    new QClickEvent(),
                    new QAlertAction($this->translate('NO_BIDDER_PRIVILEGE'))
                );
            }
        }
        return $this->btnRegister;
    }

    /**
     * Return html for auction approval block
     * @return string
     */
    protected function getAuctionApprovalHtml(): string
    {
        $output = $this->translate('PENDINGAPPROVAL');
        $output = $this->decorateWithDiv($output, self::BLOCK_AUCTION_APPROVAL);
        $output .= $this->getQuantityXMoneyHtml();
        $output .= $this->getReserveNotMetHtml();
        $output .= $this->getAbsenteeHtml();
        return $output;
    }

    /**
     * Return html with auction upcoming info
     * @return string
     */
    protected function getAutionUpcomingHtml(): string
    {
        $output = $this->translate('UPCOMING');
        $output = $this->decorateWithDiv($output, self::BLOCK_AUCTION_UPCOMING);
        return $output;
    }

    /** ***********************
     * Some approvement blocks
     */

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
        $button = '<input type="button" id="' . $controlId . '" ' .
            'class="button" value="' . $this->translate('SPEC_TERMS') . '" ' .
            'onclick="sam.redirect(\'' . $url . '\');" ' . ($activeByDatesRange ? '' : 'disabled') . ' />';
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
            'class="button-lot-changes" value="' . $this->translate('LOT_CHANGES') . '" ' .
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
            '<input type="button" id="' . $controlId . '" ' .
            'class="button" value="' . $this->translate('RESTRICTED_GROUP') . '" disabled />';
        $output .= $this->decorateWithDiv($button, self::BLOCK_RESTRICTED_BUYERS_GROUP);
        $output .= $this->getViewDetailsHtml();
        return $output;
    }

    /**
     * Return html for outstanding limit exceeded message
     * @return string
     */
    protected function getOutstandingLimitExceededHtml(): string
    {
        $auctionBidder = $this->getAuctionBidder();
        if (!$auctionBidder) {
            return '';
        }
        $output = BidderOutstandingHelper::new()
            ->setUserInfo($this->UserInfo)
            ->getOutstandingLimitExceededText($auctionBidder);
        $output = $this->decorateWithDiv($output, self::BLOCK_OUTSTANDING_EXCEEDED);
        return $output;
    }

    /** *******************************
     * Regular, forced, next bids, etc.
     */

    /**
     * Return html for regular bid textbox and place bid button block
     * @return string
     *
     */
    protected function getRegularBidHtml(): string
    {
        $txtRegularBid = $this->createRegularBidTextbox();
        $btnRegularBid = $this->createRegularBidButton();
        $lblRegularBidError = $this->createRegularBidErrorLabel();
        $action = $this->getCurrencyDecorated()
            . $txtRegularBid->RenderWithError(false)
            . $btnRegularBid->Render(false);
        $error = $lblRegularBidError->Render(false);
        $output = $this->decorateWithDiv($action, self::BLOCK_REGULAR_BID_ACTION)
            . $this->decorateWithDiv($error, self::BLOCK_REGULAR_BID_ERROR);
        $output = $this->decorateWithDiv($output, self::BLOCK_REGULAR_BID);
        return $output;
    }

    /**
     * Create and init regular bid textbox
     * @return QTextBox
     *
     */
    protected function createRegularBidTextbox(): QTextBox
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLiveOrHybrid($this->auctionType)) {
            $output = $this->createRegularBidLiveTextbox();
        } else {
            $output = $this->createRegularBidTimedTextbox();
        }
        return $output;
    }

    /**
     * Create and init regular bid textbox control for timed auction
     * @return QTextBox
     *
     */
    protected function createRegularBidTimedTextbox(): QTextBox
    {
        if (!$this->txtRegularBid) {
            $controlId = $this->getControlId(self::CONTROL_TXT_REGULAR_BID);
            $this->txtRegularBid = new QTextBox($this->objParentControl, $controlId);
            $this->txtRegularBid->AddCssClass('timed-regularbid');
            $this->txtRegularBid->AddAction(
                new QClickEvent(),
                new QJavaScriptAction("this.select();this.setSelectionRange(0,9999);this.contentEditable=true;")
            );
        }
        if (!$this->isKeepRegularBid) {
            $this->txtRegularBid->Text = isset($this->myMaxBid)
                ? $this->getNumberFormatter()->formatMoneyNto($this->myMaxBid) : null;
            $this->txtRegularBid->Enabled = true;
        }
        return $this->txtRegularBid;
    }

    /**
     * Create and init regular bid textbox control for live auction
     * @return QTextBox
     *
     */
    protected function createRegularBidLiveTextbox(): QTextBox
    {
        if (!$this->txtRegularBid) {
            $controlId = $this->getControlId(self::CONTROL_TXT_REGULAR_BID);
            $this->txtRegularBid = new QTextBox($this->objParentControl, $controlId);
            $this->txtRegularBid->AddCssClass('live-regularbid');
            $this->txtRegularBid->AddAction(
                new QClickEvent(),
                new QJavaScriptAction("this.select();this.setSelectionRange(0,9999);this.contentEditable=true;")
            );
        }
        if (!$this->isKeepRegularBid) {
            $this->txtRegularBid->Text = isset($this->myMaxBid)
                ? $this->getNumberFormatter()->formatMoneyNto($this->myMaxBid) : null;
            $this->txtRegularBid->Enabled = true;
        }
        $auctionLotStatusPureChecker = $this->getAuctionLotStatusPureChecker();
        if ($this->isAmongWonStatuses()) {
            $isSoldConditional = Floating::gt($this->reservePrice, $this->hammerPrice)
                && $this->isConditionalSales;
            if ($isSoldConditional) {
                $text = $this->translate('SOLDCONDITIONAL');
            } else {
                $text = $this->translate('SOLD');
            }
            $this->txtRegularBid->Text = $text;
            $this->txtRegularBid->Enabled = false;
        } elseif ($auctionLotStatusPureChecker->isUnsold($this->LotStatusId)) {
            $this->txtRegularBid->Text = 'Closed!';
            $this->txtRegularBid->Enabled = false;
        }
        return $this->txtRegularBid;
    }

    /**
     * Create place bid button for live/timed auction lot
     * @return Button
     *
     */
    protected function createRegularBidButton(): Button
    {
        if (!$this->btnRegularBid) {
            $controlId = $this->getControlId(self::CONTROL_BTN_REGULAR_BID);
            $this->btnRegularBid = Qform_ComponentFactory::new()
                ->createButton($this->objParentControl, $controlId);
            $this->btnRegularBid->ActionParameter = $this->auctionLotId;
            $maxBidId = 'tmbid' . $this->auctionLotId;
            $multiplier = $this->cfg()->get('core->bidding->highBidWarningMultiplier');
            $hiddenAskingBidId = 'hidVisAskBid' . $this->auctionLotId;
            $langInvalidBid = $this->getTranslator()->translateByAuctionReverse('GENERAL_INVALID_MAXBID', 'general', $this->isAuctionReverse);
            $jsIsUsNumberFormatting = $this->getJsIsUsNumberFormatting();
            $jsBidValidator = "if(!sam.isBidValidLive(this, '{$langInvalidBid}', '{$this->auctionLotId}', '#{$maxBidId}', {$jsIsUsNumberFormatting})) return false; ";
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            if ($auctionStatusPureChecker->isTimed($this->auctionType)) {
                $excessiveBidCheckJs = "if(!sam.excessiveBidChecker.checkSingleBid("
                    . "this, '{$maxBidId}', null, '{$hiddenAskingBidId}', {$multiplier}, {$jsIsUsNumberFormatting})) return false;";
            } else {
                $assumedAskingBid = $this->getAssumedAskingBidLiveCalculator()->calc(
                    (float)$this->askingBid,
                    $this->MyMaxBid,
                    (float)$this->StartingBid,
                    (bool)$this->AbsenteeNotification,
                    $this->AbsenteeBidsDisplay
                );
                $excessiveBidCheckJs = "if(!sam.excessiveBidChecker.checkSingleBid("
                    . "this, '{$maxBidId}', {$assumedAskingBid}, '', {$multiplier}, {$jsIsUsNumberFormatting})) return false;";
            }
            $jsCode = $jsBidValidator . $excessiveBidCheckJs;
            $this->btnRegularBid->AddAction(new QClickEvent(), new QJavaScriptAction($jsCode));

            if ($this->isConfirmTimedBid) {
                $this->btnRegularBid->AddAction(
                    new QClickEvent(),
                    new QServerAction($this->regularBidMethodName)
                );
            } else {
                $this->addInlineConfirm($this->btnRegularBid);
                $icoWait = $this->createWaitIcon();
                $this->btnRegularBid->AddAction(
                    new QClickEvent(),
                    new QAjaxAction($this->regularBidMethodName, $icoWait)
                );
            }
        }
        $regularBid = $this->myMaxBid
            ? $this->translate('CHANGEBID')
            : $this->translate('PLACEBID');
        $this->btnRegularBid->Text = $regularBid . $this->getQuantityXMoneyOnButton();
        if ($this->isBidPossible()) {
            $this->btnRegularBid->Visible = false;
        }
        return $this->btnRegularBid;
    }

    /**
     * Create label for error message for regular bid
     * @return QLabel
     */
    protected function createRegularBidErrorLabel(): QLabel
    {
        if (!$this->lblRegularBidError) {
            $controlId = $this->getControlId(self::CONTROL_LBL_REGULAR_BID_ERROR);
            $this->lblRegularBidError = new QLabel($this->objParentControl, $controlId);
            $this->lblRegularBidError->CssClass = 'warning';
        }
        return $this->lblRegularBidError;
    }

    /**
     * Add inline confirmation js to button
     * @param Button $btnBid
     */
    protected function addInlineConfirm(Button $btnBid): void
    {
        if ($this->isInlineBidConfirm) {
            $label = $this->translate('CONFIRM_PLACE_BID');
            $countDown = $this->cfg()->get('core->bidding->inlineConfirmTimeout');
            $currency = $this->getCurrencyLoader()->detectDefaultSign($this->getAuctionId());
            $jsIsUsNumberFormatting = $this->getJsIsUsNumberFormatting();
            $js = "if(!sam.inlineConfirm(this, '{$label}', {$countDown}, true, '{$currency}', {$jsIsUsNumberFormatting})) return false;";
            $btnBid->AddAction(
                new QClickEvent(),
                new QJavaScriptAction($js)
            );
        }
    }

    /**
     * Return quantity text rendered on buttons, i.e. "x15"
     * @return string
     */
    protected function getQuantityXMoneyOnButton(): string
    {
        $qty = '';
        $isQuantityXMoneyEffective = LotQuantityPureCalculator::new()
            ->isQuantityXMoneyEffective($this->quantity, $this->quantityScale, $this->isQuantityXMoney);
        if ($isQuantityXMoneyEffective) {
            $quantityFormatted = $this->getLotAmountRenderer()->makeQuantity($this->quantity, $this->quantityScale);
            $qty = ' x' . $quantityFormatted;
        }
        return $qty;
    }

    /**
     * Return html for force bid block
     * @return string
     *
     */
    protected function getForceBidHtml(): string
    {
        $output = '';
        if (
            $this->isAllowForceBid
            && !$this->isAmongWonStatuses()
        ) {
            $txtForceBid = $this->createForceBidTextbox();
            $btnForceBid = $this->createForceBidButton();
            $lblForceBidError = $this->createForceBidErrorLabel();
            $action = $this->getCurrencyDecorated()
                . $txtForceBid->RenderWithError(false)
                . $btnForceBid->Render(false);
            $error = $lblForceBidError->Render(false);
            $output = $this->decorateWithDiv($action, self::BLOCK_FORCE_BID_ACTION)
                . $this->decorateWithDiv($error, self::BLOCK_FORCE_BID_ERROR);
            $output = $this->decorateWithDiv($output, self::BLOCK_FORCE_BID);
        }
        return $output;
    }

    /**
     * Create "force bid" textbox
     * @return QTextBox
     */
    protected function createForceBidTextbox(): QTextBox
    {
        if (!$this->txtForceBid) {
            $controlId = $this->getControlId(self::CONTROL_TXT_FORCE_BID);
            $this->txtForceBid = new QTextBox($this->objParentControl, $controlId);
            $this->txtForceBid->AddCssClass('timed-forcebid');
            $this->txtForceBid->AddAction(
                new QClickEvent(),
                new QJavaScriptAction("this.select();this.setSelectionRange(0,9999);this.contentEditable=true;")
            );
        }
        return $this->txtForceBid;
    }

    /**
     * Create "force bid" button
     * @return QButton
     */
    protected function createForceBidButton(): QButton
    {
        if (!$this->btnForceBid) {
            $maxBidId = 'tmbid' . $this->auctionLotId;
            $multiplier = $this->cfg()->get('core->bidding->highBidWarningMultiplier');
            $hiddenAskingBidId = 'hidVisAskBid' . $this->auctionLotId;
            $jsIsUsNumberFormatting = $this->getJsIsUsNumberFormatting();
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            if ($auctionStatusPureChecker->isTimed($this->auctionType)) {
                $excessiveBidCheckJs = "if(!sam.excessiveBidChecker.checkSingleBid("
                    . "this, '{$maxBidId}', null, '{$hiddenAskingBidId}', {$multiplier}, {$jsIsUsNumberFormatting})) return false;";
            } else {
                $excessiveBidCheckJs = "if(!sam.excessiveBidChecker.checkSingleBid("
                    . "this, '{$maxBidId}', {$this->askingBid}, '', {$multiplier}, {$jsIsUsNumberFormatting})) return false;";
            }
            $controlId = $this->getControlId(self::CONTROL_BTN_FORCE_BID);
            $this->btnForceBid = Qform_ComponentFactory::new()
                ->createButton($this->objParentControl, $controlId);
            $this->btnForceBid->Text = $this->translate('FORCE_BID') . $this->getQuantityXMoneyOnButton();
            $this->btnForceBid->ActionParameter = $this->auctionLotId;
            $this->btnForceBid->AddAction(new QClickEvent(), new QJavaScriptAction($excessiveBidCheckJs));
            // use the regular bid-timed button's action
            if ($this->isConfirmTimedBid) {
                $this->btnForceBid->AddAction(
                    new QClickEvent(),
                    new QServerAction($this->forceBidMethodName)
                );
            } else {
                $this->addInlineConfirm($this->btnForceBid);
                $icoWait = $this->createWaitIcon();
                $this->btnForceBid->AddAction(
                    new QClickEvent(),
                    new QAjaxAction($this->forceBidMethodName, $icoWait)
                );
            }
        }
        if ($this->isBidPossible()) {
            $this->btnForceBid->Visible = false;
        }
        return $this->btnForceBid;
    }

    /**
     * Create label for error message for forced bid
     * @return QLabel
     */
    protected function createForceBidErrorLabel(): QLabel
    {
        if (!$this->lblForceBidError) {
            $controlId = $this->getControlId(self::CONTROL_LBL_FORCE_BID_ERROR);
            $this->lblForceBidError = new QLabel($this->objParentControl, $controlId);
            $this->lblForceBidError->CssClass = 'warning';
        }
        return $this->lblForceBidError;
    }

    /**
     * Create and render next bid button (for timed auctions only)
     * @return string
     */
    public function getNextBidHtml(): string
    {
        $output = '';
        if ($this->isNextBidAvailable()) {
            $btnNextBid = $this->createNextBidButton();
            if (!$btnNextBid) {
                return '';
            }
            $lblNextBidError = $this->createNextBidErrorLabel();
            $action = $btnNextBid->Render(false);
            $error = $lblNextBidError->Render(false);
            $output = $this->decorateWithDiv($action, self::BLOCK_NEXT_BID_ACTION)
                . $this->decorateWithDiv($error, self::BLOCK_NEXT_BID_ERROR);
            $output = $this->decorateWithDiv($output, self::BLOCK_NEXT_BID);
        }
        return $output;
    }

    /**
     * Create next bid button
     * @return Button|null
     */
    protected function createNextBidButton(): ?Button
    {
        //create the next bid button
        if (!$this->btnNextBid) {
            $controlId = $this->getControlId(self::CONTROL_BTN_NEXT_BID);
            $this->btnNextBid = Qform_ComponentFactory::new()
                ->createButton($this->objParentControl, $controlId);
            $this->btnNextBid->ActionParameter = $this->auctionLotId;    // TODO: possibly we need to unify and always pass the same parameter

            if ($this->isConfirmTimedBid) {
                $this->btnNextBid->AddAction(
                    new QClickEvent(),
                    new QServerAction($this->nextBidMethodName)
                );
            } else {
                $this->addInlineConfirm($this->btnNextBid);
                $icoWait = $this->createWaitIcon();
                $this->btnNextBid->AddAction(
                    new QClickEvent(),
                    new QAjaxAction($this->nextBidMethodName, $icoWait)
                );
                $this->btnNextBid->blockMultipleClick();
            }
        }

        $isHighBidder = false;
        $auction = $this->getAuction();
        if (!$auction) {
            log_error(
                "Available auction not found, when rendering next bid button html"
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return null;
        }
        // checking if user is high bidder
        if (
            $this->currentBidderId
            && $this->currentBidderId === $this->userId
            && !RegularBidPureChecker::new()->checkBidToCurrentMaxBid((float)$this->myMaxBid, (float)$this->currentMaxBid, $auction)
        ) {
            $isHighBidder = true;
        }
        /* SAM-3289
        $isClosed = $this->intDateAssignmentStrategy != Constants\Auction::TDO_INDEPENDENT
            ? $this->blnAuctionClosed : false; */
        $isClosed = $this->isAuctionClosed;
        $isEnabled = !$this->isAmongWonStatuses()
            && !$isClosed
            && !$isHighBidder;
        $this->btnNextBid->Enabled = $isEnabled;

        $this->btnNextBid->Text = $this->translate('NEXTBID_BUTTON_TEXT') . ' ' .
            $this->getCurrencySign() . $this->getNumberFormatter()->formatMoney($this->askingBid) .
            $this->getQuantityXMoneyOnButton();

        // reverse auction case
        if ($this->askingBid === null) {
            $this->btnNextBid->Visible = false;
        }

        return $this->btnNextBid;
    }

    /**
     * Create label for error message for next bid
     * @return QLabel
     */
    protected function createNextBidErrorLabel(): QLabel
    {
        if (!$this->lblNextBidError) {
            $controlId = $this->getControlId(self::CONTROL_LBL_NEXT_BID_ERROR);
            $this->lblNextBidError = new QLabel($this->objParentControl, $controlId);
            $this->lblNextBidError->CssClass = 'warning';
        }
        return $this->lblNextBidError;
    }

    /**
     * Render html for general error block (for errors not related to any bidding button)
     * @return string
     *
     */
    protected function getGeneralErrorHtml(): string
    {
        $lblGeneralError = $this->createGeneralErrorLabel();
        $error = $lblGeneralError->Render(false);
        $output = $this->decorateWithDiv($error, self::BLOCK_GENERAL_ERROR);
        return $output;
    }

    /**
     * Create label for error message for next bid
     * @return QLabel
     */
    protected function createGeneralErrorLabel(): QLabel
    {
        if (!$this->lblGeneralError) {
            $controlId = $this->getControlId(self::CONTROL_LBL_GENERAL_ERROR);
            $this->lblGeneralError = new QLabel($this->objParentControl, $controlId);
            $this->lblGeneralError->CssClass = 'warning';
        }
        return $this->lblGeneralError;
    }

    /**
     * Return html for "buy now" block
     * @return string
     */
    public function getBuyNowHtml(): string
    {
        $output = '';
        if ($this->isBuyNowAvailable()) {
            $qty = $this->getQuantityXMoneyOnButton();
            $controlId = $this->getControlId(self::CONTROL_BTN_BUY_NOW);
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionLotUrlConfig::new()->forRedirect(
                    Constants\Url::P_AUCTIONS_CONFIRM_BUY,
                    $this->lotItemId,
                    $this->getAuctionId()
                )
            );
            $buyNow = '<input type="button" id="' . $controlId . '" ' .
                'class="button" value="' . $this->translate('TABLE_BUYNOW') . $qty . '" ' .
                'onclick="sam.redirect(\'' . $url . '\');" />';
            $output = $this->decorateWithDiv($buyNow, self::BLOCK_BUY_NOW);
        }
        return $output;
    }

    /**
     * Create hidden input field to store visible for user asking bid,
     * it is used for next bid and failed bid checking (SAM-1065)
     * @return string
     *
     */
    protected function getHiddenAskingBidHtml(): string
    {
        if (!$this->hidVisAskBid) {
            $controlId = $this->getControlId(self::CONTROL_HID_VISIBLE_ASKING_BID);
            $this->hidVisAskBid = new QHiddenInput($this->objParentControl, $controlId);
        }
        if (
            $this->askingBid === null
            && $this->currentBidAmount === null
            && $this->startingBid > 0
        ) {
            $this->hidVisAskBid->Text = $this->startingBid;
        } else {
            $this->hidVisAskBid->Text = $this->askingBid;
        }
        $output = $this->hidVisAskBid->Render(false);
        return $output;
    }

    /**
     * Return html for ended lot block
     * @return string
     */
    protected function getClosedLotHtml(): string
    {
        $output = '';
        $output .= $this->getClosedMessageHtml();
        $output .= $this->getBuyNowHtml();
        if ($this->isAuctionReadyUser()) {
            $output .= $this->getViewDetailsHtml();
        }
        $output .= $this->getBiddingHistoryLink();
        $output = $this->decorateWithDiv($output, self::BLOCK_CLOSED_LOT);
        return $output;
    }

    /**
     * Return html for lot closed message
     * @return string
     */
    public function getClosedMessageHtml(): string
    {
        $output = $this->translate('CLOSED');
        $output = $this->decorateWithDiv($output, self::BLOCK_CLOSED_MESSAGE);
        return $output;
    }

    /**
     * Return html for bidding status (Outbid / High bidder)
     * @return string
     */
    protected function getBiddingStatusHtml(): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLiveOrHybrid($this->auctionType)) {
            $output = $this->getBiddingStatusLiveHtml();
        } else {
            $output = $this->getBiddingStatusTimedHtml();
        }
        return $output;
    }

    /**
     * Return html for bidding status (Outbid / High bidder) for timed auction
     * @return string
     */
    protected function getBiddingStatusTimedHtml(): string
    {
        $output = '';
        $tpl = '<div id="%s" class="%s">%s</div>';
        $controlId = $this->getControlId(self::CONTROL_BLK_BIDDING_STATUS);
        if (
            $this->currentBidderId
            && $this->currentBidAmount
        ) {
            if ($this->currentBidderId === $this->userId) {
                $message = $this->getTranslator()
                    ->translateByAuctionReverse('GENERAL_YOUR_HIGH_BID', 'general', $this->isAuctionReverse);
                $reserveCheckResult = $this->createLotReservePriceChecker()
                    ->setLotItemId($this->lotItemId)
                    ->setAuction($this->getAuction())
                    ->check();
                if ($reserveCheckResult === LotReservePriceChecker::NOT_MET) {
                    $message = $this->getTranslator()
                        ->translateByAuctionReverse(
                            'GENERAL_YOUR_HIGH_BID_BELOW_RESERVE',
                            'general',
                            $this->isAuctionReverse
                        );
                }
                $output = sprintf($tpl, $controlId, 'youre-winning', $message);
            } elseif ($this->myMaxBid) {
                $output = sprintf($tpl, $controlId, 'outbid', $this->translate('OUTBID'));
            }
        } else {
            $output = sprintf($tpl, $controlId, '', '');
        }
        $output = $this->decorateWithDiv($output, self::BLOCK_BIDDING_STATUS);
        return $output;
    }

    /**
     * Return html for bidding status (Outbid / High bidder) for live auction
     * @return string
     */
    protected function getBiddingStatusLiveHtml(): string
    {
        $auction = $this->getAuction();
        if (!$auction) {
            log_error(
                "Available auction not found, when rendering bidding status live html"
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return '';
        }
        $output = '';
        $tpl = '<div id="%s" class="%s">%s</div>';
        $controlId = $this->getControlId(self::CONTROL_BLK_BIDDING_STATUS);
        if (
            Floating::gt($this->currentMaxBid, 0)
            && $this->getAuctionStatusChecker()->isAccessOutbidWinningInfo($auction)
        ) {
            if ($this->currentBidderId === $this->userId) {
                $message = $this->getTranslator()
                    ->translateByAuctionReverse('GENERAL_YOUR_HIGH_BID', 'general', $this->isAuctionReverse);
                $reserveCheckResult = $this->createLotReservePriceChecker()
                    ->setLotItemId($this->lotItemId)
                    ->setAuction($auction)
                    ->check();
                if (
                    $auction->ReserveNotMetNotice
                    && $reserveCheckResult === LotReservePriceChecker::NOT_MET
                ) {
                    $message = $this->getTranslator()
                        ->translateByAuctionReverse(
                            'GENERAL_YOUR_HIGH_BID_BELOW_RESERVE',
                            'general',
                            $this->isAuctionReverse
                        );
                }
                $output = sprintf($tpl, $controlId, 'youre-winning', $message);
            } elseif ($this->myMaxBid) {
                $output = sprintf($tpl, $controlId, 'outbid', $this->translate('OUTBID'));
            }
        } else {
            $output = sprintf($tpl, $controlId, '', '');
        }
        $output = $this->decorateWithDiv($output, self::BLOCK_BIDDING_STATUS);
        return $output;
    }

    /**
     * Return html of rendered wait icon
     * @return string
     *
     */
    protected function getWaitIconHtml(): string
    {
        $icoWait = $this->createWaitIcon();
        $output = $icoWait->Render(false);
        return $output;
    }

    /**
     * Create wait icon control
     * @return QWaitIcon
     */
    protected function createWaitIcon(): QWaitIcon
    {
        if (!$this->icoWait) {
            $controlId = $this->getControlId(self::CONTROL_ICO_WAIT);
            $this->icoWait = new QWaitIcon($this->objParentControl, $controlId);
        }
        return $this->icoWait;
    }

    /**
     * Return html for "view details" link
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
        $viewDetails =
            '<a href="' . $url . '">' .
            $this->translate('VIEWDETAILS') .
            '</a>';
        $output = $this->decorateWithDiv($viewDetails, self::BLOCK_VIEW_DETAILS);
        return $output;
    }

    /**
     * Return html for invoice link
     * @return string
     */
    protected function getInvoiceHtml(): string
    {
        $output = '';
        if ($this->invoiceId && (!$this->isListingOnly && !$this->isAuctionListingOnly)) {
            $url = $this->getUrlBuilder()->build(
                ResponsiveInvoiceViewUrlConfig::new()->forWeb($this->invoiceId)
            );
            $output = sprintf('<a href="%s">%s</a>', $url, $this->translate('INVOICELINK'));
        }
        $output = $this->decorateWithDiv($output, self::BLOCK_INVOICE);
        return $output;
    }

    /**
     * Return html for settlement link
     * @return string
     */
    protected function getSettlementHtml(): string
    {
        $output = '';
        if (
            $this->settlementId
            && !$this->isListingOnly
            && !$this->isAuctionListingOnly
        ) {
            $url = $this->getUrlBuilder()->build(
                ResponsiveSettlementViewUrlConfig::new()->forWeb(
                    $this->settlementId,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $this->AccountId]
                )
            );
            $output = sprintf('<a href="%s">%s</a>', $url, $this->translate('SETTLEMENTSLINK'));
        }
        $output = $this->decorateWithDiv($output, self::BLOCK_SETTLEMENT);
        return $output;
    }

    /**
     * Return html for "bidding history" link block
     * @return string
     */
    protected function getBiddingHistoryLink(): string
    {
        $output = '';
        $class = '';
        $linkHtml = '';

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $isTimed = $auctionStatusPureChecker->isTimed($this->auctionType);
        if ($isTimed) {
            $auctionId = $this->getAuctionId();
        } else {
            $auctionId = $this->getAuctionIdForBiddingHistoryLink();
        }
        if ($auctionId) {
            $biddingHistoryUrl = $this->getUrlBuilder()->build(
                ResponsiveBiddingHistoryUrlConfig::new()->forWeb($this->lotItemId, $auctionId)
            );
            if ($this->bidCount > 1) {
                $bids = $isTimed
                    ? '(<span>' . $this->bidCount . '</span> ' . $this->translate('BIDS') . ')' : '';
                $linkHtml = $this->translate('BIDDINGHISTORY') . $bids;
            } elseif ($this->bidCount === 1) {
                $bid = $isTimed
                    ? '(<span>' . $this->bidCount . '</span> ' . $this->translate('BID') . ')' : '';
                $linkHtml = $this->translate('BIDDINGHISTORY') . $bid;
            } else {
                $class = 'hidden';
            }
            $output =
                '<a href="' . $biddingHistoryUrl . '" class="biddingHistoryLink ' . $class . '" data-id="' . $this->auctionLotId . '">' . $linkHtml . '</a>';
            $output = $this->decorateWithDiv($output, self::BLOCK_BID_HISTORY);
        }
        return $output;
    }

    /**
     * Return auction id, which should be used for "Bidding history" link.
     * null means, that link should not be rendered
     * @return int|null
     */
    protected function getAuctionIdForBiddingHistoryLink(): ?int
    {
        $auctionId = null;
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (
            $this->isLotBiddingHistoryAllowed
            && $auctionStatusPureChecker->isLiveOrHybrid($this->auctionType)
        ) {
            if ($this->getAuctionLotStatusPureChecker()->isActive($this->lotStatusId)) {
                $rtbCurrent = $this->getRtbLoader()->loadByAuctionId($this->getAuctionId());
                if (
                    $rtbCurrent
                    && $rtbCurrent->LotItemId === $this->lotItemId
                ) {
                    $auctionId = $this->getAuctionId();
                }
            } elseif ($this->isWinningBidView) {
                $auctionId = $this->getAuctionId();
            }
        }
        return $auctionId;
    }

    /**
     * Return html for absentee bidders notification block
     * @return string
     */
    protected function getAbsenteeNotification(): string
    {
        $output = '';
        if ($this->absenteeNotification) {
            $output = $this->absenteeNotification;
            $output = $this->decorateWithDiv($output, self::BLOCK_ABSENTEE_NOTIFICATION);
        }
        return $output;
    }

    /**
     * @return string represents JS boolean value for importing to front-end code
     */
    protected function getJsIsUsNumberFormatting(): string
    {
        $jsIsUsNumberFormatting = $this->isUsNumberFormatting ? 'true' : 'false';
        return $jsIsUsNumberFormatting;
    }

    protected function getLotAmountRenderer(): LotAmountRendererInterface
    {
        if ($this->lotAmountRenderer === null) {
            $this->lotAmountRenderer = LotAmountRendererFactory::new()->create($this->AccountId);
        }
        return $this->lotAmountRenderer;
    }

    /** ***************
     * Setters/getters
     */

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        try {
            switch ($name) {
                case 'AbsenteeBidsDisplay':
                    return $this->absenteeBidsDisplay = (string)$value;

                case 'AbsenteeNotification':
                    return $this->absenteeNotification = (string)$value;

                case 'AccountId':
                    return $this->accountId = (int)$value;

                case 'AllowForceBid':
                    return $this->isAllowForceBid = (bool)$value;

                case 'Approved':
                    return $this->isApproved = (bool)$value;

                case 'AskingBid':
                    return $this->askingBid = (float)$value;

                case 'AuctionBidder':
                    if (!$value instanceof AuctionBidder) {
                        throw new QInvalidCastException("Is not instance of AuctionBidder class");
                    }
                    return $this->auctionBidder = $value;

                case 'AuctionClosed':
                    return $this->isAuctionClosed = (bool)$value;

                case 'AuctionListingOnly':
                    return $this->isAuctionListingOnly = (bool)$value;

                case 'AuctionLotCache':
                    if (!$value instanceof AuctionLotItemCache) {
                        throw new QInvalidCastException("Is not instance of AuctionLotItemCache class");
                    }
                    return $this->auctionLotCache = $value;

                case 'AuctionLotId':
                    return $this->auctionLotId = (int)$value;

                case 'AuctionReverse':
                    return $this->isAuctionReverse = (bool)$value;

                case 'AuctionStatusId':
                    return $this->auctionStatusId = (int)$value;

                case 'AuctionType':
                    return $this->auctionType = (string)$value;

                case 'BidCount':
                    return $this->bidCount = (int)$value;

                case 'BiddingPaused':
                    return $this->isBiddingPaused = (bool)$value;

                case 'BidderTermsAreAgreed':
                    return $this->isBidderTermsAreAgreed = $value;

                case 'BuyNowAmount':
                    return $this->buyNowAmount = (float)$value;

                case 'BuyNowRestriction':
                    return $this->buyNowRestriction = (string)$value;

                case 'BuyNowUnsold':
                    return $this->isBuyNowUnsold = (bool)$value;

                case 'ConditionalSales':
                    return $this->isConditionalSales = (bool)$value;

                case 'ConfirmTimedBid':
                    return $this->isConfirmTimedBid = (bool)$value;

                case 'CurrentBid':
                    return $this->currentBidAmount = (float)$value;

                case 'CurrentBidderId':
                    return $this->currentBidderId = (int)$value;

                case 'CurrentMaxBid':
                    return $this->currentMaxBid = (float)$value;

                case 'CurrentTransactionBid':
                    return $this->currentTransactionBid = (float)$value;

                case 'EndDate':
                    if (!$value instanceof DateTime) {
                        throw new QInvalidCastException("Is not instance of \DateTime class");
                    }
                    return $this->endDate = $value;

                case 'EventType':
                    return $this->eventType = (int)$value;

                case 'ForceBidMethodName':
                    return $this->forceBidMethodName = (string)$value;

                case 'HammerPrice':
                    return $this->hammerPrice = Cast::toFloat($value);

                case 'InlineBidConfirm':
                    return $this->isInlineBidConfirm = (bool)$value;

                case 'InvoiceId':
                    return $this->invoiceId = (int)$value;

                case 'IsBidder':
                    return $this->isBidder = (bool)$value;

                case 'KeepRegularBid':
                    return $this->isKeepRegularBid = (bool)$value;

                case 'ListingOnly':
                    return $this->isListingOnly = (bool)$value;

                case 'LotBiddingHistoryAllowed':
                    return $this->isLotBiddingHistoryAllowed = (bool)$value;

                case 'LotBiddingInfoAllowed':
                    return $this->isLotBiddingInfoAllowed = (bool)$value;

                case 'LotChanges':
                    return $this->lotChanges = (string)$value;

                case 'LotId':
                    return $this->lotItemId = (int)$value;

                case 'LotNum':
                    return $this->lotNum = (int)$value;

                case 'LotStatusId':
                    return $this->lotStatusId = (int)$value;

                case 'MyMaxBid':
                    return $this->myMaxBid = (float)$value;

                case 'NextBidEnabled':
                    return $this->isNextBidEnabled = (bool)$value;

                case 'NoBidding':
                    return $this->isNoBidding = (bool)$value;

                case 'NextBidMethodName':
                    return $this->nextBidMethodName = (string)$value;

                case 'RegularBidMethodName':
                    return $this->regularBidMethodName = (string)$value;

                case 'Quantity':
                    return $this->quantity = (float)$value;

                case 'QuantityScale':
                    return $this->quantityScale = (int)$value;

                case 'QuantityXMoney':
                    return $this->isQuantityXMoney = (bool)$value;

                case 'Registered':
                    return $this->isRegistered = (bool)$value;

                case 'RequireLotChangeConfirmation':
                    return $this->isRequireLotChangeConfirmation = (bool)$value;

                case 'ReserveNotMetNotice':
                    return $this->isReserveNotMetNotice = (bool)$value;

                case 'ReserveMetNotice':
                    return $this->isReserveMetNotice = (bool)$value;

                case 'ReservePrice':
                    return $this->reservePrice = (float)$value;

                case 'RtbCurrentLotId':
                    return $this->rtbCurrentLotId = (int)$value;

                case 'SeoUrl':
                    return $this->seoUrl = (string)$value;

                case 'SettlementId':
                    return $this->settlementId = (int)$value;

                case 'StartDate':
                    if (!$value instanceof DateTime) {
                        throw new QInvalidCastException("Is not instance of \DateTime class");
                    }
                    return $this->startDate = $value;

                case 'StartingBid':
                    return $this->startingBid = (float)$value;

                case 'TermsAndConditions':
                    return $this->termsAndConditions = (string)$value;

                case 'DateAssignmentStrategy':
                    return $this->dateAssignmentStrategy = (int)$value;

                case 'UserEmailVerified':
                    return $this->isUserEmailVerified = (bool)$value;

                case 'UsNumberFormatting':
                    return $this->isUsNumberFormatting = (bool)$value;

                case 'UserFlag':
                    return $this->userFlag = (int)$value;

                case 'UserId':
                    return $this->userId = (int)$value;

                case 'UserInfo':
                    if (!$value instanceof UserInfo) {
                        throw new QInvalidCastException("Is not instance of UserInfo class");
                    }
                    return $this->userInfo = $value;

                case 'VerifyEmail':
                    return $this->isVerifyEmail = (bool)$value;

                case 'VerifyMethodName':
                    return $this->verifyMethodName = (string)$value;

                case 'WinningAuctionId':
                    return $this->winningAuctionId = (int)$value;

                case 'WinningBidView':
                    return $this->isWinningBidView = (bool)$value;

                default:
                    return parent::__set($name, $value);
            }
        } catch (QCallerException $e) {
            $e->IncrementOffset();
            return $e;
        }
    }

    /**
     * @param string $name
     * @return array|mixed|null
     *
     */
    public function __get($name)
    {
        switch ($name) {
            case 'AbsenteeBidsDisplay':
                return $this->absenteeBidsDisplay;
            case 'AbsenteeNotification':
                return $this->absenteeNotification;
            case 'AccountId':
                return $this->accountId;
            case 'AllowForceBid':
                return $this->isAllowForceBid;
            case 'Approved':
                return $this->isApproved;
            case 'AskingBid':
                return $this->askingBid;
            case 'AuctionBidder':
                return $this->auctionBidder;
            case 'AuctionClosed':
                return $this->isAuctionClosed;
            case 'AuctionLotCache':
                return $this->auctionLotCache;
            case 'AuctionLotId':
                return $this->auctionLotId;
            case 'AuctionReverse':
                return $this->isAuctionReverse;
            case 'AuctionStatusId':
                return $this->auctionStatusId;
            case 'AuctionType':
                return $this->auctionType;
            case 'BidCount':
                return $this->bidCount;
            case 'BiddingPaused':
                return $this->isBiddingPaused;
            case 'BidderTermsAreAgreed':
                return $this->isBidderTermsAreAgreed;
            case 'BuyNowAmount':
                return $this->buyNowAmount;
            case 'BuyNowRestriction':
                return $this->buyNowRestriction;
            case 'BuyNowUnsold':
                return $this->isBuyNowUnsold;
            case 'ConditionalSales':
                return $this->isConditionalSales;
            case 'ConfirmTimedBid':
                return $this->isConfirmTimedBid;
            case 'CurrentBid':
                return $this->currentBidAmount;
            case 'CurrentBidderId':
                return $this->currentBidderId;
            case 'CurrentMaxBid':
                return $this->currentMaxBid;
            case 'CurrentTransactionBid':
                return $this->currentTransactionBid;
            case 'EndDate':
                return $this->endDate;
            case 'EventType':
                return $this->eventType;
            case 'ForceBidMethodName':
                return $this->forceBidMethodName;
            case 'HammerPrice':
                return $this->hammerPrice;
            case 'InlineBidConfirm':
                return $this->isInlineBidConfirm;
            case 'InvoiceId':
                return $this->invoiceId;
            case 'IsBidder':
                return $this->isBidder;
            case 'KeepRegularBid':
                return $this->isKeepRegularBid;
            case 'ListingOnly':
                return $this->isListingOnly;
            case 'LotBiddingInfoAllowed':
                return $this->isLotBiddingInfoAllowed;
            case 'LotBiddingHistoryAllowed':
                return $this->isLotBiddingHistoryAllowed;
            case 'LotChanges':
                return $this->lotChanges;
            case 'LotId':
                return $this->lotItemId;
            case 'LotNum':
                return $this->lotNum;
            case 'LotStatusId':
                return $this->lotStatusId;
            case 'MyMaxBid':
                return $this->myMaxBid;
            case 'NextBidEnabled':
                return $this->isNextBidEnabled;
            case 'NextBidMethodName':
                return $this->nextBidMethodName;
            case 'NoBidding':
                return $this->isNoBidding;
            case 'Quantity':
                return $this->quantity;
            case 'QuantityScale':
                return $this->quantityScale;
            case 'QuantityXMoney':
                return $this->isQuantityXMoney;
            case 'Registered':
                return $this->isRegistered;
            case 'RegularBidMethodName':
                return $this->regularBidMethodName;
            case 'RequireLotChangeConfirmation':
                return $this->isRequireLotChangeConfirmation;
            case 'ReserveNotMetNotice':
                return $this->isReserveNotMetNotice;
            case 'ReserveMetNotice':
                return $this->isReserveMetNotice;
            case 'ReservePrice':
                return $this->reservePrice;
            case 'RtbCurrentLotId':
                return $this->rtbCurrentLotId;
            case 'SeoUrl':
                return $this->seoUrl;
            case 'SettlementId':
                return $this->settlementId;
            case 'StartDate':
                return $this->startDate;
            case 'StartingBid':
                return $this->startingBid;
            case 'TermsAndConditions':
                return $this->termsAndConditions;
            case 'DateAssignmentStrategy':
                return $this->dateAssignmentStrategy;
            case 'UserEmailVerified':
                return $this->isUserEmailVerified;
            case 'UsNumberFormatting':
                return $this->isUsNumberFormatting;
            case 'UserId':
                return $this->userId;
            case 'UserInfo':
                if ($this->userInfo === null) {
                    $this->userInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->userId);
                }
                return $this->userInfo;
            case 'UserFlag':
                return $this->userFlag;
            case 'VerifyEmail':
                return $this->isVerifyEmail;
            case 'VerifyMethodName':
                return $this->verifyMethodName;
            case 'WinningAuctionId':
                return $this->winningAuctionId;
            case 'WinningBidView':
                return $this->isWinningBidView;
            default :
                try {
                    return parent::__get($name);
                } catch (QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }
        }
    }
}
