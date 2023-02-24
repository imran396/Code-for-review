<?php
/**
 * Best Offer functionality for lot detail page.
 * SAM-3446: Refactoring Timed Auction - extract Best Offer
 * https://bidpath.atlassian.net/projects/SAM/issues/SAM-3446
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Oleg Kovalyov
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Sept 25, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Lot\Details;

use AuctionLotItem;
use Email_Template;
use QAjaxAction;
use QClickEvent;
use QEnterKeyEvent;
use QForm;
use Qform_ComponentFactory;
use QJavaScriptAction;
use QLabel;
use QServerAction;
use QTerminateAction;
use QTextBox;
use QWaitIcon;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAuctionLotChangesUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\Closer\TimedCloser;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Bidding\OfferBid\Place\OfferBidSaverCreateTrait;
use Sam\Bidding\TimedOnlineOfferBid\Load\TimedOnlineOfferBidLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\BuyerGroup\Access\LotBuyerGroupAccessHelperAwareTrait;
use Sam\Lot\Date\TimedLotDateDetectorCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Qform\AvailableStateDetector;
use Sam\Qform\Button;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\TimedItemAwareTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class BestOfferHelper
 * @package Sam\Qform\Lot\Details
 * @method AuctionLotItem getAuctionLot(bool $isReadOnlyDb = false)
 */
class BestOfferHelper extends CustomizableClass
{
    use AccountAwareTrait;
    use ApplicationRedirectorCreateTrait;
    use AuctionAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BackUrlParserAwareTrait;
    use CurrencyLoaderAwareTrait;
    use EditorUserAwareTrait;
    use FormStateLongevityAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotBuyerGroupAccessHelperAwareTrait;
    use LotItemAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use OfferBidSaverCreateTrait;
    use ServerRequestReaderAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use TimedItemAwareTrait;
    use TimedLotDateDetectorCreateTrait;
    use TimedOnlineOfferBidLoaderAwareTrait;
    use TimedOnlineOfferBidWriteRepositoryAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserLoaderAwareTrait;

    protected ?QForm $parentObject = null;
    protected ?QTextBox $txtOffer = null;
    protected Button|QLabel|null $btnAcceptOffer = null;
    protected Button|QLabel|null $btnDeclineOffer = null;
    protected Button|QLabel|null $btnMakeOffer = null;
    protected string $errorMessage = '';
    protected string $handleNameForAcceptChanges = '';
    protected string $handleNameForAcceptOffer = '';
    protected string $handleNameForDeclineOffer = '';
    protected string $handleNameForMakeOfferBtn = '';
    protected string $handleNameForRegisterClick = '';
    protected string $handleNameForSpecialTermsClick = '';
    protected string $handleNameForVClick = '';
    protected string $message = '';
    protected ?array $availableStates = null;
    protected ?array $translations = null;
    protected ?QWaitIcon $icoLotWait = null;
    protected ?QWaitIcon $icoOfferWait = null;
    protected ?bool $gaBidTrack = null;
    protected bool $hasError = false;
    protected ?bool $isRestrictedGroup = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param QForm $parentObject
     * @return static
     */
    public function setParentObject(QForm $parentObject): static
    {
        $this->parentObject = $parentObject;
        return $this;
    }

    /**
     * @return QForm
     */
    public function getParentObject(): QForm
    {
        return $this->parentObject;
    }

    /**
     * @return bool
     */
    public function isRestrictedGroup(): bool
    {
        if ($this->isRestrictedGroup === null) {
            $editorUserId = $this->getEditorUserId();
            $this->isRestrictedGroup = $this->getLotBuyerGroupAccessHelper()
                ->isRestrictedBuyerGroup($editorUserId, $this->getLotItemId());
        }
        return $this->isRestrictedGroup;
    }

    /**
     * @return QWaitIcon
     */
    public function getLotWaitIcon(): QWaitIcon
    {
        if ($this->icoLotWait === null) {
            $this->icoLotWait = new QWaitIcon($this->getParentObject(), 'oai1');
        }
        return $this->icoLotWait;
    }

    /**
     * @return QWaitIcon
     */
    public function getOfferWaitIcon(): QWaitIcon
    {
        if ($this->icoOfferWait === null) {
            $this->icoOfferWait = new QWaitIcon($this->getParentObject(), 'oai42');
        }
        return $this->icoOfferWait;
    }

