<?php
/**
 * SAM-6260: PDF catalog configuration option for Lot name, lot description, font size
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Catalog\Pdf\Save\Build;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\Construct\PdfPrintCatalog;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\Construct\TcpdfPrintCatalog;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\RowItem\PdfPrintCatalogRowItemDto;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Build empty, standard (with tables) or alternative (without tables) auction pdf catalog.
 * Empty pdf catalog can be used when we can not load auction.
 *
 * Class PdfCatalogBuilder
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class Builder extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;

    protected int $baseFontSize = 10;
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
     * Public constructor.
     * @param int $entityAccountId auction's account id
     * @param array $options = [
     *  'baseFontSize' => (int),
     *  'useAlternatePdfCatalog' => (bool),
     * ]
     * @return $this
     */
    public function construct(int $entityAccountId, array $options = []): static
    {
        $this->getTranslator()->setAccountId($entityAccountId);
        $this->isUseAlternatePdfCatalog = (bool)($options['useAlternatePdfCatalog']
            ?? $this->getSettingsManager()->get(Constants\Setting::USE_ALTERNATE_PDF_CATALOG, $entityAccountId));
        $this->baseFontSize = (int)($options['baseFontSize'] ?? $this->cfg()->get('core->auction->pdfCatalog->baseFontSize'));
        return $this;
    }

    /**
     * @param string $fileRootPath pdf file root path of auction catalog
     * @param string $title title of catalog
     * @param PdfPrintCatalogRowItemDto[] $rowItems
     */
    public function build(string $fileRootPath, string $title, array $rowItems): void
    {
        if ($this->isUseAlternatePdfCatalog) {
            $this->buildAlternativeCatalog($fileRootPath, $title, $rowItems);
        } else {
            $this->buildStandardCatalog($fileRootPath, $title, $rowItems);
        }
    }

    /**
     * @param string $fileRootPath
     * @param string $title
     * @param PdfPrintCatalogRowItemDto[] $rowItems
     */
    protected function buildStandardCatalog(string $fileRootPath, string $title, array $rowItems): void
    {
        $pdf = new PdfPrintCatalog($title, $this->baseFontSize);
        $pdf->AddPage();
        $pdf->itemGrid($rowItems);

        $this->createPdfCatalogRootDir($fileRootPath);
        $pdf->Output($fileRootPath, 'F');
    }

    /**
     * @param string $fileRootPath
     * @param string $title
     * @param PdfPrintCatalogRowItemDto[] $rowItems
     */
    protected function buildAlternativeCatalog(string $fileRootPath, string $title, array $rowItems): void
    {
        $pdf = new TcpdfPrintCatalog();
        $pdf->SetTitle($title);
        $pdf->setHeaderData('', 0, $title);
        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();
        $pdf->resetColumns();
        $pdf->setEqualColumns(2, 80);
        $pdf->selectColumn();
        $pdf->SetFont('times', '', $this->baseFontSize);
        $langCatalogPdfLot = $this->getTranslator()->translate('CATALOG_PDF_LOT', 'catalog');
        $textFull = '';
        foreach ($rowItems as $rowItem) {
            $lotNum = $rowItem->lotNumber;
            $lotName = htmlentities($rowItem->lotName);
            $lotDesc = $rowItem->lotDescription !== '' ? htmlentities($rowItem->lotDescription) . "\n" : '';
            $lotEst = $rowItem->lotEstimate;
            $lotRow = "{$langCatalogPdfLot} {$lotNum}: {$lotName}\n";
            $lotRow .= "{$lotDesc}{$lotEst}\n\n";
            $textFull .= $lotRow;
        }
        $pdf->Write(0, $textFull, '', false, 'J', true, 0, false, true);
        $pdf->Ln();

        $this->createPdfCatalogRootDir($fileRootPath);
        $pdf->Output($fileRootPath, 'F');
    }

    /**
     * @param string $fileRootPath
     */
    protected function createPdfCatalogRootDir(string $fileRootPath): void
    {
        try {
            LocalFileManager::new()->createDirPath($fileRootPath);
        } catch (FileException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
