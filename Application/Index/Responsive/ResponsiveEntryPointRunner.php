<?php
/**
 * Run public web application after initialization. Handle cached page.
 *
 * SAM-5677: Extract logic from web entry points index.php
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Responsive;

use Sam\Application\ApplicationAwareTrait;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Index\Base\Concrete\DbProfilingLogger;
use Sam\Application\Index\Base\Concrete\LegacyFrontController;
use Sam\Application\Index\Base\Exception\BadRequest;
use Sam\Application\Language\Detect\ApplicationLanguageDetectorCreateTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Infrastructure\Profiling\Web\WebProfilingLoggerCreateTrait;
use Sam\Infrastructure\StatisticServer\StatisticServerHttpClientCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Zend_Controller_Exception;

/**
 * Class ResponsiveEntryPointRunner
 * @package
 */
class ResponsiveEntryPointRunner extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ApplicationLanguageDetectorCreateTrait;
    use ApplicationRedirectorCreateTrait;
    use AuthIdentityManagerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CookieHelperCreateTrait;
    use FilesystemCacheManagerAwareTrait;
    use OutputBufferCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ServerRequestReaderAwareTrait;
    use StatisticServerHttpClientCreateTrait;
    use UrlParserAwareTrait;
    use WebProfilingLoggerCreateTrait;

    /** @var float|null */
    protected ?float $excStartTs = null;

    /** @var bool|null */
    protected ?bool $isCachingRequired = null;
    /**
     * @var string
     */
    protected string $cacheIdentifier = '';
    /**
     * @var bool|null
     */
    protected ?bool $isGzipRequired = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('page-cache');
        return $this;
    }

    /**
     * @internal
     */
    public function run(): void
    {
        $this->initHttpReferer();

        $isCached = $this->sendCache();
        if (!$isCached) {
            try {
                LegacyFrontController::new()
                    ->construct(Ui::new()->constructWebResponsive())
                    ->run();

                $this->saveCache();
            } catch (Zend_Controller_Exception $e) {
                // TODO: why do we suppress exception output, when it was happened during zf related initializations?
                // at least now we log this info
                log_error($e->getMessage() . composeSuffix(['code' => $e->getCode()]));
                $this->createApplicationRedirector()->processPageNotFound();
            } catch (BadRequest) {
                $this->createApplicationRedirector()->badRequest();
            }
        }

        $this->log();
    }

    /**
     * @return float
     * @noinspection PhpUnused
     */
    public function getExcStartTs(): float
    {
        if ($this->excStartTs === null) {
            $this->excStartTs = microtime(true);
        }
        return $this->excStartTs;
    }

    /**
     * @param float $excStartTs
     * @return static
     */
    public function setExcStartTs(float $excStartTs): static
    {
        $this->excStartTs = $excStartTs;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCachingRequired(): bool
    {
        if ($this->isCachingRequired === null) {
            $this->isCachingRequired = $this->detectCachingRequired();
        }
        return $this->isCachingRequired;
    }

    /**
     * @param bool $isCachingRequired
     * @return static
     * @noinspection PhpUnused
     */
    public function enableCachingRequired(bool $isCachingRequired): static
    {
        $this->isCachingRequired = $isCachingRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGzipRequired(): bool
    {
        if ($this->isGzipRequired === null) {
            $this->isGzipRequired = !empty($this->getServerRequestReader()->httpAcceptEncoding())
                && str_contains($this->getServerRequestReader()->httpAcceptEncoding(), "gzip");
        }
        return $this->isGzipRequired;
    }

    /**
     * @param bool $isGzipRequired
     * @return static
     * @noinspection PhpUnused
     */
    public function enableGzipRequired(bool $isGzipRequired): static
    {
        $this->isGzipRequired = $isGzipRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheIdentifier(): string
    {
        if (!$this->cacheIdentifier) {
            $this->cacheIdentifier = $this->buildCacheIdentifier();
        }
        return $this->cacheIdentifier;
    }

    /**
     * @param string $cacheIdentifier
     * @return static
     * @noinspection PhpUnused
     */
    public function setCacheIdentifier(string $cacheIdentifier): static
    {
        $this->cacheIdentifier = $cacheIdentifier;
        return $this;
    }

    /**
     * @return bool
     */
    protected function detectCachingRequired(): bool
    {
        $urlParser = $this->getUrlParser();
        $serverRequestReader = $this->getServerRequestReader();
        $requestUri = $serverRequestReader->requestUri();
        $is = $urlParser->inUrl(ResponsiveEntryPointConstants::$cacheRoutes, $requestUri)
            || (
                $serverRequestReader->isGet()
                && !$this->createAuthIdentityManager()->isAuthorized()
                && $urlParser->inUrl(ResponsiveEntryPointConstants::$anonymousCacheRoutes, $requestUri)
            );
        return $is;
    }

    /**
     * @return string
     */
    protected function buildCacheIdentifier(): string
    {
        $cookieHelper = $this->createCookieHelper();
        $serverRequestReader = $this->getServerRequestReader();
        $languageId = $this->createApplicationLanguageDetector()->detectActiveLanguageId();
        $cacheIdentifier = $languageId . '-' . $serverRequestReader->requestUri()
            . ($serverRequestReader->httpHost() ?: $this->cfg()->get('core->app->httpHost'));
        $cacheIdentifier = ($cookieHelper->hasMapp() ? $cookieHelper->getMapp() . '-' : '') . $cacheIdentifier;
        $cacheIdentifier = ($this->isGzipRequired() ? 'gzip-' : '') . $cacheIdentifier;
        return $cacheIdentifier;
    }

    /**
     * @return bool
     */
    protected function sendCache(): bool
    {
        $isCached = false;
        if ($this->isCachingRequired()) {
            $pageData = $this->getFilesystemCacheManager()->get($this->getCacheIdentifier());
            if ($pageData) {
                if ($this->isGzipRequired()) {
                    header("Content-Encoding: gzip");
                }
                echo $pageData;
                $isCached = true;
            }
            // start creating page content (use output buffering to save the results in the cache)
            $this->createOutputBuffer()->start();
        }
        return $isCached;
    }

    protected function saveCache(): void
    {
        if ($this->isCachingRequired()) {
            $output = $this->createOutputBuffer()->getClean();
            if ($this->cfg()->get('core->app->removeWhitespace')) {
                $output = preg_replace('/[\t ]+/', ' ', $output);
            }
            // no removing new lines for now, since it messes up inline JS that use the // comment
            //            if(PHP_REMOVE_WHITESPACE) $output = preg_replace('/[\t ]+/', ' ', str_replace(array("\r","\n"),'', $output));
            echo $output;
            if ($output !== false) {
                // if output is html/ xml append timestamp in comment
                // only  check first x characters of output for better performance on large output
                if (preg_match("/<[^<]+>/", substr($output, 0, 1000)) !== 0) {
                    // append cache generated timestamp
                    $uts = microtime(true);
                    $ts = floor($uts);
                    $ms = round($uts - $ts, 3) * 1000;
                    $tsComment = sprintf('<!-- Page cache generated %1s.%2$03d UTC -->', gmdate(Constants\Date::ISO, (int)$ts), $ms);

                    // find last tag (assumption, starts with "<")
                    $pos = strrpos($output, '<');

                    if ($pos !== false) {
                        // place TS comment before last tag
                        $output = substr_replace($output, $tsComment . '<', $pos, 1);
                    }
                }

                $cacheManager = $this->getFilesystemCacheManager();
                $cacheManager->setExtension('txt')
                    ->setNamespace('page-cache');
                if ($this->isGzipRequired()) {
                    $cacheManager->enableGzip(true);
                }

                $cacheTtl = $this->cfg()->get('core->cache->responsiveWeb->ttl');
                $cacheManager->set($this->getCacheIdentifier(), $output, $cacheTtl);
            }
        }
    }

    /**
     * Catch HTTP_REFERER in visitor's session, when he comes for the first time. Store in cookie.
     */
    protected function initHttpReferer(): void
    {
        $cookieHelper = $this->createCookieHelper();
        $serverRequestReader = $this->getServerRequestReader();
        $httpRefererFromGet = $this->getParamFetcherForGet()->getString('http_referer');
        if ($httpRefererFromGet) {
            //force referrer in session
            $cookieHelper->setHttpReferer($httpRefererFromGet);
        } elseif (
            !empty($serverRequestReader->httpReferer())
            && !$cookieHelper->hasHttpReferer()
        ) {
            $cookieHelper->setHttpReferer($serverRequestReader->httpReferer());
        }
    }

    /**
     * Log to file and to stats server
     */
    protected function log(): void
    {
        $execTime = (int)round((microtime(true) - $this->excStartTs) * 1000);
        $serverRequestReader = $this->getServerRequestReader();
        $url = $serverRequestReader->requestUri();
        $clientIp = $serverRequestReader->remoteAddr();
        $userAgent = $serverRequestReader->httpUserAgent() ?: '';

        if ($this->cfg()->get('core->statsServer->enabledForWeb')) {
            $memoryUsage = memory_get_usage(true);
            $memoryPeakUsage = memory_get_peak_usage(true);
            $httpHost = $this->cfg()->get('core->app->httpHost');
            $httpReferrer = $serverRequestReader->httpReferer();
            $ts = microtime(true);
            $this->createStatisticServerHttpClient()->report(
                $httpHost,
                $memoryUsage,
                $memoryPeakUsage,
                $execTime,
                $clientIp,
                $userAgent,
                session_id(),
                $url,
                $httpReferrer
            );
            log_debug('send to stats server' . composeSuffix(['time' => (microtime(true) - $ts) * 1000 . 'ms']));
        }

        DbProfilingLogger::new()->log();
        $this->createWebProfilingLogger()->construct()->log($this->excStartTs, 'Profiling public web');
    }
}
