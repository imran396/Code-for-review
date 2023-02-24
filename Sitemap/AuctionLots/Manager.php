<?php
/**
 * It is the manager class for managing xml file.
 * SAM-3554: Sitemap
 * https://bidpath.atlassian.net/browse/SAM-3554
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis,Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\AuctionLots;

use Exception;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Sitemap\AuctionLots\Internal\Load\DataSourceMysql;
use Sam\Sitemap\Base;
use Sam\Sitemap\Base\Reader\DataReader;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class Manager
 * @package Sam\Sitemap\AuctionLots
 */
class Manager extends CustomizableClass
{
    use AuctionAwareTrait;
    use FileManagerCreateTrait;

    /**
     * Class instantiation method.
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * It is wrapper for reading data, building and caching xml file.
     * @return string
     */
    public function generate(): string
    {
        // init source reader
        $dataSource = DataSourceMysql::new();
        $dataSource->setResultSetFields(
            [
                'id',
                'lot_created',
                'lot_modified',
                'lot_seo_url',
            ]
        );
        $auction = $this->getAuction();
        if (!$auction) {
            log_error("Available auction not found" . composeSuffix(['a' => $this->getAuctionId()]));
            return '';
        }

        $dataSource->setSystemAccountId($auction->AccountId);
        $dataSource->filterAuctionIds([$auction->Id]);
        $dataSource->filterAuctionPublished(true);
        $dataSource->filterPublished(true);
        $dataSource->filterAuctionAccessCheck([Constants\Auction::ACCESS_RESTYPE_AUCTION_VISIBILITY]);
        if ($auction->isTimed()) {
            if ($auction->OnlyOngoingLots) {
                $dataSource->enableOnlyOngoingLotsFilter(true);
            } elseif ($auction->NotShowUpcomingLots) {
                $dataSource->enableNotShowUpcomingLotsFilter(true);
            }
        }
        $reader = DataReader::new()
            ->setDataSource($dataSource);
        // init output builder
        $builder = Builder::new();
        $builder->setAuction($auction);
        // generate result
        $output = Base\Generator::new()
            ->setReader($reader)
            ->setBuilder($builder)
            ->generate();

        if ($output) {
            $this->cache($output);
        }

        return $output;
    }

    /**
     * Get xml file path for auction from sitemap cache directory.
     * @return string
     */
    public function getFilePath(): string
    {
        $path = substr(path()->docRoot(), strlen(path()->sysRoot())) . "/sitemap/cache";
        $filePath = $path . '/auction-' . $this->getAuctionId() . '.xml';
        return $filePath;
    }

    /**
     * Save site map file into sitemap cache directory.
     * @param string $output
     */
    public function cache(string $output): void
    {
        $filePath = $this->getFilePath();
        $this->createFileManager()->write($output, $filePath);
    }

    /**
     * Delete static sitemap file from sitemap cache directory based on file path.
     */
    public function dropCached(): void
    {
        $filePath = $this->getFilePath();
        try {
            $this->createFileManager()->delete($filePath);
        } catch (Exception) {
            // file absent
        }
    }
}
