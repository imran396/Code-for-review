<?php
/**
 * SAM-5140: Url Builder class
 * SAM-3234: Refactoring: replace hard-coded link urls
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-24, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build;

use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionLandingUrlConfig;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Auction\AbstractResponsiveSingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Base\KnownUrlConfig;
use Sam\Application\Url\Build\Internal\AuctionInfoLink\AuctionInfoLinkApplierCreateTrait;
use Sam\Application\Url\Build\Internal\AuctionSeoUrl\AuctionSeoUrlAppenderCreateTrait;
use Sam\Application\Url\Build\Internal\BackUrl\BackUrlAdjusterCreateTrait;
use Sam\Application\Url\Build\Internal\CustomFieldFileUrl\CustomFieldFileUrlPathBuilderCreateTrait;
use Sam\Application\Url\Build\Internal\DomainLink\DomainLinkRendererCreateTrait;
use Sam\Application\Url\Build\Internal\DomainRule\DomainRuleApplierCreateTrait;
use Sam\Application\Url\Build\Internal\ForceMain\MainDomainApplierCreateTrait;
use Sam\Application\Url\Build\Internal\ImageUrl\ImageUrlPathBuilderCreateTrait;
use Sam\Application\Url\Build\Internal\Landing\Auction\ResponsiveAuctionLandingUrlConfigCompleterCreateTrait;
use Sam\Application\Url\Build\Internal\LotSeoUrl\LotSeoUrlAppenderCreateTrait;
use Sam\Application\Url\Build\Internal\BackPage\BackPageUrlDetectorCreateTrait;
use Sam\Application\Url\Build\Internal\SoundUrl\SoundUrlPathBuilderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class UrlBuilder
 * @package Sam\Application\Url\Build
 */
class UrlBuilder extends CustomizableClass implements UrlBuilderInterface
{
    use AuctionInfoLinkApplierCreateTrait;
    use AuctionSeoUrlAppenderCreateTrait;
    use BackPageUrlDetectorCreateTrait;
    use BackUrlAdjusterCreateTrait;
    use BackUrlParserAwareTrait;
    use CustomFieldFileUrlPathBuilderCreateTrait;
    use DomainLinkRendererCreateTrait;
    use DomainRuleApplierCreateTrait;
    use ImageUrlPathBuilderCreateTrait;
    use LotSeoUrlAppenderCreateTrait;
    use MainDomainApplierCreateTrait;
    use ResponsiveAuctionLandingUrlConfigCompleterCreateTrait;
    use SoundUrlPathBuilderCreateTrait;
    use SystemAccountAwareTrait;
    use UrlAdvisorAwareTrait;
    use UrlParserAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build url according passed configuration
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    public function build(AbstractUrlConfig $urlConfig): string
    {
        $urlConfig = $this->completeLandingConfig($urlConfig);
        $url = $urlConfig->urlFilled();
        [$url, $urlConfig] = $this->completeAuctionInfoLinkUrlConfig($url, $urlConfig);
        $url = $this->processImageUrlPath($url, $urlConfig);
        $url = $this->processSoundUrlPath($url, $urlConfig);
        $url = $this->processCustomFieldFileUrlPath($url, $urlConfig);
        $url = $this->appendSeoUrl($url, $urlConfig);
        $url = $this->processFullUrl($url, $urlConfig);
        $url = $this->processBackPageUrl($url, $urlConfig);
        $url = $this->processToBackUrl($url, $urlConfig);
        $url = $this->processDomainRuleView($url, $urlConfig);
        // log_trace('Url build' . composeSuffix(['url' => $url] + $urlConfig->toArray()));
        return $url;
    }

    /**
     * Completes landing page url configs, that don't have defined url type yet.
     * It searches for exact page url config that we defined as starting page of individual auction.
     * @param AbstractUrlConfig $sourceUrlConfig
     * @return AbstractUrlConfig
     */
    protected function completeLandingConfig(AbstractUrlConfig $sourceUrlConfig): AbstractUrlConfig
    {
        if ($sourceUrlConfig instanceof ResponsiveAuctionLandingUrlConfig) {
            $targetUrlConfig = $this->createResponsiveAuctionLandingUrlConfigCompleter()
                ->construct()
                ->complete($sourceUrlConfig);
            return $targetUrlConfig;
        }
        return $sourceUrlConfig;
    }

