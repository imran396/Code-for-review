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

/** @noinspection PhpPropertyNamingConventionInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpVariableNamingConventionInspection */
/** @noinspection PhpMethodNamingConventionInspection */
/** @noinspection TypeUnsafeComparisonInspection */

namespace Sam\Report\Auction\Catalog\Pdf\Save\Build\Construct;

use Fpdf\Fpdf;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\RowElement\RowElementDescriptorDto;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\RowItem\PdfPrintCatalogRowItemDto;
use Sam\Core\Constants;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\RowElement\RowElementsBuilder;

/**
 * Class PdfPrintCatalog
 * @property bool $tdbegin
 * @property int $tdwidth
 * @property int $tdheight
 * @property int $tableborder
 * @property string $tdalign
 * @property bool $tdbgcolor
 * @property array $fontlist
 * @property int $oldx
 * @property int $oldy
 *
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class PdfPrintCatalog extends FPDF
{
    use DateHelperAwareTrait;
    use TranslatorAwareTrait;

    public $title = '';
    public $col = 0; // Current column
    public $y0; //  Ordinate of column start

    //variables of html parser
    public $B;
    public $I;
    public $U;
    public $href;
    public $issetfont;
    public $issetcolor;
    public $widths;
    public $aligns;

    /** @var int */
    protected $baseFontSize = 6;

    /**
     * PdfPrintCatalog constructor.
     * @param string $title
     * @param int $fontSize
     */
    public function __construct(string $title, int $fontSize = 6)
    {
        parent::__construct();
        $this->title = $title;
        $this->FontSize = $fontSize;
        $this->baseFontSize = $fontSize;

        //Initialization
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->href = '';

        $this->tableborder = 0;
        $this->tdbegin = false;
        $this->tdwidth = 0;
        $this->tdheight = 0;
        $this->tdalign = "L";
        $this->tdbgcolor = false;

        $this->oldx = 0;
        $this->oldy = 0;

        $this->fontlist = Constants\Pdf::$fonts;
        $this->issetfont = false;
        $this->issetcolor = false;
    }

    /** Write HTML Table **/
    /**
     * Returns an associative array (keys: R,G,B) from
     * a hex html code (e.g. #3FE5AA)
     * @param string $color
     * @return array
     */
    public function rgbFromHexColor(string $color = "#000000"): array
    {
        $char1 = substr($color, 1, 2);
        $redColor = hexdec($char1);
        $char2 = substr($color, 3, 2);
        $greenColor = hexdec($char2);
        $char3 = substr($color, 5, 2);
        $blueColor = hexdec($char3);

        $outputColor = [];
        $outputColor['R'] = $redColor;
        $outputColor['G'] = $greenColor;
        $outputColor['B'] = $blueColor;
        return $outputColor;
    }

    //conversion pixel -> millimeter in 72 dpi

    /**
     * @param $html
     * @return string
     */
    public function txtEntities(string $html): string
    {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($html, $trans);
    }

    /**
     * @param string $html
     */
    public function writeHTML(string $html): void
    {
        $html = strip_tags($html, "<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><hr><td><tr><table><sup>"); //remove all unsupported tags
        //replace carriage returns by spaces
        $html = str_replace(["\n", "\t"], '', $html); //replace carriage returns by spaces
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE); //explodes the string

        foreach ($a as $i => $e) {
            if ($i % 2 === 0) {
                //Text
                if ($this->href) {
                    $this->putLink($this->href, (string)$e);
                } elseif ($this->tdbegin) {
                    if (trim($e) !== '' && $e !== "&nbsp;") {
                        //--$this->MultiCell($this->tdwidth,$this->tdheight,$e,$this->tableborder,$this->tdalign,$this->tdbgcolor);
                        $this->Cell($this->tdwidth, $this->tdheight, $e, $this->tableborder, '', $this->tdalign, $this->tdbgcolor);
                    } elseif ($e === "&nbsp;") {
                        //MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
                        //--$this->MultiCell($this->tdwidth,$this->tdheight,'',$this->tableborder,$this->tdalign,$this->tdbgcolor);
                        $this->Cell($this->tdwidth, $this->tdheight, '', $this->tableborder, '', $this->tdalign, $this->tdbgcolor);
                    }
                } else {
                    $this->Write(5, stripslashes($this->txtEntities((string)$e)));
                }
            } else {
                //Tag
                if (str_starts_with($e, '/')) {
                    $this->closeTag(strtoupper(substr($e, 1)));
                } else {
                    //Extract attributes
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = [];
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3)) {
                            $attr[strtoupper($a3[1])] = $a3[2];
                        }
                    }
                    $this->openTag($tag, $attr);
                }
            }
        }
    }

    /**
     * @param string $tag
     * @param array $attributes
     */
    public function openTag(string $tag, array $attributes): void
    {
        //Opening tag
        switch ($tag) {
            case 'SUP':
                if (!empty($attributes['SUP'])) {
                    //Set current font to 6pt
                    $this->SetFont('', '', $this->FontSize);
                    //Start 125cm plus width of cell to the right of left margin
                    //Superscript "1"
                    $this->Cell(2, 2, $attributes['SUP'], 0, 0, 'L');
                }
                break;

            case 'TABLE': // TABLE-BEGIN
                if (!empty($attributes['BORDER'])) {
                    $this->tableborder = (int)$attributes['BORDER'];
                } else {
                    $this->tableborder = 0;
                }
                break;
            case 'TR': //TR-BEGIN
                break;
            case 'TD': // TD-BEGIN
                if (!empty($attributes['WIDTH'])) {
                    $this->tdwidth = (int)((float)$attributes['WIDTH'] / 4);
                } else {
                    $this->tdwidth = 40;
                } // Set to your own width if you need bigger fixed cells
                if (!empty($attributes['HEIGHT'])) {
                    $this->tdheight = (int)((float)$attributes['HEIGHT'] / 6);
                } else {
                    $this->tdheight = 6;
                } // Set to your own height if you need bigger fixed cells
                if (!empty($attributes['ALIGN'])) {
                    $align = $attributes['ALIGN'];
                    if ($align === 'LEFT') {
                        $this->tdalign = 'L';
                    }
                    if ($align === 'CENTER') {
                        $this->tdalign = 'C';
                    }
                    if ($align === 'RIGHT') {
                        $this->tdalign = 'R';
                    }
                } else {
                    $this->tdalign = 'L';
                } // Set to your own
                if (!empty($attributes['BGCOLOR'])) {
                    $color = $this->rgbFromHexColor((string)$attributes['BGCOLOR']);
                    $this->SetFillColor($color['R'], $color['G'], $color['B']);
                    $this->tdbgcolor = true;
                }
                $this->tdbegin = true;
                break;

            case 'HR':
                if (!empty($attributes['WIDTH'])) {
                    $width = $attributes['WIDTH'];
                } else {
                    $width = $this->w - $this->lMargin - $this->rMargin;
                }
                $x = $this->GetX();
                $y = $this->GetY();
                $this->SetLineWidth(0.2);
                $this->Line($x, $y, $x + $width, $y);
                $this->SetLineWidth(0.2);
                $this->Ln(1);
                break;
            case 'STRONG':
                $this->setStyle('B', true);
                break;
            case 'EM':
                $this->setStyle('I', true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->setStyle($tag, true);
                break;
            case 'A':
                $this->href = $attributes['HREF'];
                break;
            case 'IMG':
                if (isset($attributes['SRC']) && (isset($attributes['WIDTH']) || isset($attributes['HEIGHT']))) {
                    if (!isset($attributes['WIDTH'])) {
                        $attributes['WIDTH'] = 0;
                    }
                    if (!isset($attributes['HEIGHT'])) {
                        $attributes['HEIGHT'] = 0;
                    }
                    $this->Image($attributes['SRC'], $this->GetX(), $this->GetY(), $this->px2mm($attributes['WIDTH']), $this->px2mm($attributes['HEIGHT']));
                }
                break;
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (!empty($attributes['COLOR'])) {
                    $color = $this->rgbFromHexColor((string)$attributes['COLOR']);
                    $this->SetTextColor($color['R'], $color['G'], $color['B']);
                    $this->issetcolor = true;
                }
                if (
                    isset($attributes['FACE'])
                    && in_array(strtolower($attributes['FACE']), $this->fontlist, true)
                ) {
                    $this->SetFont(strtolower($attributes['FACE']));
                    $this->issetfont = true;
                }
                if (
                    isset($attributes['FACE'])
                    && in_array(strtolower($attributes['FACE']), $this->fontlist, true)
                    && !empty($attributes['SIZE'])
                ) {
                    $this->SetFont(strtolower($attributes['FACE']), '', (int)$attributes['SIZE']);
                    $this->issetfont = true;
                }
                break;
        }
    }

    /**
     * @param string $tag
     */
    public function closeTag(string $tag): void
    {
        //Closing tag
        if ($tag === 'SUP') {
        }

        if ($tag === 'TD') { // TD-END
            $this->tdbegin = false;
            $this->tdwidth = 0;
            $this->tdheight = 0;
            $this->tdalign = "L";
            $this->tdbgcolor = false;
        }
        if ($tag === 'TR') { // TR-END
            $this->Ln();
        }
        if ($tag === 'TABLE') { // TABLE-END
            //$this->Ln();
            $this->tableborder = 0;
        }

        if ($tag === 'STRONG') {
            $tag = 'B';
        }
        if ($tag === 'EM') {
            $tag = 'I';
        }
        if (in_array($tag, ['B', 'I', 'U'], true)) {
            $this->setStyle($tag, false);
        }
        if ($tag === 'A') {
            $this->href = '';
        }
        if ($tag === 'FONT') {
            if ($this->issetcolor) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont = false;
            }
        }
    }

    /**
     * @param string $tag
     * @param bool $enable
     */
    public function setStyle(string $tag, bool $enable): void
    {
        //Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (Constants\Pdf::$fontStyles as $s) {
            if ($this->$s > 0) {
                $style .= $s;
            }
        }
        $this->SetFont('', $style);
    }

    /**
     * @param string $url
     * @param string $txt
     */
    public function putLink(string $url, string $txt): void
    {
        //Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->setStyle('U', true);
        $this->Write(5, $txt, $url);
        $this->setStyle('U', false);
        $this->SetTextColor(0);
    }

    //============= Multicell ROW ========================

    /**
     * Set the array of column widths
     * @param array $widths
     */
    public function setWidths(array $widths): void
    {
        $this->widths = $widths;
    }

    /**
     * Set the array of column alignments
     * @param array $aligns
     */
    public function setAligns(array $aligns): void
    {
        $this->aligns = $aligns;
    }

    /**
     * @param int $height
     */
    public function checkPageBreak(int $height): void
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $height > $this->PageBreakTrigger) {
            if ($this->col > 0) {
                $this->AddPage($this->CurOrientation);
                $this->setCol(0);
                $this->SetY($this->y0);
            } else {
                //go to next col
                $this->setCol(1);
                $this->SetY($this->y0);
            }
        }
    }

    /**
     * @return bool
     */
    public function AcceptPageBreak(): bool
    {
        //Method accepting or not automatic page break
        if ($this->col < 1) { // Column number
            //Go to next column
            $this->setCol($this->col + 1);
            //Set ordinate to top
            $this->SetY($this->y0);
            //Keep on page
            return false;
        }
        //Go back to first column
        $this->setCol(0);
        //Page break
        return true;
    }

    /**
     * Computes the number of lines a MultiCell of width w will take
     * @param $w
     * @param $txt
     * @return int
     */
    public function NbLines($w, $txt): int
    {
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] === "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c === "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c === ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep === -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    /**
     * Page header rendering.
     * We render two rows in header. First one is a Auction title,
     * second one splited by two columns, each of them contains report column headers.
     *
     * We setup Auction title font size as $this->FontSize + 10
     * (10px more than base font size from core->auction->pdfCatalog->baseFontSize) and Arial as font face.
     * We setup Report column headers font size same as value core->auction->pdfCatalog->baseFontSize
     * and Arial as font face.
     */
    public function Header(): void
    {
        $catalogTitleFontSize = $this->FontSize + 6;
        $this->SetFont('Arial', '', $catalogTitleFontSize);
        // Adjust header font size if we have more than one page in our pdf catalog
        if ($this->page > 1) {
            $this->SetFont('Arial', '', $catalogTitleFontSize * 1.89);
        }
        $titleWidth = $this->GetStringWidth($this->title);
        // calculate xOffset and normalize it.
        // Print long titles as a multiline string, enstead a single line.
        $a4PageWith = 210; //mm
        $xOffsetDefault = 10; //mm
        $useMultiCell = false;
        if ($titleWidth !== $a4PageWith) {
            if ($titleWidth > $a4PageWith) {
                $xOffset = $xOffsetDefault;
                $useMultiCell = true;
            } else {
                $xOffset = ($a4PageWith - $titleWidth) / 2;
            }
        } else {
            $xOffset = $xOffsetDefault;
            $useMultiCell = true;
        }
        if ($xOffset < $xOffsetDefault) {
            $xOffset = $xOffsetDefault;
            $useMultiCell = true;
        }
        $this->setCol(0);
        $this->SetX($xOffset);
        $this->SetTextColor(0, 0, 0);
        if ($useMultiCell) {
            $this->MultiCell($a4PageWith - ($xOffsetDefault * 2), 7, $this->title, 0, 'L');
        } else {
            $this->Cell($titleWidth, 9, $this->title, 0, 0, 'L');
        }

        $this->Ln(10);

        $this->SetFont('Arial', '', $this->FontSize * 1.6);
        $translator = $this->getTranslator();
        $header = [
            mb_convert_encoding($translator->translate("AUCTION_LOT", "auctions"), "CP1252", "UTF-8"),
            mb_convert_encoding($translator->translate("AUCTION_TITLE_DESCRIPTION", "auctions"), "CP1252", "UTF-8")
        ];
        $this->setCol(0);
        $this->Cell(10, 4, $header[0], 0, 0, 'L');
        $this->Cell(90, 4, $header[1], 0, 0, 'L');

        $this->setCol(1);

        $this->Cell(10, 4, $header[0], 0, 0, 'L');
        $this->Cell(90, 4, $header[1], 0, 0, 'L');

        $this->Ln(10);

        //bring it back to 0
        $this->setCol(0);

        //Save ordinate
        $this->y0 = $this->GetY();
    }

    /**
     * Set position at a given column
     * @param int $col
     */
    public function setCol(int $col): void
    {
        $this->col = $col;
        $x = 10 + ($col * 94);
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    /**
     * @param PdfPrintCatalogRowItemDto[] $catalogRows
     */
    public function itemGrid(array $catalogRows): void
    {
        $this->setWidths([10, 75,]);
        $this->setAligns(['L', 'L']);
        $this->setCol(0);
        foreach ($catalogRows as $rowItemDto) {
            $secondColumnElements = (new RowElementsBuilder())->build($rowItemDto, ['lotNumber']);
            $this->renderRow(
                [
                    $rowItemDto->lotNumber,
                    $secondColumnElements,
                ]
            );
        }
    }

    /**
     * Render concrete row with it columns content.
     * @param array $rowColumns
     */
    protected function renderRow(array $rowColumns): void
    {
        //Calculate the height of the row
        $rowCountLines = $this->calcRowCountLines($rowColumns);
        $rowHeight = 3 * $rowCountLines;
        //Issue a page break first if needed
        $this->checkPageBreak($rowHeight);

        //Draw the cells of the row
        for ($i = 0, $iMax = count($rowColumns); $i < $iMax; $i++) {
            $columnWidth = $this->widths[$i];
            $align = $this->aligns[$i] ?? 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $columnWidth, $rowHeight);

            //Print the text
            if (is_array($rowColumns[$i])) {
                /** @var RowElementDescriptorDto $elementDto */
                foreach ($rowColumns[$i] as $elementDto) {
                    // setup current possition of pointer to columnt x postion.
                    // necessary to avoid all possible text offsets in the column.
                    $this->SetX($x);
                    $this->SetFont($elementDto->fontFamily, $elementDto->fontStyle, $elementDto->fontSize);
                    $content = $elementDto->content;
                    $elementNumberLines = $this->NbLines($this->widths[$i], $content);
                    if ($elementNumberLines > 1) {
                        $this->MultiCell($columnWidth, 3, $content, 0, $align);
                    } else {
                        $this->Cell($columnWidth, 3, $content, 0, 0, $align);
                    }
                    $this->Ln();
                }
            } else {
                //reset font with default fontFamily/fontStyle/fontSize
                $this->SetFont('', '', $this->baseFontSize);
                $this->MultiCell($columnWidth, 3, $rowColumns[$i], 0, $align);
            }
            //Put the position to the right of the cell
            $this->SetXY($x + $columnWidth, $y);
        }
        //Go to the next line
        $this->Ln($rowHeight);
    }

    /**
     * Calculate row (with columns) count lines of text.
     * @param array $rowColumns
     * @return int
     */
    protected function calcRowCountLines(array $rowColumns): int
    {
        $output = 0;
        for ($i = 0, $iMax = count($rowColumns); $i < $iMax; $i++) {
            if (is_array($rowColumns[$i])) {
                $baseCountLines = count($rowColumns[$i]);
                /** @var RowElementDescriptorDto $elementDto */
                foreach ($rowColumns[$i] as $elementDto) {
                    //adjust number lines calculation with concrete fontFamily/fontSize/fontStyle
                    $this->SetFont($elementDto->fontFamily, $elementDto->fontStyle, $elementDto->fontSize);
                    // calculate number of lines.
                    $countLinesInElement = $this->NbLines($this->widths[$i], $elementDto->content);
                    if ($countLinesInElement > 1) {
                        // only when element has more than one line.
                        // If it has only one line of text, it is alredy counted at $baseCountLines initialization.
                        $baseCountLines += $countLinesInElement;
                    }
                }
            } else {
                $baseCountLines = $this->NbLines($this->widths[$i], $rowColumns[$i]);
            }
            $output = max($output, $baseCountLines);
        }
        return $output;
    }

    /**
     * @param $px
     * @return float
     */
    public function px2mm($px)
    {
        return $px * 25.4 / 72;
    }
}
