<?php
/**
 * Build sitemap XML output special for Auction list sitemap
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           08 Jan, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\Auctions;

use Generator;
use Sam\Application\Url\Build\Config\Base\KnownUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Date\DateHelper;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sitemap\Base\Builder\XmlBuilder;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;

/**
 * Class Builder
 * @package Sam\Sitemap\Auctions
 */
class Builder extends XmlBuilder
{
    use AccountAwareTrait;
    use ConfigRepositoryAwareTrait;
    use ServerRequestReaderAwareTrait;
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
        $result = [];
        $dateHelper = DateHelper::new();
        $urlBuilder = $this->getUrlBuilder();
        foreach ($data as $row) {
            $auctionId = (int)$row['id'];
            $filename = 'auction-' . $auctionId . '.xml';
            $filePath = '/sitemap/cache/' . $filename;
            $url = $urlBuilder->build(
                KnownUrlConfig::new()->forDomainRule(
                    $filePath,
                    [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
                )
            );
            $result[$auctionId]['loc'] = $url;
            $result[$auctionId]['lastmod'] = isset($row['modified_on'])
                ? $dateHelper->formatDate($row['modified_on'], 'c')
                : $dateHelper->formatDate($row['created_on'], 'c');
        }
        return $result;
    }

    /**
     * Return XML Document
     * @return string
     */
    public function build(): string
    {
        $output = '';
        if ($this->data) {
            $output = '<?xml version="1.0" encoding="UTF-8"?>';
            $output .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($this->data as $rows) {
                foreach ($rows as $row) {
                    $line = "<sitemap>" . "\n";
                    $line .= "<loc>" . $row['loc'] . "</loc>" . "\n";
                    $line .= "<lastmod>" . $row['lastmod'] . "</lastmod>" . "\n";
                    $line .= "</sitemap>" . "\n";
                    $sizeInBytes = strlen($output) + strlen($line) + strlen('</sitemapindex>');
                    if ($sizeInBytes > $this->cfg()->get('core->sitemap->maxSizeOfFile') * 1048576) {
                        $output .= '</sitemapindex>';
                        return $output;
                    }
                    $output .= $line;
                }
            }
            $output .= '</sitemapindex>';
        }
        return $output;
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
