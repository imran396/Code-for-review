<?php
/**
 * SAM-6367: Continue to refactor "PDF Prices Realized" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\RealizedPrice\Pdf\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Report\Auction\RealizedPrice\Pdf\Path\AuctionRealizedPricePdfPathResolverCreateTrait;
use Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Prepare\DataPreparer;
use Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Builder;

/**
 * Class AuctionRealizedPricePdfProducer
 * Now we create a realized prices pdf report file using a this class.
 * It (in produce() method) split a producing process to a 5 separate and simple stages:
 *
 * 1 - Validation for auction existence. (We can not create any report for non-existence auction)
 * 2 - interaction with AuctionRealizedPricePdfPathResolver and getting from them a pdf file root path.
 * 3 - Prepare pdf report data. build a report title and report body.
 * \Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Prepare\DataPreparer used at this stage.
 * 4 - build a pdf realized  prices report file with prepared data at previous stage and save it in
 * a file system. \Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Builder used at this stage.
 * 5 - Place a generated (at stage 4) pdf file to a master file system (if remote file system enabled).
 * used \Sam\File\Manage\FileManagerCreateTrait::createFileManager at this stage.
 *
 * @package Sam\Report\Auction\RealizedPrice\Pdf\Save
 */
class AuctionRealizedPricePdfProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionRealizedPricePdfPathResolverCreateTrait;
    use FileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
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
        $pathResolver = $this->createAuctionRealizedPricePdfPathResolver();
        $fileRootPath = $pathResolver->detectFileRootPath($auction);

        // Prepare pdf data
        $dataPreparer = DataPreparer::new()->construct($auction);
        $title = $dataPreparer->buildTitle();
        $builderBodyData = $dataPreparer->prepareBuilderData();

        // Build pdf catalog to file
        $builder = Builder::new()->construct($auction->AccountId);
        $builder->build($fileRootPath, $title, $builderBodyData);

        // Place generated file to master fs (if remote fs enabled)
        $fileManager = $this->createFileManager();
        $filePath = $pathResolver->detectFilePath($auction);
        $fileManager->put($fileRootPath, $filePath);

        return true;
    }
}
