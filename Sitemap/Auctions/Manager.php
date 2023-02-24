<?php
/**
 * It is the manager class for managing sitemap index xml file.
 * SAM-3554: Sitemap
 * https://bidpath.atlassian.net/browse/SAM-3554
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\Auctions;

use Exception;
use Sam\Auction\AuctionList\DataSourceMysql;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sitemap\Base\Generator as BaseGenerator;
use Sam\Sitemap\Base\Reader\DataReader;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Manager
 * @package Sam\Sitemap\Auctions
 */
class Manager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use FilterAccountAwareTrait;
    use SystemAccountAwareTrait;

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
                'account_id',
                'created_on',
                'id',
                'modified_on',
            ]
        );
        $dataSource->setSystemAccountId($this->getSystemAccountId());
        $dataSource->filterAccountId($this->getFilterAccountId());
        $dataSource->filterPublished(true);
        $dataSource->filterAuctionStatuses(Constants\Auction::$availableAuctionStatuses);
        $dataSource->filterAuctionAccessCheck([Constants\Auction::ACCESS_RESTYPE_AUCTION_VISIBILITY]);
        $dataSource->setOrder('auction_date DESC');
        $reader = DataReader::new()
            ->setDataSource($dataSource);

        // init output builder
        $builder = Builder::new();
        if ($this->getFilterAccountId()) {
            $builder->setAccountId($this->getFilterAccountId());
        }
        // generate result
        $output = BaseGenerator::new()
            ->setReader($reader)
            ->setBuilder($builder)
            ->generate();
        if ($output) {
            $this->cache($output);
        }
        return $output;
    }

    /**
     * Get sitemap index xml file path for account from sitemap cache directory.
     * @return string
     */
    public function getFilePath(): string
    {
        $path = substr(path()->docRoot(), strlen(path()->sysRoot())) . "/sitemap/cache";
        if ($this->cfg()->get('core->sitemap->enableSingleIndex')) {
            $filePath = $path . '/single-index.xml';
        } else {
            $filePath = $path . '/index-' . $this->getFilterAccountId() . '.xml';
        }
        return $filePath;
    }

    /**
     * Save site map index file into sitemap cache directory.
     * @param string $output
     * @return void
     */
    public function cache(string $output): void
    {
        $filePath = $this->getFilePath();
        $this->createFileManager()->write($output, $filePath);
    }

    /**
     * Delete static sitemap index file from sitemap cache directory based on file path.
     * @return void
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
