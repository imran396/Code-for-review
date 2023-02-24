<?php
/**
 * SAM-6356: Unite mutual logic in Auction Pdf Catalog module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Catalog\Pdf\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Report\Auction\Catalog\Pdf\Path\AuctionPdfCatalogPathResolverCreateTrait;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\Builder;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\DataPreparer;
use Sam\Auction\Load\AuctionLoaderAwareTrait;

/**
 * Class AuctionPdfCatalogProducer
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class AuctionPdfCatalogProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionPdfCatalogPathResolverCreateTrait;
    use FileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @param int $auctionId
     * @return bool
     */
    public function produce(int $auctionId): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error('Auction not found' . composeSuffix(['a' => $auctionId]));
            return false;
        }

        // Determine file root path of resulting pdf
        $pathResolver = $this->createAuctionPdfCatalogPathResolver();
        $fileRootPath = $pathResolver->detectFileRootPath($auction);

        // Prepare pdf catalog data
        $pdfCatalogDataPreparer = DataPreparer::new()->construct($auction);
        $catalogTitle = $pdfCatalogDataPreparer->buildCatalogTitle();
        $pdfCatalogRows = $pdfCatalogDataPreparer->buildPdfPrintCatalogRows();

        // Build pdf catalog to file
        $pdfCatalogBuilder = Builder::new()->construct($auction->AccountId);
        $pdfCatalogBuilder->build($fileRootPath, $catalogTitle, $pdfCatalogRows);

        // Place generated file to master fs (if remote fs enabled)
        $fileManager = $this->createFileManager();
        $filePath = $pathResolver->detectFilePath($auction);
        $fileManager->put($fileRootPath, $filePath);

        return true;
    }
}