    /**
     * @return array
     */
    public function getAvailableStates(): array
    {
        if ($this->availableStates === null) {
            $availableStateDetector = AvailableStateDetector::new()
                ->setAccountId($this->getAccountId())
                ->setLotItemId($this->getLotItemId())
                ->setAuctionId($this->getAuctionId())
                ->enableBiddingPaused($this->getAuction()->BiddingPaused)
                ->setEditorUserId($this->getEditorUserId())
                ->enableEmailVerificationRequired(
                    $this->getSettingsManager()->getForMain(Constants\Setting::VERIFY_EMAIL)
                );
            $this->availableStates = $availableStateDetector->getStates();
        }
        return $this->availableStates;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function setHandleNameForMakeOfferButton(string $functionName): static
    {
        $this->handleNameForMakeOfferBtn = trim($functionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getHandleNameForMakeOfferButton(): string
    {
        return $this->handleNameForMakeOfferBtn;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function setHandleNameForAcceptChanges(string $functionName): static
    {
        $this->handleNameForAcceptChanges = trim($functionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getHandleNameForAcceptChanges(): string
    {
        return $this->handleNameForAcceptChanges;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function setHandleNameForAcceptOffer(string $functionName): static
    {
        $this->handleNameForAcceptOffer = trim($functionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getHandleNameForAcceptOffer(): string
    {
        return $this->handleNameForAcceptOffer;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function setHandleNameForDeclineOffer(string $functionName): static
    {
        $this->handleNameForDeclineOffer = trim($functionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getHandleNameForDeclineOffer(): string
    {
        return $this->handleNameForDeclineOffer;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function setHandleNameForSpecialTermsClick(string $functionName): static
    {
        $this->handleNameForSpecialTermsClick = trim($functionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getHandleNameForSpecialTermsClick(): string
    {
        return $this->handleNameForSpecialTermsClick;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function setHandleNameForRegisterClick(string $functionName): static
    {
        $this->handleNameForRegisterClick = trim($functionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getHandleNameForRegisterClick(): string
    {
        return $this->handleNameForRegisterClick;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function setHandleNameForVClick(string $functionName): static
    {
        $this->handleNameForVClick = trim($functionName);
        return $this;
    }

    /**
     * @return string
     */
    public function getHandleNameForVClick(): string
    {
        return $this->handleNameForVClick;
    }

    /**
     * @param string $controlId
     * @return QTextBox
     */
    public function createOfferTextField(string $controlId): QTextBox
    {
        $this->txtOffer = new QTextBox($this->getParentObject(), $controlId);
        if ($this->getAuctionLot()->isAmongWonStatuses()) {
            $this->txtOffer->Text = $this->getTranslation('dealOk');
            $this->txtOffer->Enabled = false;
        } elseif ($this->getAuctionLot()->isUnsold()) {
            $this->txtOffer->Text = $this->getTranslation('dealNot');
            $this->txtOffer->Enabled = false;
        } else {
            $userOfferBid = $this->getTimedOnlineOfferBidLoader()
                ->loadByUserAndAuctionLotAndCounterBid(
                    $this->getEditorUserId(),
                    $this->getAuctionLot()->Id
                );
            $this->txtOffer->Text = $userOfferBid ? $this->getNumberFormatter()->formatMoneyNto($userOfferBid->Bid) : 0;
        }

        //remove enter action for txtOffer
        if ($this->isRestrictedGroup()) {
            $this->txtOffer->AddAction(new QEnterKeyEvent(), new QTerminateAction());
        }
        return $this->txtOffer;
    }

    /**
     * @param string $controlId
     * @return QLabel|Button
     */
    public function createMakeOfferButton(string $controlId): QLabel|Button
    {
        if (in_array(AvailableStateDetector::SIGNIN, $this->getAvailableStates(), true)) {
            return $this->createMakeOfferButtonForAnonymousUser($controlId);
        }

        return $this->createMakeOfferButtonForUser($controlId);
    }

    /**
     * @param string $controlId
     * @return QLabel|Button
     */
    public function createMakeOfferButtonForUser(string $controlId): Button|QLabel
    {
        $tr = $this->getTranslator();
        if (in_array(AvailableStateDetector::EMAIL_VERIFICATION, $this->getAvailableStates(), true)) {
            $this->btnMakeOffer = Qform_ComponentFactory::new()
                ->createButton($this->getParentObject(), $controlId);
            $this->btnMakeOffer->Text = $tr->translate('GENERAL_VERIFY_ACCT', 'general', $this->getSystemAccountId());
            $this->btnMakeOffer->ActionParameter = 0;
            $this->btnMakeOffer->AddAction(new QClickEvent(), new QServerAction($this->getHandleNameForVClick()));
        } elseif (in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)) {
            $this->btnMakeOffer = Qform_ComponentFactory::new()
                ->createButton($this->getParentObject(), $controlId);
            $this->btnMakeOffer->blockMultipleClick();
            $this->btnMakeOffer->Text = $tr->translate('GENERAL_SALECLOSED', 'general', $this->getSystemAccountId());
            $this->btnMakeOffer->Enabled = false;
        } elseif (in_array(AvailableStateDetector::AUCTION_REGISTRATION, $this->getAvailableStates(), true)) {
            $this->btnMakeOffer = Qform_ComponentFactory::new()
                ->createButton($this->getParentObject(), $controlId);
            $this->btnMakeOffer->blockMultipleClick();
            $this->btnMakeOffer->Text = $tr->translate('GENERAL_REGISTER_TO_OFFER', 'general', $this->getSystemAccountId());
            $this->btnMakeOffer->AddAction(
                new QClickEvent(),
                new QAjaxAction($this->getHandleNameForRegisterClick(), $this->getOfferWaitIcon())
            );

            $this->txtOffer->Enabled = false;
        } elseif (in_array(AvailableStateDetector::AUCTION_APPROVAL, $this->getAvailableStates(), true)) {
            $this->btnMakeOffer = new QLabel($this->getParentObject(), $controlId);
            $this->btnMakeOffer->HtmlEntities = false;
            $this->btnMakeOffer->Text = '<i>' . $tr->translate('GENERAL_PENDINGAPPROVAL', 'general', $this->getSystemAccountId()) . '</i>';

            $this->txtOffer->Enabled = false;
        } elseif (in_array(AvailableStateDetector::BIDDING_PAUSED, $this->getAvailableStates(), true)) {
            $this->btnMakeOffer = new QLabel($this->getParentObject(), $controlId);
            $this->btnMakeOffer->HtmlEntities = false;
            $this->btnMakeOffer->Text = '<span class="bidding-paused">';
            $this->btnMakeOffer->Text .= $tr->translate('GENERAL_BIDDING_PAUSED', 'general', $this->getSystemAccountId());
            $this->btnMakeOffer->Text .= '</span>';

            $this->txtOffer->Enabled = false;
        } else {
            $qty = '';
            $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale(
                $this->getAuctionLot()->LotItemId,
                $this->getAuctionLot()->AuctionId
            );
            if ($this->getAuctionLot()->isQuantityXMoneyEffective($quantityScale)) {
                $quantityFormatted = $this->createLotAmountRendererFactory()
                    ->create($this->getAuctionLot()->AccountId)
                    ->makeQuantity($this->getAuctionLot()->Quantity, $quantityScale);
                $qty = ' x' . $quantityFormatted;
            }

            $this->btnMakeOffer = Qform_ComponentFactory::new()
                ->createButton($this->getParentObject(), $controlId);
            $this->btnMakeOffer->blockMultipleClick();
            $userOfferBid = $this->getTimedOnlineOfferBidLoader()
                ->loadByUserAndAuctionLotAndCounterBid(
                    $this->getEditorUserId(),
                    $this->getAuctionLot()->Id
                );

            if (in_array(AvailableStateDetector::LOT_CHANGES_APPROVAL, $this->getAvailableStates(), true)) {
                $this->btnMakeOffer->Text = $this->getTranslation('lotChanges');
                $this->btnMakeOffer->AddAction(new QClickEvent(), new QServerAction($this->getHandleNameForAcceptChanges()));
                return $this->btnMakeOffer;
            }

            if (in_array(AvailableStateDetector::SPECIAL_TERMS_APPROVAL, $this->getAvailableStates(), true)) {
                $this->btnMakeOffer->Text = $tr->translate('MYITEMS_SPEC_TERMS', 'myitems');
                $this->btnMakeOffer->AddAction(new QClickEvent(), new QServerAction($this->getHandleNameForSpecialTermsClick()));
                return $this->btnMakeOffer;
            }

            if (in_array(AvailableStateDetector::RESTRICTED_BUYER_GROUP, $this->getAvailableStates(), true)) {
                $this->btnMakeOffer->Text = $this->getTranslation('restrictedGroup');
                $this->btnMakeOffer->Enabled = false;
                return $this->btnMakeOffer;
            }

            $this->btnMakeOffer->AddAction(
                new QClickEvent(),
                new QAjaxAction($this->getHandleNameForMakeOfferButton(), $this->getLotWaitIcon())
            );

            $this->btnMakeOffer->Text = $userOfferBid
                ? $tr->translate('CATALOG_OFFER_CHANGE', 'catalog', $this->getSystemAccountId())
                : $tr->translate('CATALOG_OFFER_MAKE', 'catalog', $this->getSystemAccountId());
            $this->btnMakeOffer->Text .= $qty;
        }
        return $this->btnMakeOffer;
    }

    /**
     * @param string $controlId
     * @return Button
     */
    public function createMakeOfferButtonForAnonymousUser(string $controlId): Button
    {
        $currentUrl = $this->getServerRequestReader()->currentUrl();

        $this->btnMakeOffer = Qform_ComponentFactory::new()
            ->createButton($this->getParentObject(), $controlId);
        $this->btnMakeOffer->blockMultipleClick();
        $this->btnMakeOffer->Text = $this->getTranslator()->translate('GENERAL_LOGINTOOFFER', 'general', $this->getSystemAccountId());
        $loginUrl = $this->getUrlBuilder()->build(ResponsiveLoginUrlConfig::new()->forRedirect());
        $loginUrl = $this->getBackUrlParser()->replace($loginUrl, $currentUrl);
        $this->btnMakeOffer->AddAction(
            new QClickEvent(),
            new QJavaScriptAction('sam.redirect(\'' . $loginUrl . '\')')
        );

        if (in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)) {
            $this->btnMakeOffer->Text = $this->getTranslator()->translate('GENERAL_SALECLOSED', 'general', $this->getSystemAccountId());
            $this->btnMakeOffer->Enabled = 'false';
        }
        $this->txtOffer->Enabled = false;
        return $this->btnMakeOffer;
    }

    /**
     * @return string|null
     */
    public function handleBtnMakeOfferClick(): ?string
    {
        $this->hasError = false;
        $this->errorMessage = '';
        $timedItem = $this->getTimedItem();
        if (!$timedItem) {
            log_error(
                "Available timed item not found, when making offer"
                . composeSuffix(['u' => $this->getEditorUserId(), 'toi' => $this->getTimedItemId()])
            );
            return null;
        }
        $timedItem->Reload();
        $auctionLot = $this->getAuctionLot();
        $auctionLot->Reload();
        $auctionLotId = $auctionLot->Id;

        if (in_array(AvailableStateDetector::LOT_CHANGES_APPROVAL, $this->getAvailableStates(), true)) {
            $url = $this->getUrlBuilder()->build(
                ResponsiveAuctionLotChangesUrlConfig::new()
                    ->forRedirect([$this->getLotItemId()], $auctionLot->AuctionId)
            );
            $this->createApplicationRedirector()->redirect($url);
            return null;
        }

        $offerAmount = $this->getNumberFormatter()->parse($this->txtOffer->Text);
        if (
            !is_numeric($offerAmount)
            || Floating::eq($offerAmount, 0)
        ) {
            $this->hasError = true;
            $this->errorMessage = $this->getTranslation('offerWarn');
            return null;
        }

        $isAboveStartingBid = $this->getAuction()->Reverse ? false
            : $this->getSettingsManager()->get(Constants\Setting::TIMED_ABOVE_STARTING_BID, $this->getAccountId());
        if (
            $isAboveStartingBid
            && $this->getLotItem()->StartingBid > 0
            && Floating::lt($offerAmount, $this->getLotItem()->StartingBid)
        ) {
            $this->hasError = true;
            $this->errorMessage = $this->getTranslation('aboveStartingBidWarn');
            return null;
        }

        $isTimedAboveReserve = $this->getAuction()->Reverse ? false
            : $this->getSettingsManager()->get(Constants\Setting::TIMED_ABOVE_RESERVE, $this->getAccountId());
        if (
            $isTimedAboveReserve
            && $this->getLotItem()->ReservePrice > 0
            && Floating::lt($offerAmount, $this->getLotItem()->ReservePrice)
        ) {
            $this->hasError = true;
            $this->errorMessage = $this->getTranslation('aboveReserveWarn');
            return null;
        }

        $editorUser = $this->getUserLoader()->load($this->getEditorUserId());
        if (!$editorUser) {
            log_error(
                "Available user of timed offer bid not found for make offer"
                . composeSuffix(['u' => $this->getEditorUserId(), 'ali' => $auctionLotId])
            );
            return null;
        }

        $this->createOfferBidSaver()->placeOfferBid(
            $this->getEditorUserId(),
            $auctionLotId,
            $offerAmount,
            $this->getEditorUserId()
        );
        $timedOfferBid = $this->getTimedOnlineOfferBidLoader()
            ->loadByUserAndAuctionLotAndCounterBid($this->getEditorUserId(), $auctionLotId);
        if (!$timedOfferBid) {
            log_error(
                "Available timed online offer bid not found for make offer"
                . composeSuffix(['u' => $this->getEditorUserId(), 'ali' => $auctionLotId])
            );
            return null;
        }
        //Send email to admin about counteroffer declining
        $emailManager = Email_Template::new()->construct(
            $this->getAccountId(),
            Constants\EmailKey::OFFER_SUBMITTED,
            $this->getEditorUserId(),
            [
                $editorUser,
                $this->getLotItem(),
                $auctionLot,
                $timedOfferBid->Bid,
            ],
            $this->getAuctionId()
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

        $this->message = $this->getTranslation('offerPlacedMsg');

        $url = $this->getServerRequestReader()->currentUrl();
        $url = $this->getUrlParser()->removeParams($url, [Constants\UrlParam::GA]);
        if ($this->isGaBidTrack()) {
            $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
            $ga = Constants\PageTracking::OFFER . $lotNo
                . '_' . $this->getLotRenderer()->renderItemNo($this->getLotItem())
                . '_' . $editorUser->Username;
            $url = $this->getUrlParser()->replaceParams($url, ['ga' => $ga]);
        }
        return $url;
    }

    /**
     * @param string $controlId
     * @return Button
     */
    public function createAcceptOfferButton(string $controlId): Button
    {
        if ($this->getAuction()->BiddingPaused) {
            $this->btnAcceptOffer = new QLabel($this->getParentObject(), $controlId);
            $this->btnAcceptOffer->HtmlEntities = false;
            $this->btnAcceptOffer->Text = '<span class="bidding-paused">'
                . $this->getTranslator()->translate('GENERAL_BIDDING_PAUSED', 'general', $this->getSystemAccountId())
                . '</span>';
            $this->txtOffer->Enabled = false;
        } else {
            $this->btnAcceptOffer = Qform_ComponentFactory::new()
                ->createButton($this->getParentObject(), $controlId);
            $this->btnAcceptOffer->blockMultipleClick();

            $this->btnAcceptOffer->AddAction(
                new QClickEvent(),
                new QAjaxAction($this->getHandleNameForAcceptOffer(), $this->icoOfferWait)
            );

            $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale(
                $this->getAuctionLot()->LotItemId,
                $this->getAuctionLot()->AuctionId
            );
            if ($this->getAuctionLot()->isQuantityXMoneyEffective($quantityScale)) {
                $langAcceptOfferX = $this->getTranslator()->translate("GENERAL_ACCEPT_OFFER_X", "general", $this->getSystemAccountId());
                $quantityFormatted = $this->createLotAmountRendererFactory()
                    ->create($this->getAuctionLot()->AccountId)
                    ->makeQuantity($this->getAuctionLot()->Quantity, $quantityScale);
                $this->btnAcceptOffer->Text = sprintf($langAcceptOfferX, $quantityFormatted);
            } else {
                $this->btnAcceptOffer->Text = $this->getTranslator()->translate("GENERAL_ACCEPT_OFFER", "general", $this->getSystemAccountId());
            }
        }
        return $this->btnAcceptOffer;
    }

    /**
     * @return string
     */
    public function handleBtnAcceptOfferClick(): string
    {
        $auctionLot = $this->getAuctionLot();

        $auctionLotId = $auctionLot->Id;
        $timedCounterBid = $this->getTimedOnlineOfferBidLoader()
            ->loadNewUserCounterOfferBid($this->getEditorUserId(), $auctionLotId);
        $logData = ['u' => $this->getEditorUserId(), 'ali' => $auctionLotId];
        if (!$timedCounterBid) {
            log_error(
                "Available user counter offer bid not found for timed online offer bid, when accepting offer"
                . composeSuffix($logData)
            );
            return '';
        }
        $timedOfferBid = $this->getTimedOnlineOfferBidLoader()
            ->loadByUserAndAuctionLotAndCounterBid($this->getEditorUserId(), $auctionLotId);
        if (!$timedOfferBid) {
            log_error(
                "Available user counter offer bid not found for timed online offer bid, when accepting offer"
                . composeSuffix($logData)
            );
            return '';
        }

        $user = $this->getUserLoader()->load($timedCounterBid->UserId, true);

        $timedCounterBid->Status = Constants\TimedOnlineOfferBid::STATUS_ACCEPTED;
        $this->getTimedOnlineOfferBidWriteRepository()->saveWithModifier($timedCounterBid, $this->getEditorUserId());

        [$startDateUtc, $endDateUtc] = $this->createTimedLotDateDetector()->detect($auctionLot);
        log_info(
            'ITEM DETAILS ACCEPT OFFER;' . composeSuffix(
                array_merge(
                    [
                        'Closing lot item' => $auctionLot->LotItemId,
                        'auction' => $auctionLot->AuctionId,
                        'Start' => $startDateUtc->format(Constants\Date::ISO),
                        'End' => $endDateUtc->format(Constants\Date::ISO),
                    ],
                    $logData
                )
            )
        );
        $timedItemCloser = TimedCloser::new();
        $timedItemCloser->closeAuctionLot(
            $auctionLot,
            Constants\BidTransaction::TYPE_OFFER,
            $this->getEditorUserId(),
            $timedCounterBid->Bid,
            $timedCounterBid->UserId
        ); // Closed the lot
        $auctionLot->Reload();

        $lotItem = $this->getLotItemLoader()->load($this->getTimedItem()->LotItemId, true);

        //Send email to admin about counteroffer declining
        $emailManager = Email_Template::new()->construct(
            $this->getAccountId(),
            Constants\EmailKey::COUNTER_ACCEPT,
            $this->getEditorUserId(),
            [
                $user,
                $lotItem,
                $auctionLot,
                $timedCounterBid->Bid,
                $timedOfferBid->Bid,
            ],
            $this->getAuctionId()
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

        $url = $this->getServerRequestReader()->cleanUrl();
        return $url;
    }

    /**
     * @param string $controlId
     * @return Button
     */
    public function createDeclineOfferButton(string $controlId): Button
    {
        if ($this->getAuction()->BiddingPaused) {
            $this->btnDeclineOffer = new QLabel($this->getParentObject(), $controlId);
            $this->btnDeclineOffer->HtmlEntities = false;
            $this->btnDeclineOffer->Text = '<span class="bidding-paused">'
                . $this->getTranslator()->translate('GENERAL_BIDDING_PAUSED', 'general', $this->getSystemAccountId())
                . '</span>';
            $this->txtOffer->Enabled = false;
        } else {
            $this->btnDeclineOffer = Qform_ComponentFactory::new()
                ->createButton($this->getParentObject(), $controlId);
            $this->btnDeclineOffer->blockMultipleClick();
            $this->btnDeclineOffer->AddAction(
                new QClickEvent(),
                new QServerAction($this->getHandleNameForDeclineOffer())
            );
            $this->btnDeclineOffer->Text = $this->getTranslator()->translate("GENERAL_DECLINE_OFFER", "general", $this->getSystemAccountId());
        }

        return $this->btnDeclineOffer;
    }

    /**
     * @return void
     */
    public function handleBtnDeclineOfferClick(): void
    {
        $auctionLot = $this->getAuctionLot();
        $auctionLotId = $auctionLot->Id;
        //1.Decline offer
        $timedCounterBid = $this->getTimedOnlineOfferBidLoader()
            ->loadNewUserCounterOfferBid($this->getEditorUserId(), $auctionLotId);
        if ($timedCounterBid) {
            $timedCounterBid->Status = Constants\TimedOnlineOfferBid::STATUS_REJECTED;
            $this->getTimedOnlineOfferBidWriteRepository()->saveWithModifier($timedCounterBid, $this->getEditorUserId());
        }

        $timedOfferBid = $this->getTimedOnlineOfferBidLoader()
            ->loadByUserAndAuctionLotAndCounterBid($this->getEditorUserId(), $auctionLotId);
        if (!$timedOfferBid) {
            log_error(
                "Available timed online offer bid not found for decline offer"
                . composeSuffix(['u' => $this->getEditorUserId(), 'ali' => $auctionLotId])
            );
            return;
        }

        [$startDateUtc, $endDateUtc] = $this->createTimedLotDateDetector()->detect($auctionLot);
        log_info(
            'ITEM DETAILS DECLINED OFFER;' . composeSuffix(
                [
                    'Closing lot item' => $auctionLot->LotItemId,
                    'auction' => $auctionLot->AuctionId,
                    'Start' => $startDateUtc->format(Constants\Date::ISO),
                    'End' => $endDateUtc->format(Constants\Date::ISO),
                ]
            )
        );

        $editorUser = $this->getUserLoader()->load($this->getEditorUserId(), true);
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);

        //Send email to admin about counteroffer declining
        $emailManager = Email_Template::new()->construct(
            $this->getAccountId(),
            Constants\EmailKey::COUNTER_DECLINED,
            $this->getEditorUserId(),
            [
                $editorUser,
                $lotItem,
                $auctionLot,
                $timedOfferBid->Bid,
                $timedCounterBid->Bid,
            ],
            $this->getAuctionId()
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
    }

    /**
     * @param float $offer
     * @param string $controlId
     * @return QLabel
     */
    public function createLabelOfferMessage1(float $offer, string $controlId): QLabel
    {
        $lblOfferMessage1 = new QLabel($this->getParentObject(), $controlId);
        $lblOfferMessage1->CssClass = 'message';
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($this->getAuctionId());
        $lblOfferMessage1->Text = sprintf($this->getTranslator()->translate("GENERAL_SELLER_COUNTERED_DECLINE_OFFER", 'general'), $currencySign, $offer);
        return $lblOfferMessage1;
    }

    /**
     * @param string $controlId
     * @return QLabel
     */
    public function createLabelOfferMessage2(string $controlId): QLabel
    {
        $lblOfferMessage2 = new QLabel($this->getParentObject(), $controlId);
        $lblOfferMessage2->CssClass = 'message';
        $lblOfferMessage2->Text = $this->getTranslator()->translate("GENERAL_MAKE_NEW_COUNTER_OFFER", "general", $this->getSystemAccountId());
        return $lblOfferMessage2;
    }

    /**
     * @param string $translationKey
     * @return string
     */
    protected function getTranslation(string $translationKey): string
    {
        if ($this->translations === null) {
            $tr = $this->getTranslator();
            $this->translations['dealNot'] = $tr->translateByAuctionReverse('ITEM_UNSOLD', 'item', $this->getAuction()->Reverse);
            $this->translations['dealOk'] = $tr->translateByAuctionReverse('ITEM_SOLD', 'item', $this->getAuction()->Reverse);
            $this->translations['lotChanges'] = $tr->translate('CATALOG_LOT_CHANGES', 'catalog');
            $this->translations['restrictedGroup'] = $tr->translate('GENERAL_RESTRICTED_GROUP', 'general');
            $this->translations['offerWarn'] = $tr->translate('GENERAL_INVALID_OFFER', 'general');
            $this->translations['aboveStartingBidWarn'] = $tr->translate('CATALOG_STARTINGBIDNOTMET', 'catalog');
            $this->translations['aboveReserveWarn'] = $tr->translate('CATALOG_RESERVENOTMET', 'catalog');
            $this->translations['offerPlacedMsg'] = $tr->translate('CATALOG_OFFER_PLACED', 'catalog');
        }
        return $this->translations[$translationKey];
    }

    /**
     * @return bool
     */
    protected function isGaBidTrack(): bool
    {
        if ($this->gaBidTrack === null) {
            $this->gaBidTrack = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::GA_BID_TRACKING, $this->getAuction()->AccountId);
        }
        return $this->gaBidTrack;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->hasError;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

}
