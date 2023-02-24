<?php
/**
 * Managing lot info in static file cache
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Feb 1, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\LotInfo;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotInfoService
 * @package Sam\Rtb\LotInfo
 */
class Service extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LocalFileManagerCreateTrait;
    use LotItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    protected ?Keeper $keeper = null;
    protected ?DataBuilder $dataBuilder = null;
    protected ?float $startTs = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create lot info static file
     *
     * Create:
     * 1) it is not created or
     * 2) if the created file has a different current_lot_id than the current lot
     * 3) we could check if the file exists
     * 4) how old it is
     *
     * @param RtbCurrent $rtbCurrent
     * @return bool
     */
    public function create(RtbCurrent $rtbCurrent): bool
    {
        $this->startTs = microtime(true); // start time for benchmarking
        $auctionId = $rtbCurrent->AuctionId;
        $lotItemId = $rtbCurrent->LotItemId;

        if (!$rtbCurrent->LotItemId) {
            log_debug(
                "Skip creating rtb lot info, because running lot unset"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return false;
        }

        if (!$this->getKeeper()->check($lotItemId, $auctionId)) {
            $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
            if (!$auction) {
                log_error(
                    "Available auction not found, when creating lot info for rtb"
                    . composeSuffix(['a' => $rtbCurrent->AuctionId])
                );
                return false;
            }
            $this->getKeeper()->set($lotItemId, $auctionId);
            $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
            if (!$lotItem) {
                log_debug(
                    "Available lot item not found, when creating lot info for rtb"
                    . composeSuffix(['li' => $rtbCurrent->LotItemId, 'a' => $rtbCurrent->AuctionId])
                );
                return false;
            }
            $dataBuilder = $this->getDataBuilder()
                ->setLotItem($lotItem)
                ->setAuction($auction)
                ->construct();
            if ($dataBuilder->validate()) {
                $dataJson = $dataBuilder->build();
                return $this->save($auctionId, $dataJson);
            }
        }
        return true;
    }

    /**
     * @param int $auctionId
     */
    public function drop(int $auctionId): void
    {
        $this->getKeeper()->drop($auctionId);
    }

    /**
     * Delete files with catalog pages
     * @param int $auctionId
     */
    public function deleteStaticFile(int $auctionId): void
    {
        @unlink($this->getStaticFileRootPath($auctionId));
    }

    /**
     * Store data in static files
     * @param int $auctionId
     * @param string $dataJson
     * @return bool
     */
    protected function save(int $auctionId, string $dataJson): bool
    {
        $rootPath = path()->docRoot() . '/lot-info';
        $fileRootPath = $rootPath . '/' . $this->getStaticFileName($auctionId);
        try {
            LocalFileManager::new()->createDirPath($fileRootPath);
        } catch (FileException) {
            log_error('Error on Creating a temporary Directory');
            return false;
        }

        $fp = fopen($fileRootPath, 'wb');
        fwrite($fp, $dataJson);
        fclose($fp);
        $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);

        log_debug(composeSuffix(['Static lot-info creation total time' => (microtime(true) - $this->startTs) . 's']));
        return true;
    }

    /**
     * Return file name for static lot info at responsive side
     * @param int $auctionId
     * @return string
     */
    protected function getStaticFileName(int $auctionId): string
    {
        $fileName = 'info_mobile_' . $auctionId . '.txt';
        return $fileName;
    }

    /**
     * @param int $auctionId
     * @return string
     */
    protected function getStaticFileRootPath(int $auctionId): string
    {
        $rootPath = path()->docRoot() . '/lot-info';
        $fileRootPath = $rootPath . '/' . $this->getStaticFileName($auctionId);
        return $fileRootPath;
    }

    /**
     * @return Keeper
     */
    public function getKeeper(): Keeper
    {
        if ($this->keeper === null) {
            $this->keeper = Keeper::getInstance();
        }
        return $this->keeper;
    }

    /**
     * @param Keeper $keeper
     * @return static
     */
    public function setKeeper(Keeper $keeper): Service
    {
        $this->keeper = $keeper;
        return $this;
    }

    /**
     * @return DataBuilder
     */
    public function getDataBuilder(): DataBuilder
    {
        if ($this->dataBuilder === null) {
            $this->dataBuilder = DataBuilder::new();
        }
        return $this->dataBuilder;
    }

    /**
     * @param DataBuilder $dataBuilder
     * @return static
     */
    public function setDataBuilder(DataBuilder $dataBuilder): Service
    {
        $this->dataBuilder = $dataBuilder;
        return $this;
    }
}
