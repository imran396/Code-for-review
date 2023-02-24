<?php
/**
 * Create a button with all params by different conditions
 * Refactor Live and Timed Lot Details pages of responsive side
 * @see https://bidpath.atlassian.net/browse/SAM-3241
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Jun 14, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Lot\Details\Live\Button;

use AuctionLotItem;
use QAjaxAction;
use QAlertAction;
use QClickEvent;
use Qform_ComponentFactory;
use QJavaScriptAction;
use QLabel;
use QServerAction;
use QWaitIcon;
use RuntimeException;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\AuctionLot\Validate\AuctionLotStatusCheckerCreateTrait;
use Sam\Bidding\ExcessiveBid\ExcessiveAbsenteeBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Qform\AvailableStateDetector;
use Sam\Qform\Button;
use Sam\Qform\Lot\GeneralControlTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class AbsenteeBidCreator
 */
class AbsenteeBidCreator extends CustomizableClass
{
    use AuctionLotStatusCheckerCreateTrait;
    use AuctionStatusCheckerAwareTrait;
    use BackUrlParserAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use ExcessiveAbsenteeBidDetectorCreateTrait;
    use GeneralControlTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use ServerRequestReaderAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

    private const JS_REDIRECT_TMPL = 'sam.redirect(\'%s\');';

    private QLabel|Button|null $btnPlaceBid = null;
    private ?QWaitIcon $icoWait = null;
    /**
     * JS code
     */
    private string $js = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return QWaitIcon|null
     */
    public function getIconWait(): ?QWaitIcon
    {
        return $this->icoWait;
    }

    /**
     * @param QWaitIcon $icoWait
     * @return static
     */
    public function setIconWait(QWaitIcon $icoWait): static
    {
        $this->icoWait = $icoWait;
        return $this;
    }

    /**
     * @return string
     */
    public function getJs(): string
    {
        return $this->js;
    }

    /**
     * @param bool $isPreBiddingActive
     * @param int|null $editorUserId
     * @return Button|QLabel
     */
    public function create(bool $isPreBiddingActive, ?int $editorUserId): Button|QLabel
    {
        if (in_array(AvailableStateDetector::SIGNIN, $this->getAvailableStates(), true)) {
            $this->createForAnonymousUser();
        } else {
            // $this->btnPlaceBid is instance of \QLabel for states:
            // AvailableStateDetector::AUCTION_APPROVAL, AvailableStateDetector::BIDDING_PAUSED
            // for other states it will be instance of \Sam\Qform\Button
            if (!$editorUserId) {
                throw new RuntimeException('Editor user not defined');
            }
            $this->createForUser($isPreBiddingActive, $editorUserId);
        }

        return $this->btnPlaceBid;
    }

    /**
     * @param string $controlId
     * @return Button
     */
    protected function createNewButton(string $controlId): Button
    {
        return Qform_ComponentFactory::new()
            ->createButton($this->getViewContext()->getParentObject(), $controlId);
    }