    /**
     * Transform "Auction Info" url-config to pre-defined url config with Auction->AuctionInfoLink value,
     * in case when it is absolute or schemeless url (SAM-10520).
     * @param string $url
     * @param AbstractUrlConfig $urlConfig
     * @return array
     */
    protected function completeAuctionInfoLinkUrlConfig(string $url, AbstractUrlConfig $urlConfig): array
    {
        if (!$urlConfig instanceof ResponsiveAuctionInfoUrlConfig) {
            return [$url, $urlConfig];
        }

        $urlConfig = $this->createAuctionInfoLinkApplier()->transformUrlConfig($urlConfig);
        if ($urlConfig instanceof ResponsiveAuctionInfoUrlConfig) {
            $url = $this->createAuctionInfoLinkApplier()->apply($url, $urlConfig);
        } else { // KnownUrlConfig
            $url = $urlConfig->urlFilled();
        }
        return [$url, $urlConfig];
    }

    /**
     * Make image url path
     * @param string $urlPath
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function processImageUrlPath(string $urlPath, AbstractUrlConfig $urlConfig): string
    {
        $prefixedUrlPath = $this->createImageUrlPathBuilder()
            ->construct()
            ->build($urlPath, $urlConfig);
        return $prefixedUrlPath;
    }

    /**
     * Add modification time as query-string parameter for sound type url paths.
     * @param string $urlPath
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function processSoundUrlPath(string $urlPath, AbstractUrlConfig $urlConfig): string
    {
        return $this->createSoundUrlPathBuilder()
            ->construct()
            ->build($urlPath, $urlConfig);
    }

    /**
     * Add modification time as query-string parameter for url paths of downloading custom field files.
     * @param string $urlPath
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function processCustomFieldFileUrlPath(string $urlPath, AbstractUrlConfig $urlConfig): string
    {
        return $this->createCustomFieldFileUrlPathBuilder()
            ->construct()
            ->build($urlPath, $urlConfig);
    }

    /**
     * Additional processing for some links
     * @param string $url
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function appendSeoUrl(string $url, AbstractUrlConfig $urlConfig): string
    {
        if ($urlConfig instanceof KnownUrlConfig) {
            // Skip known url case
            return $url;
        }

        if ($urlConfig instanceof ResponsiveLotDetailsUrlConfig) {
            return $this->createLotSeoUrlAppender()
                ->construct()
                ->append($url, $urlConfig);
        }

        if ($urlConfig instanceof AbstractResponsiveSingleAuctionUrlConfig) {
            $url = $this->createAuctionSeoUrlAppender()
                ->construct()
                ->append($url, $urlConfig);
        }

        return $url;
    }

    /**
     * @param string $url
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function processFullUrl(string $url, AbstractUrlConfig $urlConfig): string
    {
        if ($urlConfig instanceof KnownUrlConfig) {
            // Skip known url case
            return $url;
        }

        if (!$urlConfig->isAbsoluteView()) {
            return $url;
        }

        if ($urlConfig->isForceMainDomain()) {
            $url = $this->createMainDomainApplier()
                ->construct()
                ->apply($url);
            return $url;
        }

        $url = $this->createDomainLinkRenderer()
            ->construct($this->getSystemAccountId())
            ->apply($url, $urlConfig);
        $url = $this->getUrlAdvisor()->completeFullUrl($url);
        return $url;
    }

    /**
     * Adding back-page url GET parameter to url for specified pages stored in Constants\Url::$backUrlPages
     * @param string $url
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function processBackPageUrl(string $url, AbstractUrlConfig $urlConfig): string
    {
        if ($urlConfig instanceof KnownUrlConfig) {
            // Skip known url case
            return $url;
        }

        if (!$urlConfig->shouldSearchBackPageParam()) {
            return $url;
        }

        $backPageUrl = $this->createBackPageUrlDetector()
            ->construct()
            ->detect($urlConfig);
        if ($backPageUrl) {
            $url = $this->getBackUrlParser()->replace($url, $backPageUrl);
        }
        return $url;
    }

    /**
     * Convert url to back-page url view
     * @param string $url
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function processToBackUrl(string $url, AbstractUrlConfig $urlConfig): string
    {
        if ($urlConfig instanceof KnownUrlConfig) {
            // Skip known url case
            return $url;
        }

        if (!$urlConfig->isBackPageView()) {
            return $url;
        }

        $url = $this->createBackUrlAdjuster()
            ->construct()
            ->adjust($url);
        return $url;
    }

    /**
     * @param string $url
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function processDomainRuleView(string $url, AbstractUrlConfig $urlConfig): string
    {
        if (
            !$urlConfig->isDomainRuleView()
            || $urlConfig->isForceMainDomain()
        ) {
            return $url;
        }

        $url = $this->createDomainRuleApplier()->composeUrl($url, $urlConfig);
        return $url;
    }
}
