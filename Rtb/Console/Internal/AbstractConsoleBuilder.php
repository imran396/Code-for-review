<?php

namespace Sam\Rtb\Console\Internal;

use Auction;
use Laminas\Filter\StripNewlines;
use LotItemCustField;
use RuntimeException;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\Protect\Csrf\SynchronizerToken\Store\SynchronizerTokenProviderCreateTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Simultaneous\Load\SimultaneousAuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\ConsoleBaseConstants;
use Sam\Core\Constants\Responsive\RtbConsoleConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Rtb\ConsoleHelper;
use Sam\Rtb\Control\Render\RtbControlCollectionAwareTrait;
use Sam\Rtb\Control\Render\RtbControlRendererCreateTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Validate\RtbAuctionValidatorCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\JsValueImporterAwareTrait;

/**
 * Class AbstractConsoleBuilder
 * @method Auction getAuction() - we are sure, it is not null
 * TODO: we want get rid of this parent class in further, so now we need to decouple it first
 */
abstract class AbstractConsoleBuilder extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use AuctionAwareTrait;
    use AuctionHelperAwareTrait;
    use AuctionLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CookieHelperCreateTrait;
    use CurrencyLoaderAwareTrait;
    use FilePathHelperAwareTrait;
    use JsValueImporterAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use RtbAuctionValidatorCreateTrait;
    use RtbControlCollectionAwareTrait;
    use RtbControlRendererCreateTrait;
    use RtbGeneralHelperAwareTrait;
    use SettingsManagerAwareTrait;
    use SimultaneousAuctionLoaderAwareTrait;
    use SynchronizerTokenProviderCreateTrait;
    use TranslatorAwareTrait;

    public bool $isHideBidderNumber = false;
    public string $currencySign = '';
    public bool $isUsNumberFormatting = false;
    public bool $isClearMessageCenter = false;
    public bool $isTwentyMessagesMax = false;
    public bool $isMultiCurrency = false;
    /** @var LotItemCustField[] */
    public array $lotCustomFields = [];
    public int $delaySoldItem = 0;
    public int $delayBlockSell = 0;
    public bool $isFloorBiddersFromDropdown = false;
    public int $switchFrameSeconds = 0;
    public bool $isSlideshowProjectorOnly = false;
    /** @var string[] */
    public array $liveBiddingCountdowns = [];
    protected ?int $simultaneousAuctionId = null;
    /**
     * Cache here Simultaneous auction availability by auction.id as key
     * @var bool[]
     */
    protected array $saAvailabilities = [];
    protected ?ConsoleHelper $consoleHelper = null;
    protected ?string $sessionId = null;

    /**
     * @return string
     */
    abstract public function loadScript(): string;

    /**
     * @param int $auctionId
     * @return static
     */
    public function construct(int $auctionId): static
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for rtb console initialization"
                . composeSuffix(['a' => $auctionId])
            );
            $this->createApplicationRedirector()->processPageNotFound();
        }
        $this->setAuction($auction);

        $auctionAccountId = $auction->AccountId;
        $this->getNumberFormatter()->construct($auction->AccountId);
        $this->currencySign = $this->getCurrencyLoader()->detectDefaultSign($auction->Id);
        $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadByRole();

        $sm = $this->getSettingsManager();
        $this->delayBlockSell = (int)$sm->get(Constants\Setting::DELAY_BLOCK_SELL, $auctionAccountId);
        $this->delaySoldItem = (int)$sm->get(Constants\Setting::DELAY_SOLD_ITEM, $auctionAccountId);
        $this->isClearMessageCenter = (bool)$sm->get(Constants\Setting::CLEAR_MESSAGE_CENTER, $auctionAccountId);
        $this->isFloorBiddersFromDropdown = (bool)$sm->get(Constants\Setting::FLOOR_BIDDERS_FROM_DROPDOWN, $auctionAccountId);
        $this->isHideBidderNumber = (bool)$sm->get(Constants\Setting::HIDE_BIDDER_NUMBER, $auctionAccountId);
        $this->isMultiCurrency = (bool)$sm->get(Constants\Setting::MULTI_CURRENCY, $auctionAccountId);
        $this->isSlideshowProjectorOnly = (bool)$sm->get(Constants\Setting::SLIDESHOW_PROJECTOR_ONLY, $auctionAccountId);
        $this->isTwentyMessagesMax = (bool)$sm->get(Constants\Setting::TWENTY_MESSAGES_MAX, $auctionAccountId);
        $this->isUsNumberFormatting = (bool)$sm->get(Constants\Setting::US_NUMBER_FORMATTING, $auctionAccountId);
        $this->switchFrameSeconds = 1000 * (int)$sm->get(Constants\Setting::SWITCH_FRAME_SECONDS, $auctionAccountId);

        $liveBiddingCountdown = $sm->get(Constants\Setting::LIVE_BIDDING_COUNTDOWN, $auctionAccountId);
        $liveBiddingCountdowns = preg_split('/\R/', trim($liveBiddingCountdown));
        foreach ($liveBiddingCountdowns as $i => $bidCount) {
            if (trim($bidCount) === '') {
                unset($liveBiddingCountdowns[$i]);
            } else {
                $bidCount = (new StripNewlines())->filter($bidCount);
                $liveBiddingCountdowns[$i] = $this->getRtbGeneralHelper()->clean($bidCount);
            }
        }
        $this->liveBiddingCountdowns = $liveBiddingCountdowns;
        return $this;
    }

    /**
     * @return ConsoleHelper
     */
    public function getConsoleHelper(): ConsoleHelper
    {
        if ($this->consoleHelper === null) {
            $this->consoleHelper = ConsoleHelper::new();
        }
        return $this->consoleHelper;
    }

    /**
     * @return string
     */
    public function renderMobileMessageCenter(): string
    {
        return '';
    }

    /**
     * Validate auction state and return error messages or empty array on success validation
     * @return string[]
     */
    public function validateToErrorMessages(): array
    {
        $validator = $this->createRtbAuctionValidator();
        $validator->validate($this->getAuctionId());
        return $validator->errorMessages();
    }

    public function initControls(): void
    {
    }

    /**
     * @return string
     */
    public function renderBegin(): string
    {
        $csrfToken = $this->createSynchronizerTokenProvider()->getSessionToken();
        $csrfTokenName = $this->cfg()->get('core->app->csrf->synchronizerToken->hiddenFieldName');
        $cssUrlPath = $this->getFilePathHelper()->appendUrlPathWithMTime('/assets/dist/admin_manageAuctions_run.css');
        $output =
            '<style type="text/css">@import url("' . $cssUrlPath . '");</style>' . "\n" .
            '<script>' . "\n" . $this->loadScript() . "\n" . '</script>' . "\n" .
            $this->getJsValueImporter()->renderJs() . "\n" .
            '<form action="" method="post">' . "\n" .
            '<input type="hidden" id="' . $csrfTokenName . '" name="' . $csrfTokenName . '" value="' . $csrfToken . '" />' . "\n" .
            '<input type="hidden" id="' . Constants\Qform::FORM_CSRF_TOKEN_NAME . '" name="' . Constants\Qform::FORM_CSRF_TOKEN_NAME . '" value="' . $csrfTokenName . '" />' . "\n" .
            '<div id="' . ConsoleBaseConstants::CID_RTB_PANEL . '">' . "\n";
        return $output;
    }

    /**
     * @return string
     */
    public function renderEnd(): string
    {
        return "</div>\n</form>";
    }

    /**
     * @param string $controlId
     * @param array $customControlParams
     * @return string
     */
    public function renderControl(string $controlId, array $customControlParams = []): string
    {
        $controlParams = $this->getRtbControlCollection()->get($controlId);
        if (!$controlParams) {
            throw new RuntimeException("Undefined rtb control \"{$controlId}\"");
        }
        $output = $this->createRtbControlRenderer()->render($controlParams, $customControlParams);
        return $output;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        if ($this->sessionId === null) {
            $this->sessionId = (string)session_id();
        }
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return static
     */
    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = trim($sessionId);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSimultaneousAuctionId(): ?int
    {
        if ($this->simultaneousAuctionId === null) {
            $this->simultaneousAuctionId = $this->getSimultaneousAuctionLoader()
                ->findSimultaneousAuctionId($this->getAuction(), true);
        }
        return $this->simultaneousAuctionId;
    }

    /**
     * @param int|null $simultaneousAuctionId
     * @return static
     * @noinspection PhpUnused
     */
    public function setSimultaneousAuctionId(?int $simultaneousAuctionId): static
    {
        $this->simultaneousAuctionId = $simultaneousAuctionId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSimultaneousAuctionAvailable(): bool
    {
        if (!isset($this->saAvailabilities[$this->getAuctionId()])) {
            $this->saAvailabilities[$this->getAuctionId()] = $this->getAuctionHelper()
                ->isSimultaneousAuctionAvailable($this->getAuction(), true);
            if ($this->saAvailabilities[$this->getAuctionId()]) {
                log_trace('Simultaneous auction found' . composeSuffix(['a' => $this->getAuctionId()]));
            }
        }
        return $this->saAvailabilities[$this->getAuctionId()];
    }

    /**
     * @return string
     */
    protected function cssClassForSimultaneousAuction(): string
    {
        return $this->isSimultaneousAuctionAvailable()
            ? RtbConsoleConstants::CSS_CLASS_WITH_SIMULTANEOUS_AUCTION
            : '';
    }
}
