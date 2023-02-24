<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Feed;

use Sam\Application\Controller\Responsive\Feed\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Responsive\Feed\Internal\Privilege\PrivilegeCheckerCreateTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Feed\Builder as AuctionFeedBuilder;
use Sam\Details\Lot\Feed\Builder as LotFieldBuilder;
use Sam\Infrastructure\Profiling\Web\WebProfilingLogger;
use Sam\Infrastructure\Profiling\Web\WebProfilingLoggerCreateTrait;
use Sam\Lot\Category\Feed\LotCategoryFeed;

/**
 * Class FeedOutputProducer
 * @package Sam\Application\Controller\Responsive\Feed
 */
class FeedOutputProducer extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use DataProviderCreateTrait;
    use PrivilegeCheckerCreateTrait;
    use UrlBuilderAwareTrait;
    use WebProfilingLoggerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(
        string $feedSlug,
        int $accountId,
        string $userName,
        string $password,
        bool $isProfilingEnabled = false
    ): void {
        $startTs = microtime(true);

        $feedConfig = $this->createDataProvider()->loadFeedConfig($feedSlug, $accountId, true);
        if (!$feedConfig) {
            $this->createApplicationRedirector()->pageNotFound();
        }

        if (
            $feedConfig->includeInReports
            && !$this->createPrivilegeChecker()->hasPrivilegeForManageReports($userName, $password)
        ) {
            $url = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forRedirect(Constants\Url::A_ADMIN_BASE)
            );
            $this->createApplicationRedirector()->redirect($url);
        }

        switch ($feedConfig->feedType) {
            case Constants\Feed::TYPE_AUCTIONS:
                AuctionFeedBuilder::new()
                    ->enableProfiling($isProfilingEnabled)
                    ->setFeedSlug($feedSlug)
                    ->render();
                break;
            case Constants\Feed::TYPE_LOTS:
                $feedManager = LotFieldBuilder::new()
                    ->enableProfiling($isProfilingEnabled)
                    ->setFeedSlug($feedSlug);
                $feedManager->render();
                break;
            case Constants\Feed::TYPE_CATEGORY:
                LotCategoryFeed::new()->render(['slug' => $feedSlug]);
                break;
        }
        $this->logProfiling($feedSlug, $startTs, $isProfilingEnabled);
    }

    /**
     * @param string $slug
     * @param float $startTs
     * @param bool $isProfiling
     */
    protected function logProfiling(string $slug, float $startTs, bool $isProfiling): void
    {
        $optionals = [
            WebProfilingLogger::OP_PROFILING_ENABLED => $isProfiling,
            WebProfilingLogger::OP_LIMIT_LOG_LEVEL => Constants\Debug::INFO,
        ];
        $this->createWebProfilingLogger()
            ->construct($optionals)
            ->log($startTs, "Profiling feed/{$slug}");
    }
}
