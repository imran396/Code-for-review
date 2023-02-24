<?php
/**
 * SAM-6367: Continue to refactor "PDF Prices Realized" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-23, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\RealizedPrice\Pdf\Path;

use Auction;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;

/**
 * Class AuctionRealizedPricePdfPathResolver
 * @package Sam\Report\Auction\RealizedPrice\Pdf\Path
 */
class AuctionRealizedPricePdfPathResolver extends CustomizableClass
{
    use FileManagerCreateTrait;
    use LocalFileManagerCreateTrait;
    use PathResolverCreateTrait;

    private const FILE_NAME_TPL = 'auction-prices-%s.pdf';
    private const FILE_ROOT_PATH_TPL = '%s/%s/%s';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Make name of pdf-file based on auction data
     * @param Auction $auction
     * @return string
     */
    public function detectFileName(Auction $auction): string
    {
        return $this->makeFileName((string)$auction->SaleNum, $auction->SaleNumExt);
    }

    /**
     * Make name of pdf-file based on concrete parts
     * @param string $saleNo
     * @param string $saleNoExt
     * @return string
     * #[Pure]
     */
    public function makeFileName(string $saleNo, string $saleNoExt): string
    {
        // sale# and extension should be separated by character to avoid blending of digits
        $fullSaleNo = implode('-', array_filter([$saleNo, $saleNoExt]));
        $fileName = sprintf(self::FILE_NAME_TPL, $fullSaleNo);
        return $fileName;
    }

    /**
     * Make pdf-file relative path of auction catalog based on related values
     * @param int $accountId
     * @param string $saleNo
     * @param string $saleNoExt
     * @return string
     */
    public function makeFilePath(int $accountId, string $saleNo, string $saleNoExt): string
    {
        $filePath = substr($this->makeFileRootPath($accountId, $saleNo, $saleNoExt), strlen($this->path()->sysRoot()));
        return $filePath;
    }

    /**
     * Make pdf-file root path of auction catalog based on related values
     * @param int $accountId
     * @param string $saleNo
     * @param string $saleNoExt
     * @return string
     */
    public function makeFileRootPath(int $accountId, string $saleNo, string $saleNoExt): string
    {
        $fileName = $this->makeFileName($saleNo, $saleNoExt);
        $fileRootPath = sprintf(self::FILE_ROOT_PATH_TPL, $this->path()->uploadAuctionImage(), $accountId, $fileName);
        return $fileRootPath;
    }

    /**
     * Determine pdf-file root path of auction catalog
     * @param Auction $auction
     * @return string
     */
    public function detectFileRootPath(Auction $auction): string
    {
        return $this->makeFileRootPath((int)$auction->AccountId, (string)$auction->SaleNum, $auction->SaleNumExt);
    }

    /**
     * Determine pdf-file relative path of auction catalog
     * @param Auction $auction
     * @return string
     */
    public function detectFilePath(Auction $auction): string
    {
        $filePath = substr($this->detectFileRootPath($auction), strlen($this->path()->sysRoot()));
        return $filePath;
    }

    /**
     * Check file exists in local file-system
     * @param Auction $auction
     * @return bool
     */
    public function existInLocalFs(Auction $auction): bool
    {
        $filePath = $this->detectFilePath($auction);
        $isFound = $this->createLocalFileManager()->exist($filePath);
        return $isFound;
    }

    /**
     * Check file exists in master file-system
     * @param Auction $auction
     * @return bool
     */
    public function existInMasterFs(Auction $auction): bool
    {
        $filePath = $this->detectFilePath($auction);
        try {
            $isFound = $this->createFileManager()->exist($filePath);
        } catch (FileException $e) {
            log_error($e->getMessage());
            $isFound = false;
        }
        return $isFound;
    }

}
