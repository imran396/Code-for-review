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
 * @since       Jun 08, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Lot\Details\Live\Button;

use AuctionLotItem;
use QAjaxAction;
use QClickEvent;
use QControl;
use Qform_ComponentFactory;
use QJavaScriptAction;
use QLabel;
use QServerAction;
use QWaitIcon;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Qform\AvailableStateDetector;
use Sam\Qform\Button;
use Sam\Qform\Lot\GeneralControlTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BuyNowCreator
 */
class BuyNowCreator extends CustomizableClass
{
    use AuctionStatusCheckerAwareTrait;
    use BackUrlParserAwareTrait;
    use CurrencyLoaderAwareTrait;
    use GeneralControlTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use ServerRequestReaderAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

    private Button|QLabel|null $btnBuyNow = null;
    private ?QWaitIcon $icoWait = null;

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

    public function create(): Button|QLabel|null
    {
        if (in_array(AvailableStateDetector::SIGNIN, $this->getAvailableStates(), true)) {
            $this->createForAnonymousUser();
        } else {
            $this->createForUser();
        }

        return $this->btnBuyNow;
    }

    /**
     * @param string $controlId
     * @return Button|null
     */
    protected function createNewButton(string $controlId): ?Button
    {
        return Qform_ComponentFactory::new()
            ->createButton($this->getViewContext()->getParentObject(), $controlId);
    }

