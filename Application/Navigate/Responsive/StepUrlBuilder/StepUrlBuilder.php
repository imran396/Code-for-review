<?php
/**
 * SAM-5546:  Auction registration step detection and redirect
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-10, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Navigate\Responsive\StepUrlBuilder;

use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\Config\Base\OneStringParamUrlConfig;
use Sam\Application\Url\Build\Config\Landing\ResponsiveLandingUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Build url for a specific registration step status.
 * See all step statuses in \Sam\Core\Constants\RegistrationStepStatuses
 *
 * Class AuctionRegistrationStepUrlBuilder
 * @package Sam\Application\Navigate\Responsive\StepUrlBuilder
 */
class StepUrlBuilder extends CustomizableClass
{
    use AuctionAwareTrait;
    use BackUrlParserAwareTrait;
    use TranslatorAwareTrait;
    use UrlParserAwareTrait;
    use UrlBuilderAwareTrait;

    protected ?string $redirectUrl = null;
    protected array $urlParams = [];

    /**
     * Step statuses for which we will build urls.
     */
    protected static array $buildUrlForConcreteSteps = [
        Constants\RegistrationStepStatuses::STEP_ANONYMOUS_USER,
        Constants\RegistrationStepStatuses::STEP_AUCTION_ABSENT,
        Constants\RegistrationStepStatuses::STEP_CONFIRM_BIDDER_OPTIONS,
        Constants\RegistrationStepStatuses::STEP_CONFIRM_SHIPPING,
        Constants\RegistrationStepStatuses::STEP_NO_BIDDER_PRIVILEGES,
        Constants\RegistrationStepStatuses::STEP_RENEW_BILLING,
        Constants\RegistrationStepStatuses::STEP_REVISE_BILLING,
        Constants\RegistrationStepStatuses::STEP_TERMS_AND_CONDITIONS,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build ready for redirection url and store it in $this->redirectUrl property.
     *
     * @param bool $useToAuctionRegistration
     * @param int $redirectStep
     * @return static
     */
    public function build(bool $useToAuctionRegistration, int $redirectStep): static
    {
        $backUrl = $redirectUrl = null;
        $urlParams = $this->getUrlParams();
        if (isset($urlParams[Constants\UrlParam::BACK_URL])) {
            $backUrl = $urlParams[Constants\UrlParam::BACK_URL];
            unset($urlParams[Constants\UrlParam::BACK_URL]);
        }
        if (
            $useToAuctionRegistration
            && isset($backUrl)
            && $redirectStep === Constants\RegistrationStepStatuses::STEP_AUCTION_REGISTERED
        ) {
            $redirectUrl = $backUrl;
        } elseif (in_array($redirectStep, self::$buildUrlForConcreteSteps, true)) {
            $redirectUrl = $this->buildRedirectUrl($redirectStep, $backUrl);
        }
        $this->setRedirectUrl($redirectUrl);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     * @return static
     */
    public function setRedirectUrl(string $redirectUrl): static
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * Get url query parameters
     * @return array
     */
    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    /**
     * Set url query parameters
     * @param array $urlParams
     * @return $this
     */
    public function withParams(array $urlParams): static
    {
        $this->urlParams = $urlParams;
        return $this;
    }

    /**
     * Build ready for redirection url with query parameters.
     *
     * @param int $redirectStep
     * @param string|null $backUrl
     * @return string
     */
    protected function buildRedirectUrl(int $redirectStep, ?string $backUrl = null): string
    {
        $redirectUrl = $this->getUrlByStepStatus($redirectStep, $backUrl);
        if ($redirectStep !== Constants\RegistrationStepStatuses::STEP_NO_BIDDER_PRIVILEGES) {
            $params = $this->getUrlParams();
            if (count($params) > 0) {
                $redirectUrl = $this->getUrlParser()->replaceParams($redirectUrl, $params);
            }
            if ($backUrl) {
                $redirectUrl = $this->getBackUrlParser()->replace($redirectUrl, $backUrl);
            }
        }
        return $redirectUrl;
    }

    /**
     * Get main url by redirect step status. See all step statuses in Sam\Core\Constants\RegistrationStepStatuses.
     *
     * @param int $redirectStep
     * @param string|null $backUrl
     * @return string
     */
    protected function getUrlByStepStatus(int $redirectStep, ?string $backUrl = null): string
    {
        $auctionId = $this->getAuctionId();
        $url = '';
        if ($redirectStep === Constants\RegistrationStepStatuses::STEP_ANONYMOUS_USER) {
            $url = $this->getUrlBuilder()->build(ResponsiveLoginUrlConfig::new()->forRedirect());
        } elseif ($redirectStep === Constants\RegistrationStepStatuses::STEP_AUCTION_ABSENT) {
            $url = $this->getUrlBuilder()->build(ResponsiveLandingUrlConfig::new()->forRedirect());
        } elseif ($redirectStep === Constants\RegistrationStepStatuses::STEP_CONFIRM_SHIPPING) {
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_CONFIRM_SHIPPING, $auctionId)
            );
        } elseif ($redirectStep === Constants\RegistrationStepStatuses::STEP_CONFIRM_BIDDER_OPTIONS) {
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_CONFIRM_BIDDER_OPTIONS, $auctionId)
            );
        } elseif ($redirectStep === Constants\RegistrationStepStatuses::STEP_REVISE_BILLING) {
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_REVISE_BILLING, $auctionId)
            );
        } elseif ($redirectStep === Constants\RegistrationStepStatuses::STEP_TERMS_AND_CONDITIONS) {
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_TERMS_AND_CONDITIONS, $auctionId)
            );
        } elseif ($redirectStep === Constants\RegistrationStepStatuses::STEP_NO_BIDDER_PRIVILEGES) {
            $langNoBidderPrivilege = $this->getTranslator()->translate('GENERAL_NO_BIDDER_PRIVILEGE', 'general');
            $url = $this->getUrlBuilder()->build(
                OneStringParamUrlConfig::new()->forRedirect(Constants\Url::P_NOTIFICATION, $langNoBidderPrivilege)
            );
            $url = $this->getBackUrlParser()->replace($url, $backUrl);
        } elseif ($redirectStep === Constants\RegistrationStepStatuses::STEP_RENEW_BILLING) {
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_RENEW_BILLING, $auctionId)
            );
        }
        return $url;
    }

}
