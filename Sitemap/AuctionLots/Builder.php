<?php
/**
 * Build sitemap XML output special for Auction Lots sitemap
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\AuctionLots;

use Auction;
use Generator;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionDetailsCacheLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sitemap\Base\Builder\XmlBuilder;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class Builder
 * @package Sam\Sitemap\AuctionLots
 * @method Auction getAuction()
 */
class Builder extends XmlBuilder
{
    use AuctionAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionDetailsCacheLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use UrlBuilderAwareTrait;

    protected ?iterable $data = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Prepare data for XML output
     * @param array $data
     * @return array
     */
    public function prepare(array $data): array
    {
        $auction = $this->getAuction();
        if (!$auction) { // @phpstan-ignore-line
            log_error('Available auction not found when building auction lot sitemap');
            return [];
        }
        $result = [];
        $dateHelper = $this->getDateHelper();
        $entityAccountId = $auction->AccountId;
        $urlBuilder = $this->getUrlBuilder();
        [$priority, $changefreq] = $this->getPriority();
        //prepare live sale url
        if (
            $auction->LiveViewAccess === Constants\Role::VISITOR
            && $auction->isLiveOrHybrid()
            && $auction->isStartedOrPaused()
        ) {
            $seoUrl = $this->getAuctionDetailsCacheLoader()
                ->loadValue($this->getAuctionId(), Constants\AuctionDetailsCache::SEO_URL);
            $liveSaleUrl = $urlBuilder->build(
                ResponsiveLiveSaleUrlConfig::new()->forDomainRule(
                    $this->getAuctionId(),
                    $seoUrl,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $entityAccountId]
                )
            );
            $result['liveSale']['url']['loc'] = $liveSaleUrl;
            $result['liveSale']['url']['changefreq'] = $changefreq;
            $result['liveSale']['url']['lastmod'] = isset($auction->ModifiedOn)
                ? $dateHelper->formatDate($auction->ModifiedOn, 'c')
                : $dateHelper->formatDate($auction->CreatedOn, 'c');
            $result['liveSale']['url']['priority'] = $priority;
        }
        //prepare auction infoPage url
        if ($auction->AuctionInfoAccess === Constants\Role::VISITOR) {
            $seoUrl = $this->getAuctionDetailsCacheLoader()
                ->loadValue($this->getAuctionId(), Constants\AuctionDetailsCache::SEO_URL);
            $auctionInfoUrl = $urlBuilder->build(
                ResponsiveAuctionInfoUrlConfig::new()->forDomainRule(
                    $this->getAuctionId(),
                    $seoUrl,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $entityAccountId]
                )
            );
            $result['infoPage']['url']['loc'] = $auctionInfoUrl;
            $result['infoPage']['url']['changefreq'] = $changefreq;
            $result['infoPage']['url']['lastmod'] = isset($auction->ModifiedOn)
                ? $dateHelper->formatDate($auction->ModifiedOn, 'c')
                : $dateHelper->formatDate($auction->CreatedOn, 'c');
            $result['infoPage']['url']['priority'] = $priority;
        }
        //prepare lot url
        if (
            $auction->AuctionCatalogAccess === Constants\Role::VISITOR
            && $auction->LotDetailsAccess === Constants\Role::VISITOR
        ) {
            foreach ($data as $val) {
                $lotItemId = $val['id'];
                $lotSeoUrl = $val['lot_seo_url'];
                $lotDetailsUrl = $urlBuilder->build(
                    ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                        $lotItemId,
                        $this->getAuctionId(),
                        $lotSeoUrl,
                        [UrlConfigConstants::OP_ACCOUNT_ID => $entityAccountId]
                    )
                );
                $result[$lotItemId]['url']['loc'] = $lotDetailsUrl;
                $result[$lotItemId]['url']['lastmod'] = isset($val['lot_modified'])
                    ? $dateHelper->formatDate($val['lot_modified'], 'c')
                    : $dateHelper->formatDate($val['lot_created'], 'c');
                $result[$lotItemId]['url']['changefreq'] = $changefreq;
                $result[$lotItemId]['url']['priority'] = $priority;
            }
        }
        return $result;
    }

    // We can fetch all data at first and then build XML output,
    // or we can build XML output on the fly while fetching data portions

    /**
     * Return XML Document
     * @return string
     */
    public function build(): string
    {
        $output = '';
        if ($this->data) {
            $output = '<?xml version="1.0" encoding="UTF-8"?>';
            $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($this->data as $rows) {
                foreach ($rows as $row) {
                    $output .= "<url>" . "\n";
                    $output .= "<loc>" . $row['url']['loc'] . "</loc>" . "\n";
                    $output .= "<lastmod>" . $row['url']['lastmod'] . "</lastmod>" . "\n";
                    $output .= "<changefreq>" . $row['url']['changefreq'] . "</changefreq>" . "\n";
                    $output .= "<priority>" . $row['url']['priority'] . "</priority>" . "\n";
                    $output .= "</url> " . "\n";
                    $sizeInBytes = strlen($output);
                    $sizeInMB = number_format($sizeInBytes / 1048576, 1);
                    if ($sizeInMB > $this->cfg()->get('core->sitemap->maxSizeOfFile')) {
                        $output .= '</urlset>';
                        return $output;
                    }
                }
            }
            $output .= '</urlset>';
        }
        return $output;
    }

    /**
     * @return array
     */
    protected function getPriority(): array
    {
        $auction = $this->getAuction();
        if ($auction->isActive() || $auction->isStarted()) {
            $priority = '1.0';
            $changefreq = 'daily';
        } else {
            $priority = '0.5';
            $changefreq = 'monthly';
        }
        return [$priority, $changefreq];
    }

    /**
     * @param Generator|null $data
     * @return static
     */
    public function setData(?Generator $data): static
    {
        $this->data = $data;
        return $this;
    }

}