    /**
     */
    protected function createForAnonymousUser(): void
    {
        $this->btnBuyNow = $this->createNewButton($this->getControlId());
        $this->btnBuyNow->blockMultipleClick();

        //'Login to buy!';
        $this->btnBuyNow->Text = $this->getTranslator()->translate('GENERAL_LOGINTOBUY', 'general', $this->getViewContext()->getSystemAccountId());

        $backUrl = $this->getServerRequestReader()->currentUrl();
        $loginUrl = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_LOGIN)
        );
        $loginUrl = $this->getBackUrlParser()->replace($loginUrl, $backUrl);
        $this->btnBuyNow->AddAction(
            new QClickEvent(),
            new QJavaScriptAction("sam.redirect('{$loginUrl}');")
        );

        if (in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)) {
            $this->btnBuyNow->Text = $this->getTranslator()->translate('GENERAL_SALECLOSED', 'general', $this->getViewContext()->getSystemAccountId());
            $this->btnBuyNow->Enabled = false;
        }
    }

    /**
     */
    protected function createForUser(): void
    {
        if (in_array(AvailableStateDetector::EMAIL_VERIFICATION, $this->getAvailableStates(), true)) {
            $this->createForEmailVerification();
        } elseif (in_array(AvailableStateDetector::AUCTION_REGISTRATION, $this->getAvailableStates(), true)) {
            $this->createForAuctionRegistration();
        } elseif (in_array(AvailableStateDetector::AUCTION_APPROVAL, $this->getAvailableStates(), true)) {
            $this->createForAuctionApproval();
        } elseif (in_array(AvailableStateDetector::BIDDING_PAUSED, $this->getAvailableStates(), true)) {
            $this->createForBiddingPaused();
        } elseif (in_array(AvailableStateDetector::SPECIAL_TERMS_APPROVAL, $this->getAvailableStates(), true)) {
            $this->createForSpecialTermsApproval();
        } elseif (in_array(AvailableStateDetector::LOT_CHANGES_APPROVAL, $this->getAvailableStates(), true)) {
            $this->createForLotChangesApproval();
        } elseif (in_array(AvailableStateDetector::RESTRICTED_BUYER_GROUP, $this->getAvailableStates(), true)) {
            $this->createForRestrictedBuyerGroup();
        } elseif (in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)) {
            // Any ways the button created invisible and not used in JavaScript.
            // $this->createForClosed();
        } else {
            $this->createForBuyNow();
        }
    }

    /**
     */
    protected function createForAuctionRegistration(): void
    {
        $this->btnBuyNow = $this->createNewButton($this->getControlId());
        $this->btnBuyNow->blockMultipleClick();

        //'Register to buy!';
        $this->btnBuyNow->Text = $this->getTranslator()->translate('GENERAL_REGISTERTOBUY', 'general', $this->getViewContext()->getSystemAccountId());

        $this->btnBuyNow->AddAction(
            new QClickEvent(),
            new QAjaxAction(
                $this->viewContext->getMethodNameForAuctionRegistration(),
                $this->getIconWait() ?: 'default'
            )
        );

        if (in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)) {
            $this->btnBuyNow->Text = $this->getTranslator()->translate('GENERAL_SALECLOSED', 'general', $this->getViewContext()->getSystemAccountId());
            $this->btnBuyNow->Enabled = false;
        }
        $auctionId = $this->getViewContext()->getAuctionLot()->AuctionId;
        $isRegistrationActive = $this->getAuctionStatusChecker()->isRegistrationActive($auctionId);
        if (!$isRegistrationActive) {
            $this->btnBuyNow->Enabled = false;
        }
    }

    protected function createForAuctionApproval(): void
    {
        $this->btnBuyNow = new QLabel($this->getViewContext()->getParentObject(), $this->getControlId());
        $this->btnBuyNow->HtmlEntities = false;
        $this->btnBuyNow->Text = '<i>';
        $this->btnBuyNow->Text .= $this->getTranslator()->translate('GENERAL_PENDINGAPPROVAL', 'general', $this->getViewContext()->getSystemAccountId());
        $this->btnBuyNow->Text .= '</i>';

        if (in_array(AvailableStateDetector::AUCTION_CLOSED, $this->getAvailableStates(), true)) {
            $this->btnBuyNow->Text = '';
        }
    }

    protected function createForBiddingPaused(): void
    {
        $this->btnBuyNow = new QLabel($this->getViewContext()->getParentObject(), $this->getControlId());
        $this->btnBuyNow->HtmlEntities = false;
        $this->btnBuyNow->Text = '<span class="bidding-paused">';
        $this->btnBuyNow->Text .= $this->getTranslator()->translate('GENERAL_BIDDING_PAUSED', 'general', $this->getViewContext()->getSystemAccountId());
        $this->btnBuyNow->Text .= '</span>';
    }

    /**
     */
    protected function createForBuyNow(): void
    {
        $this->btnBuyNow = $this->createNewButton($this->getControlId());
        $this->btnBuyNow->blockMultipleClick();

        //'Buy Now!';
        $this->btnBuyNow->Text = $this->getTranslator()->translate('CATALOG_BUYNOW', 'catalog', $this->getViewContext()->getSystemAccountId());
        $auctionLot = $this->getViewContext()->getAuctionLot();
        if (!$auctionLot) {
            log_error("Available auctionLotItem not found, when creating By Now button" . composeSuffix(['a' => $this->getViewContext()->getAuctionId()]));
            return;
        }

        $this->btnBuyNow->Text .= $this->getQuantityXMoneyOnButton($auctionLot);
        $this->setActionByUserAgent($this->btnBuyNow);
    }

    /**
     * @param Button $button
     */
    protected function setActionByUserAgent(Button $button): void
    {
        $button->AddAction(
            new QClickEvent(),
            new QAjaxAction($this->viewContext->getMethodNameForBuyNow(), $this->getIconWait() ?: 'default')
        );
    }

    /**
     * Return quantity text rendered on a button, i.e. "x15"
     * @param AuctionLotItem $auctionLot
     * @return string
     */
    protected function getQuantityXMoneyOnButton(AuctionLotItem $auctionLot): string
    {
        $qty = '';
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale(
            $auctionLot->LotItemId,
            $auctionLot->AuctionId
        );
        if ($auctionLot->isQuantityXMoneyEffective($quantityScale)) {
            $quantityFormatted = $this->createLotAmountRendererFactory()
                ->create($auctionLot->AccountId)
                ->makeQuantity($auctionLot->Quantity, $quantityScale);
            $qty = ' ' . $this->getCurrencyLoader()->detectDefaultSign($auctionLot->AuctionId)
                . $this->getNumberFormatter()->formatMoneyNto($auctionLot->BuyNowAmount) . ' x' . $quantityFormatted;
        }
        return $qty;
    }

    /**
     */
    protected function createForEmailVerification(): void
    {
        $this->btnBuyNow = $this->createNewButton('btnV2');
        $this->btnBuyNow->blockMultipleClick();
        $this->btnBuyNow->Text = $this->getTranslator()->translate('GENERAL_VERIFY_ACCT', 'general', $this->getViewContext()->getSystemAccountId());
        $this->btnBuyNow->ActionParameter = 2;
        $this->btnBuyNow->AddAction(
            new QClickEvent(),
            new QServerAction($this->viewContext->getMethodNameForEmailVerification())
        );
    }

    /**
     */
    protected function createForSpecialTermsApproval(): void
    {
        $this->btnBuyNow = $this->createNewButton($this->getControlId());
        $this->btnBuyNow->blockMultipleClick();

        $this->btnBuyNow->Text = $this->getTranslator()->translate('MYITEMS_SPEC_TERMS', 'myitems', $this->getViewContext()->getSystemAccountId());
        $this->btnBuyNow->AddAction(
            new QClickEvent(),
            new QServerAction($this->viewContext->getMethodNameForSpecialTermsApproval())
        );
    }

    /**
     */
    protected function createForLotChangesApproval(): void
    {
        $this->btnBuyNow = $this->createNewButton($this->getControlId());
        $this->btnBuyNow->blockMultipleClick();

        $this->btnBuyNow->Text = $this->getTranslator()->translate('CATALOG_LOT_CHANGES', 'catalog', $this->getViewContext()->getSystemAccountId());
        $this->btnBuyNow->AddAction(
            new QClickEvent(),
            new QServerAction($this->viewContext->getMethodNameForLotChangesApproval())
        );
    }

    protected function createForRestrictedBuyerGroup(): void
    {
        $this->btnBuyNow = $this->createNewButton($this->getControlId());
        $this->btnBuyNow->blockMultipleClick();

        $this->btnBuyNow->Text = $this->getTranslator()->translate('GENERAL_RESTRICTED_GROUP', 'general', $this->getViewContext()->getSystemAccountId());
        $this->btnBuyNow->Enabled = false;
    }

    /**
     */
    protected function createForClosed(): void
    {
        $this->btnBuyNow = $this->createNewButton($this->getControlId());
        $this->btnBuyNow->blockMultipleClick();

        $this->btnBuyNow->Text = $this->getTranslator()->translate('GENERAL_SALECLOSED', 'general', $this->getViewContext()->getSystemAccountId());
        $this->btnBuyNow->Enabled = false;
        $this->btnBuyNow->Visible = false;

        $this->setActionByUserAgent($this->btnBuyNow);
    }

}
