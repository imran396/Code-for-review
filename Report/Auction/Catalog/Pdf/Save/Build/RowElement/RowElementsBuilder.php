<?php
/**
 * SAM-6260: PDF catalog configuration option for Lot name, lot description, font size
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-08, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Catalog\Pdf\Save\Build\RowElement;

use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\RowItem\PdfPrintCatalogRowItemDto;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Build described row elements elements: such as lot number, lot name, lot description, lot estimates.
 * We need them for rendering each row element with concrete properties, such as font size, font color, font style, font face, etc.
 * Each row element is a instance of ColumnElementDescriptor class.
 *
 * Class ColumnElementsBuilder
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class RowElementsBuilder
{
    use ConfigRepositoryAwareTrait;

    protected const ELEMENT_LOT_NUMBER = 'lotNumber';
    protected const ELEMENT_LOT_NAME = 'lotName';
    protected const ELEMENT_LOT_DESCRIPTION = 'lotDescription';
    protected const ELEMENT_LOT_ESTIMATE = 'lotEstimate';

    /**
     * Available PDF catalog elements by rendering priority
     */
    protected const AVAILABLE_ELEMENTS_BY_PRIORITY = [
        self::ELEMENT_LOT_NUMBER,
        self::ELEMENT_LOT_NAME,
        self::ELEMENT_LOT_DESCRIPTION,
        self::ELEMENT_LOT_ESTIMATE
    ];

    /**
     * font style values, how it defined and used in PDF creation library.
     */
    protected const PDF_FONT_STYLES_MAP = [
        Constants\Pdf::FONT_STYLE_BOLD => 'B',
        Constants\Pdf::FONT_STYLE_ITALIC => 'I',
        Constants\Pdf::FONT_STYLE_UNDERLINE => 'U',
        Constants\Pdf::FONT_STYLE_NORMAL => '',
    ];

    /**
     * @param PdfPrintCatalogRowItemDto $rowItemDto
     * @param string[] $excludeElements
     * @return array|RowElementDescriptorDto[]
     */
    public function build(PdfPrintCatalogRowItemDto $rowItemDto, array $excludeElements = []): array
    {
        $rowElements = [];
        foreach (self::AVAILABLE_ELEMENTS_BY_PRIORITY as $elementName) {
            if (!in_array($elementName, $excludeElements, true)) {
                $rowElement = $this->buildRowElement($elementName, $rowItemDto);
                if ($rowElement) {
                    $rowElements[] = $rowElement;
                }
            }
        }
        return $rowElements;
    }

    /**
     * @param string $elementName
     * @param PdfPrintCatalogRowItemDto $rowItemDto
     * @return RowElementDescriptorDto|null
     * null - when catalog element disabled in configuration or has empty content.
     */
    protected function buildRowElement(string $elementName, PdfPrintCatalogRowItemDto $rowItemDto): ?RowElementDescriptorDto
    {
        $rowElement = null;
        $canRender = $this->canRender($elementName, $rowItemDto);
        if ($canRender) {
            $content = $this->getElementContent($elementName, $rowItemDto);
            if ($content !== '') {
                $fontSize = $this->getElementFontSize($elementName);
                $fontFamily = $this->getElementFontFamily($elementName);
                $fontStyle = $this->getElementFontStyle($elementName);
                $rowElement = RowElementDescriptorDto::new()
                    ->construct($elementName, $content, $fontSize, $fontFamily, $fontStyle);
            }
        }
        return $rowElement;
    }

    /**
     * @param string $elementName
     * @param PdfPrintCatalogRowItemDto $rowItemDto
     * @return bool
     */
    protected function canRender(string $elementName, PdfPrintCatalogRowItemDto $rowItemDto): bool
    {
        $isElementEnabled = true;
        if ($elementName === self::ELEMENT_LOT_NUMBER) {
            $isElementEnabled = $this->cfg()->get('core->auction->pdfCatalog->element->lotNumber->enabled') ?? true;
        } elseif ($elementName === self::ELEMENT_LOT_NAME) {
            $isElementEnabled = $this->cfg()->get('core->auction->pdfCatalog->element->lotName->enabled') ?? true;
        } elseif ($elementName === self::ELEMENT_LOT_DESCRIPTION) {
            $isElementEnabled = $this->cfg()->get('core->auction->pdfCatalog->element->lotDescription->enabled') ?? true;
        } elseif ($elementName === self::ELEMENT_LOT_ESTIMATE) {
            $isElementEnabled = $this->cfg()->get('core->auction->pdfCatalog->element->lotEstimate->enabled') ?? true;
        }
        $canRender = $isElementEnabled;
        $visibleIfEqualToLotName = $this->cfg()->get('core->auction->pdfCatalog->element->lotDescription->visibleIfEqualToLotName');
        if (
            $elementName === self::ELEMENT_LOT_DESCRIPTION
            && $rowItemDto->lotDescription === $rowItemDto->lotName
            && isset($visibleIfEqualToLotName)
            && $visibleIfEqualToLotName === false
        ) {
            $canRender = false;
        }

        return $canRender;
    }

    /**
     * @param string $elementName
     * @param PdfPrintCatalogRowItemDto $rowItemDto
     * @return string
     */
    protected function getElementContent(string $elementName, PdfPrintCatalogRowItemDto $rowItemDto): string
    {
        $content = '';
        if ($elementName === self::ELEMENT_LOT_NUMBER) {
            $content = $rowItemDto->lotNumber;
        } elseif ($elementName === self::ELEMENT_LOT_NAME) {
            $content = $rowItemDto->lotName;
        } elseif ($elementName === self::ELEMENT_LOT_DESCRIPTION) {
            $content = $rowItemDto->lotDescription;
        } elseif ($elementName === self::ELEMENT_LOT_ESTIMATE) {
            $content = $rowItemDto->lotEstimate;
        }
        return $content;
    }

    /**
     * @param string $elementName
     * @return int
     */
    protected function getElementFontSize(string $elementName): int
    {
        $defaultFontSize = $this->cfg()->get('core->auction->pdfCatalog->baseFontSize');
        $fontSize = $defaultFontSize;
        if ($elementName === self::ELEMENT_LOT_NUMBER) {
            $fontSize = $this->cfg()->get('core->auction->pdfCatalog->element->lotNumber->font->size') ?? $defaultFontSize;
        } elseif ($elementName === self::ELEMENT_LOT_NAME) {
            $fontSize = $this->cfg()->get('core->auction->pdfCatalog->element->lotName->font->size') ?? $defaultFontSize;
        } elseif ($elementName === self::ELEMENT_LOT_DESCRIPTION) {
            $fontSize = $this->cfg()->get('core->auction->pdfCatalog->element->lotDescription->font->size') ?? $defaultFontSize;
        } elseif ($elementName === self::ELEMENT_LOT_ESTIMATE) {
            $fontSize = $this->cfg()->get('core->auction->pdfCatalog->element->lotEstimate->font->size') ?? $defaultFontSize;
        }
        return $fontSize;
    }

    /**
     * @param string $elementName
     * @return string
     */
    protected function getElementFontFamily(string $elementName): string
    {
        $defaultFontFamily = Constants\Pdf::FONT_DEFAULT;
        $fontFamily = $defaultFontFamily;
        if ($elementName === self::ELEMENT_LOT_NUMBER) {
            $fontFamily = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotNumber->font->family'), Constants\Pdf::$fonts) ?? $defaultFontFamily;
        } elseif ($elementName === self::ELEMENT_LOT_NAME) {
            $fontFamily = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotName->font->family'), Constants\Pdf::$fonts) ?? $defaultFontFamily;
        } elseif ($elementName === self::ELEMENT_LOT_DESCRIPTION) {
            $fontFamily = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotDescription->font->family'), Constants\Pdf::$fonts) ?? $defaultFontFamily;
        } elseif ($elementName === self::ELEMENT_LOT_ESTIMATE) {
            $fontFamily = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotEstimate->font->family'), Constants\Pdf::$fonts) ?? $defaultFontFamily;
        }
        return $fontFamily;
    }

    /**
     * @param string $elementName
     * @return string
     */
    protected function getElementFontStyle(string $elementName): string
    {
        $defaultFontStyle = '';
        $fontStyle = $defaultFontStyle;
        if ($elementName === self::ELEMENT_LOT_NUMBER) {
            $fontStyle = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotNumber->font->style'), Constants\Pdf::$fontStyles) ?? $defaultFontStyle;
        } elseif ($elementName === self::ELEMENT_LOT_NAME) {
            $fontStyle = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotName->font->style'), Constants\Pdf::$fontStyles) ?? $defaultFontStyle;
        } elseif ($elementName === self::ELEMENT_LOT_DESCRIPTION) {
            $fontStyle = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotDescription->font->style'), Constants\Pdf::$fontStyles) ?? $defaultFontStyle;
        } elseif ($elementName === self::ELEMENT_LOT_ESTIMATE) {
            $fontStyle = Cast::toString($this->cfg()->get('core->auction->pdfCatalog->element->lotEstimate->font->style'), Constants\Pdf::$fontStyles) ?? $defaultFontStyle;
        }
        // normalize font style value, how it defined and used in PDF creation library.
        if ($fontStyle !== $defaultFontStyle) {
            $fontStyle = self::PDF_FONT_STYLES_MAP[$fontStyle];
        }
        return $fontStyle;
    }
}
