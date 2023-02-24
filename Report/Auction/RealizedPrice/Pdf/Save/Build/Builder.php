<?php
/**
 * SAM-6367: Continue to refactor "PDF Prices Realized" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\RealizedPrice\Pdf\Save\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Construct\RealizedPricesPdf;
use Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Construct\TcpdfRealizedPrices;
use Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Prepare\PreparedForBuilderDto;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Builder
 * @package Sam\Report\Auction\RealizedPrice\Pdf\Save\Build
 */
class Builder extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Standard (as a table) or alternate (without table) pdf layouts
     */
    protected bool $isUseAlternatePdfCatalog = false;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $entityAccountId
     * @param array $options = [
     *      'useAlternatePdfCatalog' => (bool)
     * ]
     * @return $this
     */
    public function construct(int $entityAccountId, array $options = []): static
    {
        $this->isUseAlternatePdfCatalog
            = (bool)($options['useAlternatePdfCatalog']
            ?? $this->getSettingsManager()->get(Constants\Setting::USE_ALTERNATE_PDF_CATALOG, $entityAccountId));
        return $this;
    }

    /**
     * @param string $fileRootPath
     * @param string $title
     * @param PreparedForBuilderDto $bodyData
     */
    public function build(string $fileRootPath, string $title, PreparedForBuilderDto $bodyData): void
    {
        if ($this->isUseAlternatePdfCatalog) {
            $this->buildWithAlternateLayout($fileRootPath, $title, $bodyData);
        } else {
            $this->buildWithStandardLayout($fileRootPath, $title, $bodyData);
        }
    }

    /**
     * @param string $fileRootPath
     * @param string $title
     * @param PreparedForBuilderDto $bodyData
     */
    protected function buildWithStandardLayout(string $fileRootPath, string $title, PreparedForBuilderDto $bodyData): void
    {
        $pdf = new RealizedPricesPdf($title);
        // setup body data
        $pdf::$creationDateTimeFormatted = $bodyData->creationDateTimeFormatted;
        $pdf::$date = $bodyData->auctionStartDate;
        $pdf::$count = $bodyData->count;
        $pdf->maxCount = $bodyData->maxCount;
        $pdf::$soldCount = $bodyData->soldCount;

        $pdf->AddPage();
        $pdf->Contents($bodyData->body, true);
        $this->createPdfFileRootDir($fileRootPath);
        $pdf->Output($fileRootPath, 'F');
    }

    /**
     * @param string $fileRootPath
     * @param string $title
     * @param PreparedForBuilderDto $bodyData
     */
    protected function buildWithAlternateLayout(string $fileRootPath, string $title, PreparedForBuilderDto $bodyData): void
    {
        $pdf = new TcpdfRealizedPrices();
        $pdf->SetTitle($title);
        // setup body data
        $pdf::$creationDateTimeFormatted = $bodyData->creationDateTimeFormatted;
        $pdf::$date = $bodyData->auctionStartDate;
        $pdf::$count = $bodyData->count;
        $pdf->maxCount = $bodyData->maxCount;
        $pdf::$soldCount = $bodyData->soldCount;

        $pdf->AddPage();
        $pdf->Contents($bodyData->body, true);
        $this->createPdfFileRootDir($fileRootPath);
        $pdf->Output($fileRootPath, 'F');
    }

    /**
     * @param string $fileRootPath
     */
    protected function createPdfFileRootDir(string $fileRootPath): void
    {
        try {
            LocalFileManager::new()->createDirPath($fileRootPath);
        } catch (FileException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
