<?php
/**
 * SAM-6116: Landing Page Url: Saving installation URL, at frontend error occurs
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis, Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-04, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Landing\Responsive;

use Sam\Application\Url\Build\Config\Landing\ResponsiveLandingUrlConfig;
use Sam\Application\Url\Build\Config\Search\ResponsiveSearchUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class ResponsiveLandingPageDetector
 * This service determines the way of jumping to landing page. It provides 2 possibilities for page change:
 * 1) By redirection url, that is always available and can be retrieved by getRedirectUrl().
 * 2) By internal controller/action forwarding, that is available for concrete routes of Auction List or Lot Search pages.
 */
class ResponsiveLandingPageDetector extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;

    public const ERR_ENDLESS_LOOP = 1;
    public const ERR_INCORRECT_LANDING_URL = 2;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_ENDLESS_LOOP => '"Landing page url" setting causes endless redirecting loop',
        self::ERR_INCORRECT_LANDING_URL => '"Landing page url" setting value looks incorrect'
    ];

    /**
     * @var array<string, string[]> setui.landing_page => [controller, action]
     */
    protected const FORWARD_WAYS = [
        Constants\SettingUi::LP_AUCTION => [Constants\ResponsiveRoute::C_AUCTIONS, Constants\ResponsiveRoute::AA_LIST],
        Constants\SettingUi::LP_SEARCH => [Constants\ResponsiveRoute::C_SEARCH, Constants\ResponsiveRoute::ASRCH_INDEX],
    ];

    /** @var string[] */
    protected const REDIRECT_URL_CONFIG_CLASSES = [
        Constants\SettingUi::LP_AUCTION => ResponsiveLandingUrlConfig::class,
        Constants\SettingUi::LP_SEARCH => ResponsiveSearchUrlConfig::class,
    ];

    /** @var int */
    protected int $systemAccountId;
    /** @var string */
    protected string $forwardController = '';
    /** @var string */
    protected string $forwardAction = '';
    /** @var string */
    protected string $redirectUrl = '';
    /** @var string */
    protected string $landingPage = Constants\SettingUi::LP_AUCTION;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(int $systemAccountId): static
    {
        $this->systemAccountId = $systemAccountId;
        return $this;
    }

    /**
     * Performs detection of landing page redirection and forwarding possibilities.
     */
    public function detect(): void
    {
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
        $this->landingPage = $this->getSettingsManager()->get(Constants\Setting::LANDING_PAGE, $this->systemAccountId);
        if ($this->canForward()) {
            $this->forwardController = static::FORWARD_WAYS[$this->landingPage][0];
            $this->forwardAction = static::FORWARD_WAYS[$this->landingPage][1];
            $this->redirectUrl = $this->buildUrl($this->landingPage);
        } elseif ($this->landingPage === Constants\SettingUi::LP_OTHER) {
            $this->detectForLandingUrl();
        }
    }

    /**
     * Determines, if user can be forwarded to new location without redirecting to new url.
     * Forwarding should be solved by internal routing means of framework.
     * @return bool
     */
    public function canForward(): bool
    {
        return isset(static::FORWARD_WAYS[$this->landingPage]);
    }

    /**
     * Returns action name for forwarding to landing page.
     * @return string
     */
    public function getForwardAction(): string
    {
        return $this->forwardAction;
    }

    /**
     * Returns controller name for forwarding to landing page.
     * @return string
     */
    public function getForwardController(): string
    {
        return $this->forwardController;
    }

    /**
     * Returns landing page url.
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * Return error message, in case of problem with predefined landing page url.
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Performs detection of redirection or forwarding possibilities (in case of problem with predefined landing page url)
     * if we using Other landing page type.
     */
    protected function detectForLandingUrl(): void
    {
        $landingUrl = $this->getSettingsManager()->get(Constants\Setting::LANDING_PAGE_URL, $this->systemAccountId);
        $landingUrlParts = parse_url($landingUrl);
        if (!$landingUrlParts) {
            $this->initForIncorrectLandingUrl();
            $this->processError(self::ERR_INCORRECT_LANDING_URL, $landingUrl);
            return;
        }
        if ($this->isEndlessRedirectingLoop($landingUrlParts)) {
            $this->initForIncorrectLandingUrl();
            $this->processError(self::ERR_ENDLESS_LOOP, $landingUrl);
            return;
        }
        $this->redirectUrl = $landingUrl;
    }

    /**
     * @param int $errorCode
     * @param string $landingUrl
     */
    protected function processError(int $errorCode, string $landingUrl): void
    {
        $collector = $this->getResultStatusCollector();
        $collector->addErrorWithAppendedMessage($errorCode, composeSuffix(['url' => $landingUrl]));
        log_error($collector->lastAddedErrorMessage());
    }

    /**
     * Compare request url and redirection url
     * @param array $landingUrlParts
     * @return bool
     */
    protected function isEndlessRedirectingLoop(array $landingUrlParts): bool
    {
        // Remove front slashes and dots from both checking paths
        $ltrimChars = '/.';
        $serverRequestReader = $this->getServerRequestReader();

        $landingUrlParts['path'] = ltrim($landingUrlParts['path'] ?? '', $ltrimChars);
        $landingUrlParts['host'] = $landingUrlParts['host'] ?? $serverRequestReader->serverName();

        $requestUrlParts = parse_url($serverRequestReader->currentUrl());
        $requestUrlParts['path'] = ltrim($requestUrlParts['path'], $ltrimChars);

        $isEndlessLoop = $requestUrlParts['host'] === $landingUrlParts['host']
            && $requestUrlParts['path'] === $landingUrlParts['path'];

        return $isEndlessLoop;
    }

    /**
     * Initialize default forwarding to controller action with default error message,
     * if endless redirecting loop detected or incorrect landing page url has been provided.
     */
    protected function initForIncorrectLandingUrl(): void
    {
        $landingPage = Constants\SettingUi::LP_AUCTION;
        $this->landingPage = $landingPage;
        $this->forwardController = static::FORWARD_WAYS[$landingPage][0];
        $this->forwardAction = static::FORWARD_WAYS[$landingPage][1];
        $this->redirectUrl = $this->buildUrl($landingPage);
    }

    /**
     * @param string $landingPage
     * @return string
     */
    protected function buildUrl(string $landingPage): string
    {
        $class = static::REDIRECT_URL_CONFIG_CLASSES[$landingPage];
        /** @var ResponsiveLandingUrlConfig|ResponsiveSearchUrlConfig $urlConfig */
        $urlConfig = call_user_func([$class, 'new'])->forWeb();
        $url = $this->getUrlBuilder()->build($urlConfig);
        return $url;
    }
}