    /**
     */
    protected function createForAnonymousUser(): void
    {
        if ($this->isLotSold()) {
            $this->createForAnonymousUserAndLotSold();
        } elseif (
            $this->isLotActive()
            && !in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)
        ) {
            $this->createForAnonymousUserAndLotActive();
        } else {
            $this->createForClosed();
        }
    }

    protected function createForAnonymousUserAndLotSold(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $button->Text = $this->getTranslator()
            ->translate('GENERAL_SALECLOSED', 'general', $this->getViewContext()->getSystemAccountId());
        $button->Enabled = false;

        $this->btnPlaceBid = $button;
    }

    /**
     */
    protected function createForAnonymousUserAndLotActive(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $button->Text = $this->getTranslator()
            ->translate('GENERAL_LOGINTOBID', 'general', $this->getViewContext()->getSystemAccountId());
        $loginUrl = $this->createLoginUrlForBtnAnonymousUserAndLotActive();
        $js = sprintf(self::JS_REDIRECT_TMPL, $loginUrl);
        $button->AddAction(new QClickEvent(), new QJavaScriptAction($js));

        $this->btnPlaceBid = $button;
    }

    /**
     * @return string
     */
    private function createLoginUrlForBtnAnonymousUserAndLotActive(): string
    {
        $urlBuilder = $this->getUrlBuilder();
        $auctionId = $this->viewContext->getAuction()->Id;
        $backUrl = $this->getServerRequestReader()->currentUrl();
        $backUrl = $this->getUrlParser()->replaceParams(
            $backUrl,
            [Constants\UrlParam::SALE_REG => $auctionId, Constants\UrlParam::SALE => $auctionId]
        );
        $output = $urlBuilder->build(ResponsiveLoginUrlConfig::new()->forRedirect());
        $output = $this->getBackUrlParser()->replace($output, $backUrl);
        return $output;
    }

    protected function createForClosed(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $button->Text = $this->getTranslator()->translate(
            'CATALOG_LOT_CLOSED',
            'catalog',
            $this->getViewContext()->getSystemAccountId()
        );
        $button->Enabled = false;

        $this->btnPlaceBid = $button;
    }

    /**
     * @return bool
     */
    protected function isLotSold(): bool
    {
        $lotStatus = $this->getViewContext()->getAuctionLot()->LotStatusId;
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        return $auctionLotStatusPureChecker->isAmongWonStatuses($lotStatus);
    }

    /**
     * @return bool
     */
    protected function isLotActive(): bool
    {
        $lotStatus = $this->getViewContext()->getAuctionLot()->LotStatusId;
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        return $auctionLotStatusPureChecker->isActive($lotStatus);
    }

    /**
     * Create Place bid button object ($this->btnPlaceBid) for registered user.
     *
     * $this->btnPlaceBid is instance of \QLabel for states:
     * \Sam\Qform\AvailableStateDetector::AUCTION_APPROVAL, \Sam\Qform\AvailableStateDetector::BIDDING_PAUSED
     * for other states it will be instance of \Sam\Qform\Button
     * @param bool $isPreBiddingActive
     * @param int $editorUserId
     */
    protected function createForUser(bool $isPreBiddingActive, int $editorUserId): void
    {
        $availableStates = $this->getAvailableStates();
        if (in_array(AvailableStateDetector::EMAIL_VERIFICATION, $availableStates, true)) {
            $this->createForEmailVerification();
        } elseif (in_array(AvailableStateDetector::AUCTION_REGISTRATION, $availableStates, true)) {
            $this->createForAuctionRegistration();
        } elseif (in_array(AvailableStateDetector::AUCTION_APPROVAL, $availableStates, true)) {
            $this->createForAuctionApproval(); //$this->btnPlaceBid \QLabel
        } elseif (in_array(AvailableStateDetector::BIDDING_PAUSED, $availableStates, true)) {
            $this->createForBiddingPaused(); //$this->btnPlaceBid \QLabel
        } elseif (in_array(AvailableStateDetector::SPECIAL_TERMS_APPROVAL, $availableStates, true)) {
            $this->createForSpecialTermsApproval();
        } elseif (in_array(AvailableStateDetector::LOT_CHANGES_APPROVAL, $availableStates, true)) {
            $this->createForLotChangesApproval();
        } elseif (in_array(AvailableStateDetector::RESTRICTED_BUYER_GROUP, $availableStates, true)) {
            $this->createForRestrictedBuyerGroup();
        } else {
            $this->createForPlaceBid($isPreBiddingActive, $editorUserId);
        }
    }

    protected function createForEmailVerification(): void
    {
        $button = $this->createNewButton('btnV0');
        $button->blockMultipleClick();
        $button->Text = $this->getTranslator()->translate(
            'GENERAL_VERIFY_ACCT',
            'general',
            $this->getViewContext()->getSystemAccountId()
        );
        $button->ActionParameter = 0;
        $action = $this->viewContext->getMethodNameForEmailVerification();
        $button->AddAction(new QClickEvent(), new QServerAction($action));

        $this->btnPlaceBid = $button;
    }

    protected function createForAuctionRegistration(): void
    {
        if ($this->isLotSold()) {
            $this->createButtonSaleClosed(true);
        } elseif ($this->isLotActive()) {
            $isUserBidder = $this->getViewContext()->isUserBidder();
            if (in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)) {
                $this->createButtonCatalogLotClosed(true);
            } elseif ($isUserBidder) {
                $this->createForAuctionRegistrationLotActiveWithUserBidderPrivilege();
            } else {
                $this->createForAuctionRegistrationLotActiveWithoutUserBidderPrivilege();
            }
        } else {
            $this->createButtonCatalogLotClosed(true);
        }
    }

    protected function createForAuctionApproval(): void
    {
        $button = new QLabel($this->getViewContext()->getParentObject(), $this->getControlId());
        $button->HtmlEntities = false;
        $button->Text = '';

        if ($this->isLotSold()) {
            $button->Text = $this->getTranslator()
                ->translate('GENERAL_SALECLOSED', 'general', $this->getViewContext()->getSystemAccountId());
            $button->Enabled = false;
        }

        if (
            $this->isLotActive()
            && !in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)
        ) {
            $text = $this->getTranslator()
                ->translate('GENERAL_PENDINGAPPROVAL', 'general', $this->getViewContext()->getSystemAccountId());
            $button->Text = "<i>{$text}</i>";
        }

        $this->btnPlaceBid = $button;
    }

    private function createForBiddingPaused(): void
    {
        $button = new QLabel($this->getViewContext()->getParentObject(), $this->getControlId());
        $button->HtmlEntities = false;
        $text = $this->getTranslator()
            ->translate('GENERAL_BIDDING_PAUSED', 'general', $this->getViewContext()->getSystemAccountId());
        $button->Text = <<<HTML
<span class="bidding-paused">{$text}</span>
HTML;
        $button->Enabled = false;

        $this->btnPlaceBid = $button;
    }

    protected function createForSpecialTermsApproval(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $text = $this->getTranslator()
            ->translate('MYITEMS_SPEC_TERMS', 'myitems', $this->getViewContext()->getSystemAccountId());
        $button->Text = $text;
        $action = $this->viewContext->getMethodNameForSpecialTermsApproval();
        $button->AddAction(new QClickEvent(), new QServerAction($action));

        $this->btnPlaceBid = $button;
    }

    protected function createForLotChangesApproval(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $text = $this->getTranslator()
            ->translate('CATALOG_LOT_CHANGES', 'catalog', $this->getViewContext()->getSystemAccountId());
        $button->Text = $text;
        $action = $this->viewContext->getMethodNameForLotChangesApproval();
        $button->AddAction(new QClickEvent(), new QServerAction($action));

        $this->btnPlaceBid = $button;
    }

    protected function createForRestrictedBuyerGroup(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $text = $this->getTranslator()
            ->translate('GENERAL_RESTRICTED_GROUP', 'general', $this->getViewContext()->getSystemAccountId());
        $button->Text = $text;
        $button->Enabled = false;

        $this->btnPlaceBid = $button;
    }

    protected function createForPlaceBid(bool $isPreBiddingActive, int $editorUserId): void
    {
        $isLotActive = $this->isLotActive();
        $isLotSold = $this->isLotSold();
        if (
            $isLotSold
            || (
                $isLotActive
                && in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)
            )
        ) {
            $this->createButtonSaleClosed();
        } elseif ($isLotActive) {
            $this->createForPlaceBidLotActive($isPreBiddingActive, $editorUserId);
        } else {
            $this->createButtonCatalogLotClosed();
        }
    }

    /**
     * Return quantity text rendered on a button, i.e. "x15"
     * @param AuctionLotItem|null $auctionLot
     * @return string
     */
    protected function getQuantityXMoneyOnButton(?AuctionLotItem $auctionLot): string
    {
        $qty = '';
        if (!$auctionLot) {
            return $qty;
        }
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale(
            $auctionLot->LotItemId,
            $auctionLot->AuctionId
        );
        if ($auctionLot->isQuantityXMoneyEffective($quantityScale)) {
            $quantityFormatted = $this->createLotAmountRendererFactory()
                ->create($auctionLot->AccountId)
                ->makeQuantity($auctionLot->Quantity, $quantityScale);
            $qty = ' x' . $quantityFormatted;
        }
        return $qty;
    }

    /**
     * @param bool $isPreBiddingActive
     * @param int $editorUserId
     */
    protected function createForPlaceBidLotActive(bool $isPreBiddingActive, int $editorUserId): void
    {
        $button = $this->createNewButton($this->getControlId());
        $systemAccountId = $this->getViewContext()->getSystemAccountId();

        $textFieldKey = $this->getViewContext()->getUserAbsenteeBid() ? 'GENERAL_CHANGEBID' : 'GENERAL_PLACEBID';
        $text = $this->getTranslator()->translate($textFieldKey, 'general', $systemAccountId);
        $auctionLot = $this->getViewContext()->getAuctionLot();
        $text .= $this->getQuantityXMoneyOnButton($auctionLot);
        $button->Text = $text;

        $jsCode = $this->buildJsForBtnPlaceBidLotActive($editorUserId);
        $button->AddAction(new QClickEvent(), new QJavaScriptAction($jsCode));

        $isConfirmTimed = $this->getViewContext()->isConfirmTimed();
        if ($isConfirmTimed) {
            $methodName = $this->getViewContext()->getMethodNameForPlaceBid();
            $serverAction = new QServerAction($methodName);
            $button->AddAction(new QClickEvent(), $serverAction);
        } else {
            $isInlineBidConfirm = $this->getViewContext()->isInlineBidConfirm();
            if ($isInlineBidConfirm) {
                $label = $this->getTranslator()->translate('CATALOG_CONFIRM_PLACE_BID', 'catalog', $systemAccountId);
                $countDown = $this->cfg()->get('core->bidding->inlineConfirmTimeout');
                $currency = $auctionLot
                    ? $this->getCurrencyLoader()->detectDefaultSign($auctionLot->AuctionId)
                    : '';
                $jsIsNumberFormatting = $this->getJsIsUsNumberFormatting();
                $js = "if(!sam.inlineConfirm(this, '{$label}', {$countDown}, true, '{$currency}', {$jsIsNumberFormatting})) return false;";
                $button->AddAction(new QClickEvent(), new QJavaScriptAction($js));
            }

            $methodName = $this->getViewContext()->getMethodNameForPlaceBid();
            $objWaitIcon = $this->getIconWait() ?: 'default';
            $ajaxAction = new QAjaxAction($methodName, $objWaitIcon);
            $button->AddAction(new QClickEvent(), $ajaxAction);
        }

        $button->Enabled = $isPreBiddingActive;

        $this->btnPlaceBid = $button;
    }

    /**
     * @param bool $blockMultipleClicks
     */
    protected function createButtonCatalogLotClosed(bool $blockMultipleClicks = false): void
    {
        $button = $this->createNewButton($this->getControlId());
        $text = $this->getTranslator()
            ->translate('CATALOG_LOT_CLOSED', 'catalog', $this->getViewContext()->getSystemAccountId());
        $button->Text = $text;
        $button->Enabled = false;
        if ($blockMultipleClicks) {
            $button->blockMultipleClick();
        }

        $this->btnPlaceBid = $button;
    }

    /**
     * @param int $editorUserId
     * @return string
     */
    private function buildJsForBtnPlaceBidLotActive(int $editorUserId): string
    {
        $isReverse = $this->getViewContext()->getAuction()->Reverse;
        $langInvalidBid = $this->getTranslator()->translateByAuctionReverse('GENERAL_INVALID_MAXBID', 'general', $isReverse);
        $langInvalidBid = addslashes($langInvalidBid);
        $auctionLotId = $this->getViewContext()->getAuctionLot()->Id;

        $jsIsNumberFormatting = $this->getJsIsUsNumberFormatting();
        $jsBidValidator = "if(!sam.isBidValid(this, '{$langInvalidBid}', '#tmbid{$auctionLotId}', {$jsIsNumberFormatting})) return false; ";
        $askingBid = $this->detectAssumedAskingBidForExcessiveAmountCheck($editorUserId);
        $multiplier = $this->cfg()->get('core->bidding->highBidWarningMultiplier');
        $excessiveBidCheckJs =
            "if (!sam.excessiveBidChecker.checkSingleBid(this, 'tmbid{$auctionLotId}', {$askingBid}, '', {$multiplier}, {$jsIsNumberFormatting})) return false;";

        $output = $jsBidValidator . $excessiveBidCheckJs;
        return $output;
    }

    /**
     * See, SAM-5229: Outrageous bid alert reveals hidden high absentee bid
     * @param int $editorUserId
     * @return float
     */
    protected function detectAssumedAskingBidForExcessiveAmountCheck(int $editorUserId): float
    {
        $assumedAskingBid = $this->createExcessiveAbsenteeBidDetector()
            ->setAuction($this->getViewContext()->getAuction())
            ->setAuctionLot($this->getViewContext()->getAuctionLot())
            ->setUserAbsenteeBid($this->getViewContext()->getUserAbsenteeBid())
            ->detectAssumedAskingBid($editorUserId);
        return $assumedAskingBid;
    }

    /**
     * @param bool $blockMultipleClicks
     */
    protected function createButtonSaleClosed(bool $blockMultipleClicks = false): void
    {
        $button = $this->createNewButton($this->getControlId());
        $text = $this->getTranslator()
            ->translate('GENERAL_SALECLOSED', 'general', $this->getViewContext()->getSystemAccountId());
        $button->Text = $text;
        $button->Enabled = false;
        if ($blockMultipleClicks) {
            $button->blockMultipleClick();
        }

        $this->btnPlaceBid = $button;
    }

    protected function createForAuctionRegistrationLotActiveWithUserBidderPrivilege(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $text = $this->getTranslator()
            ->translate('GENERAL_REGISTERTOBID', 'general', $this->getViewContext()->getSystemAccountId());
        $button->Text = $text;

        $methodName = $this->getViewContext()->getMethodNameForAuctionRegistration();
        $objWaitIcon = $this->getIconWait() ?: 'default';
        $button->AddAction(new QClickEvent(), new QAjaxAction($methodName, $objWaitIcon));
        $auctionId = $this->getViewContext()->getAuctionId();
        if (!$this->getAuctionStatusChecker()->isRegistrationActive($auctionId)) {
            $button->Enabled = false;
        }
        $this->btnPlaceBid = $button;
    }

    protected function createForAuctionRegistrationLotActiveWithoutUserBidderPrivilege(): void
    {
        $button = $this->createNewButton($this->getControlId());
        $button->blockMultipleClick();
        $sysAccountId = $this->getViewContext()->getSystemAccountId();
        $text = $this->getTranslator()->translate('GENERAL_REGISTERTOBID', 'general', $sysAccountId);
        $button->Text = $text;
        $alertMessage = $this->getTranslator()->translate('GENERAL_NO_BIDDER_PRIVILEGE', 'general', $sysAccountId);
        $button->AddAction(new QClickEvent(), new QAlertAction($alertMessage));
        $auctionId = $this->getViewContext()->getAuctionId();
        if (!$this->getAuctionStatusChecker()->isRegistrationActive($auctionId)) {
            $button->Enabled = false;
        }
        $this->btnPlaceBid = $button;
    }

    /**
     * @return string represents JS boolean value for importing to front-end code
     */
    protected function getJsIsUsNumberFormatting(): string
    {
        $auctionAccountId = $this->getViewContext()->getAuction()->AccountId;
        $isNumberFormatting = $this->getSettingsManager()->get(Constants\Setting::US_NUMBER_FORMATTING, $auctionAccountId);
        $jsIsNumberFormatting = $isNumberFormatting ? 'true' : 'false';
        return $jsIsNumberFormatting;
    }
}
