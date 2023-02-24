<?php
/**
 * Managing html catalog in static files cache
 *
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 21, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\Manage;

use AuctionLotItem;
use RtbCurrent;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Catalog\Bidder\Render\Base\AbstractBidderCatalogRenderer;
use Sam\Rtb\Catalog\Bidder\State\BidderCatalogStateKeeper;

/**
 * Class Service
 * @package Sam\Rtb\Catalog\Bidder\Base
 */
class BidderCatalogManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    protected BidderCatalogStateKeeper $keeper;
    protected AbstractBidderCatalogRenderer $renderer;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        AbstractBidderCatalogRenderer $renderer,
        ?BidderCatalogStateKeeper $keeper = null
    ): static {
        $this->renderer = $renderer;
        $this->keeper = $keeper ?? BidderCatalogStateKeeper::getInstance();
        return $this;
    }

    /**
     * @param int $auctionId
     */
    public function drop(int $auctionId): void
    {
        $this->keeper->drop($auctionId);
    }

    /**
     * create static catalog
     * from scratch
     * if catalog structure does not exist
     * or if it does not have any items
     * or if expired (older than CatalogLifeTime seconds)
     * or static file does not exist
     * or static file on the server is expired (older than CatalogLifeTime seconds)
     *
     * @param RtbCurrent $rtbCurrent
     */
    public function create(RtbCurrent $rtbCurrent): void
    {
        $auctionId = $rtbCurrent->AuctionId;
        $startTs = microtime(true); // remember start time for benchmarking

        $destFile = $this->getMobilePageFileRootPath($auctionId, 1);
        $destFileMTime = (int)@filemtime($destFile);  // file modification date or 0 if it doesn't exist

        if ($this->keeper->needUpdate($auctionId, $destFileMTime)) {
            // store current time stamp
            $tmpTs = microtime(true);
            // create array with html structure snippets
            $catalogInfo = $this->renderer->renderCatalog($auctionId, true);
            $this->keeper->set($auctionId, $catalogInfo);

            $tmpTs = microtime(true) - $tmpTs;
            log_trace(
                'Catalog snippet structure'
                . composeSuffix(['a' => $auctionId, 'creation time' => $tmpTs . 's'])
            );

            $this->write($auctionId);
            $logData = [
                'a' => $auctionId,
                'total creation time' => (microtime(true) - $startTs) . 's',
            ];
            log_trace('Catalog' . composeSuffix($logData));
        }
    }

    /**
     * Update one row in the global catalog array
     * usually used after a lot was sold or passed
     *
     * @param AuctionLotItem $auctionLot
     */
    public function updateRow(AuctionLotItem $auctionLot): void
    {
        if (!$this->keeper->checkRowExists($auctionLot->AuctionId, $auctionLot->LotItemId)) {
            return;
        }
        $catalogRow = $this->renderer->renderRow($auctionLot);
        $this->keeper->setRow($auctionLot->AuctionId, $auctionLot->LotItemId, $catalogRow);
        $this->write($auctionLot->AuctionId);
    }

    /**
     * Merge and render catalog file from global catalog array
     *
     * @param int $auctionId auction id
     */
    public function write(int $auctionId): void
    {
        $this->deleteMobilePageFiles($auctionId);
        $htmlCatalogInfo = $this->keeper->get($auctionId);
        $catalogInfo = array_chunk($htmlCatalogInfo, $this->cfg()->get('core->rtb->catalog->pageLength'));
        $tmpTs = microtime(true);
        for ($page = 1, $pageMax = count($catalogInfo); $page <= $pageMax; $page++) {
            $line = implode($catalogInfo[$page - 1]);
            $output = <<<HTML
<table class="footable">
    <tbody id="catalog-page-$page" class="data-page">
        $line
    </tbody>
</table>
HTML;
            $fileRootPath = $this->getMobilePageFileRootPath($auctionId, $page);
            $fp = fopen($fileRootPath, 'wb');
            fwrite($fp, $output);
            fclose($fp);
            LocalFileManager::new()->applyDefaultPermissions($fileRootPath);
        }
        $tmpTs = microtime(true) - $tmpTs;
        log_trace(
            'Catalog' . composeSuffix(['a' => $auctionId]) . ' write to chunked files time: '
            . $tmpTs . 's'
        );
    }

    /**
     * Return absolute path of catalog html file for some page
     * @param int $auctionId
     * @param int $page
     * @return string
     */
    protected function getMobilePageFileRootPath(int $auctionId, int $page): string
    {
        return path()->docRoot() . '/lot-info/catalog_mobile_' . $auctionId . '_' . $page . '.html';
    }

    /**
     * Delete files with catalog pages
     * @param int $auctionId
     */
    public function deleteMobilePageFiles(int $auctionId): void
    {
        $page = 1;
        while (file_exists($this->getMobilePageFileRootPath($auctionId, $page))) {
            @unlink($this->getMobilePageFileRootPath($auctionId, $page));
            $page++;
        }
    }
}
