<?php
/**
 * SAM-7959: Move \Pdf_Base to Sam namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base\Pdf;

use Fpdf\Fpdf;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class PdfReportBase
 * @package Sam\Report\Base\Pdf
 * @noinspection PhpVariableNamingConventionInspection
 * @noinspection PhpPropertyNamingConventionInspection
 * @noinspection PhpMethodNamingConventionInspection
 */
class PdfReportBase extends FPDF
{
    use TranslatorAwareTrait;

    public $title = '';
    public static $date = '';
    public static $count = 0;
    public static $soldCount = 0;
    public $col = 0; // Current column
    public $y0; //  Ordinate of column start
    public $z = 0;
    public $maxCount = 0;
    public static $creationDateTimeFormatted = '';

    //////////////////////////////////////
    //html parser
    //variables of html parser
    public $B;
    public $I;
    public $U;
    public $HREF;
    public $fontList;
    public $issetfont;
    public $issetcolor;

    //============= Multicell ROW ========================
    public $widths;
    public $aligns;

    public $tableborder;
    public $tdbegin;
    public $tdwidth;
    public $tdheight;
    public $tdalign;
    public $tdbgcolor;
    public $oldx;
    public $oldy;
    public $fontlist;

    /**
     * Pdf_Base constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        parent::__construct();
        $this->title = $title;

        //Initialization
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';

        $this->tableborder = 0;
        $this->tdbegin = false;
        $this->tdwidth = 0;
        $this->tdheight = 0;
        $this->tdalign = "L";
        $this->tdbgcolor = false;

        $this->oldx = 0;
        $this->oldy = 0;

        $this->fontlist = ["arial", "times", "courier", "helvetica", "symbol"];
        $this->issetfont = false;
        $this->issetcolor = false;
    }

    /**
     * @param string $str
     * @return string
     */
    public function stripHtml(string $str): string
    {
        //change <p>s to nl
        $str = str_replace('</p>', '', $str);
        $str = str_replace('&nbsp;', ' ', $str);

        //change brs to nl
        $str = $this->br2nl($str);

        //strip tags
        $str = strip_tags($str);

        return $str;
    }

    /**
     * @param string $string
     * @return string
     */
    public function br2nl(string $string): string
    {
        $return = preg_replace('/<br\s*\/?\s*>/i', chr(13) . chr(10), $string);
        return $return;
    }

    /** Write HTML Table **/
    //function hex2dec
    //returns an associative array (keys: R,G,B) from
    //a hex html code (e.g. #3FE5AA)
    /**
     * @param string $couleur
     * @return array
     */
    public function hex2dec(string $couleur = "#000000"): array
    {
        $R = substr($couleur, 1, 2);
        $rouge = hexdec($R);
        $V = substr($couleur, 3, 2);
        $vert = hexdec($V);
        $B = substr($couleur, 5, 2);
        $bleu = hexdec($B);
        $tbl_couleur = [];
        $tbl_couleur['R'] = $rouge;
        $tbl_couleur['G'] = $vert;
        $tbl_couleur['B'] = $bleu;
        return $tbl_couleur;
    }

    //conversion pixel -> millimeter in 72 dpi

    /**
     * @param $px
     * @return float
     */
    public function px2mm($px): float
    {
        return $px * 25.4 / 72;
    }

    /**
     * @param $html
     * @return string
     */
    public function txtentities($html): string
    {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($html, $trans);
    }

    /**
     * @param $html
     * @return string
     */
    public function entitiestxt($html): string
    {
        $trans = html_entity_decode($html, ENT_QUOTES, "ISO-8859-1");
        return $trans;
    }

    /**
     * @param string $html
     */
    public function WriteHTML(string $html): void
    {
        $html = strip_tags($html, "<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><hr><td><tr><table><sup>"); //remove all unsupported tags
        $html = str_replace("\n", '', $html); //replace carriage returns by spaces
        $html = str_replace("\t", '', $html); //replace carriage returns by spaces
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE); //explodes the string

        foreach ($a as $i => $e) {
            if ($i % 2 === 0) {
                //Text
                if ($this->HREF) {
                    $this->PutLink($this->HREF, $e);
                } elseif ($this->tdbegin) {
                    if (trim($e) !== '' && $e !== "&nbsp;") {
                        $this->Cell(17, 4, $e, 'LRTB', $this->tdalign, 0);
                        //$this->Cell($this->tdwidth,$this->tdheight,$e,$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
                    } elseif ($e === "&nbsp;") {
                        $y = $this->GetY();
                        $this->SetXY(5, $y);
                        //MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
                        $this->Cell(17, 4, '', 'LRTB', $this->tdalign, 0);
                        //$this->Cell($this->tdwidth,$this->tdheight,'',$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
                    }
                } else {
                    $this->Write(5, stripslashes($this->txtentities($e)));
                }
            } else {
                //Tag
                if ($e[0] === '/') {
                    $this->CloseTag(strtoupper(substr($e, 1)));
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
                    $this->OpenTag($tag, $attr);
                }
            }
        }
    }

    /**
     * @param $tag
     * @param $attr
     */
    public function OpenTag($tag, $attr): void
    {
        //Opening tag
        switch ($tag) {
            case 'SUP':
                if (!empty($attr['SUP'])) {
                    //Set current font to 6pt
                    $this->SetFont('', '', 6);
                    //Start 125cm plus width of cell to the right of left margin
                    //Superscript "1"
                    $this->Cell(2, 2, $attr['SUP'], 0, 0, 'L');
                }
                break;

            case 'TABLE': // TABLE-BEGIN
                if (!empty($attr['BORDER'])) {
                    $this->tableborder = $attr['BORDER'];
                } else {
                    $this->tableborder = 1;
                }
                break;
            case 'TR': //TR-BEGIN
                break;
            case 'TD': // TD-BEGIN
                if (!empty($attr['WIDTH'])) {
                    $this->tdwidth = ($attr['WIDTH'] / 4);
                } else {
                    $this->tdwidth = 20;
                } // Set to your own width if you need bigger fixed cells
                if (!empty($attr['HEIGHT'])) {
                    $this->tdheight = ($attr['HEIGHT'] / 6);
                } else {
                    $this->tdheight = 6;
                } // Set to your own height if you need bigger fixed cells
                if (!empty($attr['ALIGN'])) {
                    $align = $attr['ALIGN'];
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
                if (!empty($attr['BGCOLOR'])) {
                    $coul = $this->hex2dec($attr['BGCOLOR']);
                    $this->SetFillColor($coul['R'], $coul['G'], $coul['B']);
                    $this->tdbgcolor = true;
                }
                $this->tdbegin = true;
                break;

            case 'HR':
                if (!empty($attr['WIDTH'])) {
                    $Width = $attr['WIDTH'];
                } else {
                    $Width = $this->w - $this->lMargin - $this->rMargin;
                }
                $x = $this->GetX();
                $y = $this->GetY();
                $this->SetLineWidth(0.2);
                $this->Line($x, $y, $x + $Width, $y);
                $this->SetLineWidth(0.2);
                $this->Ln(1);
                break;
            case 'STRONG':
                $this->SetStyle('B', true);
                break;
            case 'EM':
                $this->SetStyle('I', true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag, true);
                break;
            case 'A':

                $this->HREF = !empty($attr['HREF']) ? $attr['HREF'] : '';
                break;
            case 'IMG':
                if (isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if (!isset($attr['WIDTH'])) {
                        $attr['WIDTH'] = 0;
                    }
                    if (!isset($attr['HEIGHT'])) {
                        $attr['HEIGHT'] = 0;
                    }
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), $this->px2mm($attr['WIDTH']), $this->px2mm($attr['HEIGHT']));
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
                if (
                    isset($attr['COLOR'])
                    && $attr['COLOR'] != ''
                ) {
                    $coul = $this->hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'], $coul['G'], $coul['B']);
                    $this->issetcolor = true;
                }
                if (
                    isset($attr['FACE'])
                    && in_array(strtolower($attr['FACE']), $this->fontlist, true)
                ) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont = true;
                }
                if (
                    isset($attr['FACE'])
                    && in_array(strtolower($attr['FACE']), $this->fontlist, true)
                    && isset($attr['SIZE'])
                    && $attr['SIZE'] != ''
                ) {
                    $this->SetFont(strtolower($attr['FACE']), '', $attr['SIZE']);
                    $this->issetfont = true;
                }
                break;
        }
    }

    /**
     * @param $tag
     */
    public function CloseTag($tag): void
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
        if ($tag === 'B' || $tag === 'I' || $tag === 'U') {
            $this->SetStyle($tag, false);
        }
        if ($tag === 'A') {
            $this->HREF = '';
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
     * @param $tag
     * @param bool $enable
     */
    public function SetStyle($tag, bool $enable): void
    {
        //Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (['B', 'I', 'U'] as $s) {
            if ($this->$s > 0) {
                $style .= $s;
            }
        }
        $this->SetFont('', $style);
    }

    /**
     * @param string $URL
     * @param string $txt
     */
    public function PutLink($URL, $txt): void
    {
        //Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }

    /**
     * @param int[] $w
     */
    public function SetWidths($w): void
    {
        //Set the array of column widths
        $this->widths = ArrayCast::makeIntArray($w);
    }

    /**
     * @param string[] $a
     */
    public function SetAligns($a): void
    {
        //Set the array of column alignments
        $this->aligns = ArrayCast::makeStringArray($a);
    }

    /**
     * @param array $data
     */
    public function Row($data): void
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = 3 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);

        //Draw the cells of the row
        for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
            $w = $this->widths[$i];
            $a = $this->aligns[$i] ?? 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 3, $data[$i], 1, $a, true);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    /**
     * @param string $orientation
     * @param string $format
     * @param int $rotation
     */
    public function AddPage($orientation = '', $format = '', $rotation = 0): void
    {
        //Start a new page
        // if ($this->state == 0) {
        //     $this->Open();
        // }
        $family = $this->FontFamily;
        $style = $this->FontStyle . ($this->underline ? 'U' : '');
        $size = $this->FontSizePt;
        $lw = $this->LineWidth;
        $dc = $this->DrawColor;
        $fc = $this->FillColor;
        $tc = $this->TextColor;
        $cf = $this->ColorFlag;
        if ($this->page > 0) {
            //Page footer
            $this->InFooter = true;
            $this->Footer();
            $this->InFooter = false;
            //Close page
            $this->_endpage();
        }
        //Start new page
        $this->_beginpage($orientation, $format, 0);
        //Set line cap style to square
        $this->_out('2 J');
        //Set line width
        $this->LineWidth = $lw;
        $this->_out(sprintf('%.2F w', $lw * $this->k));
        //Set font
        if ($family) {
            $this->SetFont($family, $style, $size);
        }
        //Set colors
        $this->DrawColor = $dc;
        if ($dc !== '0 G') {
            $this->_out($dc);
        }
        $this->FillColor = $fc;
        if ($fc !== '0 g') {
            $this->_out($fc);
        }
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;

        //Page header
        $this->InHeader = true;

        $this->Header();
        $this->InHeader = false;

        //Restore line width
        if ($this->LineWidth != $lw) {
            $this->LineWidth = $lw;
            $this->_out(sprintf('%.2F w', $lw * $this->k));
        }
        //Restore font
        if ($family) {
            $this->SetFont($family, $style, $size);
        }
        //Restore colors
        if ($this->DrawColor != $dc) {
            $this->DrawColor = $dc;
            $this->_out($dc);
        }
        if ($this->FillColor != $fc) {
            $this->FillColor = $fc;
            $this->_out($fc);
        }
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
    }

    /**
     * @param $h
     */
    public function CheckPageBreak($h): void
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            if ($this->col > 0) {
                $this->AddPage($this->CurOrientation);
                $this->SetCol(0);
                $this->SetY($this->y0);
            } else {
                //go to next col
                $this->SetCol(1);
                $this->SetY($this->y0);
            }
        }
    }

    /**
     * @param $w
     * @param $txt
     * @return int
     */
    public function NbLines($w, $txt): int
    {
        //Computes the number of lines a MultiCell of width w will take
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

    /** **/

    public function Header(): void
    {
        //Page header
        $this->SetFont('Arial', '', 20);
        $w = $this->GetStringWidth($this->title) + 6;
        $this->SetX((210 - $w) / 2);
        $this->SetTextColor(0, 0, 0);
        $this->Cell($w, 9, $this->title, 0, 0, 'L');

        $this->Ln(10);

        $tr = $this->getTranslator();
        $strDate = $tr->translate('AUCTION_DATES', "auctions")
            . "  " . self::$date;
        $strCount = $tr->translate('AUCTION_LOT_COUNT', "auctions")
            . "  " . self::$count . "  " . $tr->translate('AUCTION_LOTS', "auctions");
        $strSoldLots = $tr->translate('AUCTION_LOTS_SOLD', "auctions")
            . "  " . self::$soldCount . "  " . $tr->translate('AUCTION_LOTS', "auctions");
        $this->SetFont('Arial', '', 10);
        $this->Cell(10, 9, $strDate, 0, 0, 'L');

        $this->Ln(5);

        $this->SetFont('Arial', '', 10);
        $this->Cell(
            10,
            9,
            $tr->translate('AUCTION_LAST_UPDATED', "auctions")
            . "  " . self::$creationDateTimeFormatted,
            0,
            0,
            'L'
        );
        $this->Ln(5);

        $this->SetFont('Arial', '', 10);
        $this->Cell(10, 9, $strCount, 0, 0, 'L');

        $this->Ln(5);
        $this->SetFont('Arial', '', 10);
        $this->Cell(10, 9, $strSoldLots, 0, 0, 'L');

        $this->Ln(15);

        $this->SetFont('Arial', '', 16);
        $this->Cell(10, 9, $tr->translate('AUCTION_REALIZED_PRICES_RESULTS', "auctions"), 0, 0, 'L');

        $this->Ln(15);
        $strBulletPoint = $tr->translate('AUCTIONS_PDF_BULLED_POINT', "auctions");
        $this->SetFont('Arial', '', 10);
        $arraySplit = preg_split('/[.]/', $strBulletPoint, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($arraySplit as $key => $value) {
            $this->Cell(10, 3 * $key, chr(149) . "  " . $value, 0, 0, 'L');
            $this->Ln(3);
        }
        $this->Ln(5);
        $header = [
            $tr->translate('AUCTION_LOT_NUMBER', "auctions"),
            $tr->translate('AUCTION_REALIZED', "auctions")
        ];

        $this->SetTextColor(255);
        $this->SetFillColor(51, 102, 153);
        $z = 10;
        for ($i = 0; $i < 5; $i++) {
            if ($this->maxCount > 0) {
                $this->SetCol($i);
                $this->SetXY($z, 100);
                $this->MultiCell(17, 4, $header[0], 1, 'R', true);
                $this->SetXY($z + 18, 100);
                $this->MultiCell(17, 4, $header[1], 1, 'R', true);
                $z += 37;
                $this->maxCount -= 34;
            }
        }
        $this->y0 = $this->GetY();
        $this->SetCol(0);
        $this->SetY($this->y0);
    }

    /**
     *
     */
    public function Footer(): void
    {
        //Page footer
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 6);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    /**
     * @param int $col
     */
    public function SetCol(int $col): void
    {
        //Set position at a given column
        $this->col = $col;
        $x = 10 + $col * 37;
        $this->SetLeftMargin($x);
        $this->SetX($x);

        //also reset y
        //$this->SetY($this->y0);
    }

    /**
     * @return bool
     */
    public function AcceptPageBreak(): bool
    {
        //Method accepting or not automatic page break
        if ($this->col < 4) { // Column number
            //Go to next column
            $this->SetCol($this->col + 1);
            //Set ordinate to top
            $this->SetY($this->y0);

            //Keep on page
            return false;
        }

        //Go back to first column
        $this->SetCol(0);

        //Page break
        return true;
    }

    //Better table

    /**
     * @param array $data
     */
    public function ImprovedTable($data): void
    {
        $this->SetFont('Arial', '', 6);
        //Data
        foreach ($data as $row) {
            $this->Cell(10, 4, $row[0], 0, 0, 'L');
            //$str  = explode(" ", $row[1]);
            $str = $row[1];
            $tmp = '';
            $ilot = 0;
            //for($i=0; $i<=count($str); $i++) {
            for ($i = 0, $iMax = strlen($str); $i <= $iMax; $i++) {
                //$tmp .= (isset($str[$i]))?$str[$i] . ' ':'';
                $tmp .= $str[$i] ?? '';
                $tmpw = (int)$this->GetStringWidth($tmp);
                if ($tmpw >= 75) {
                    if ($ilot > 0) {
                        $this->Cell(10, 2, ' ', 0, 0, 'L');
                    }
                    $this->Cell(80, 2, $tmp, 0, 0, 'L');
                    $this->Ln();
                    $tmp = '';
                    $ilot++;
                }
            }
            if ($ilot > 0) {
                $this->Cell(10, 3, ' ', 0, 0, 'L');
            }
            $this->Cell(80, 3, $tmp, 0, 0, 'L');

            $this->Ln();

            //$str = explode(" ", $row[2]);
            $str = $row[2];
            $tmp = '';

            //for($i=0; $i<=count($str); $i++) {
            for ($i = 0, $iMax = strlen($str); $i <= $iMax; $i++) {
                //$tmp .= (isset($str[$i]))?$str[$i] . ' ':'';
                $tmp .= $str[$i] ?? '';
                $tmpw = (int)$this->GetStringWidth($tmp);
                if ($tmpw >= 75) {
                    $this->Cell(10, 2, ' ', 0, 0, 'L');
                    $this->Cell(80, 2, $tmp, 0, 0, 'L');
                    $this->Ln();
                    $tmp = '';
                }
            }
            $this->Cell(10, 2, ' ', 0, 0, 'L');
            $this->Cell(80, 2, $tmp, 0, 0, 'L');

            $this->Ln();

            $this->Cell(50, 2, ' ', 0, 0, 'L');
            $this->Cell(40, 2, $row[3], 0, 0, 'R');

            $this->Ln();
        }
        $w = [20, 60];
        //Closure line
        $this->Cell(array_sum($w));
    }

    //overrides

    //sample writetable
    /**
     * @param array $data
     */
    public function WriteTable($data): void
    {
        $this->Ln(3);
        $this->SetFont('Times', '', 6);
        $aelig = $this->entitiestxt('&aelig;');
        $html = '<table class="borderOne">
        <tr>
        <td width="25" height="30">cell 1</td><td width="200" height="30" bgcolor="#D0D0FF">cell 2</td>
        </tr>';
        for ($i = 0; $i < 1000; $i++) {
            $html .= '
            <tr>
            <td width="200" height="30">' .

                '<td>' . htmlentities("æ, ø, å [test] ", ENT_QUOTES, "ISO-8859-1") . $aelig . '</td>
            <td width="200" height="30">' .
                html_entity_decode('æ, ø, å', ENT_QUOTES, "ISO-8859-1") .
                '  �, �, � ' . html_entity_decode('&aelig; &oslash;', ENT_QUOTES, "ISO-8859-1") . ' =>' . html_entity_decode($data[0][2], ENT_QUOTES, "UTF-8") . '</td>
            </tr>';
        }

        $html .= '
        <tr>
        <td width="200" height="30">�, �, � &aelig; test �, �, �</td><td width="200" height="30">cell 4</td>
        </tr>
        </table>';

        $this->WriteHTML($html);
    }

    /**
     * @param $FontName
     */
    public function DumpFont($FontName): void
    {
        $this->AddPage();
        //Title
        $this->SetFont('Arial', '', 16);
        $this->Cell(0, 6, $FontName, 0, 1, 'C');
        //Print all characters in columns
        $this->SetCol(0);
        for ($i = 32; $i <= 255; $i++) {
            $this->SetFont('Arial', '', 14);
            $this->Cell(12, 5.5, "$i : ");
            $this->SetFont($FontName);
            $this->Cell(0, 5.5, chr($i), 0, 1);
        }
        $this->SetCol(0);
    }

    /**
     * @param array $data
     */
    public function itemGrid($data): void
    {
        $this->SetFont('Times', '', 6);

        $this->SetWidths(
            [
                10,
                70,
            ]
        );

        $this->SetAligns(['L', 'L']);

        $this->SetFont('Arial', '', 6);
        $this->SetCol(0);
        foreach ($data as $obj) {
            $this->Row(
                [
                    $obj[0],
                    $obj[1],
                ]
            );
        }
    }

    /**
     * @param $file
     * @param bool $mode
     */
    public function Contents($file, bool $mode = false): void
    {
        // store current margin values
        $lMargin = $this->lMargin;
        $rMargin = $this->rMargin;
        // get esternal file content
        $txt = $file; //file_get_contents($file, false);
        // set font
        $this->SetFont('times', '', 9);
        // set first column
        $this->SetCol(0);
        if ($mode) {
            // ------ HTML MODE ------
            //$this->WriteHTML($txt, true, false, true, false, 'J');
            $this->WriteHTML($txt);
        } else {
            // ------ TEXT MODE ------
            //$this->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);
            $this->Write(0, $txt);
        }
        $this->Ln();
        // Go back to first column
        $this->SetCol(0);
        // restore previous margin values
        $this->SetLeftMargin($lMargin);
        $this->SetRightMargin($rMargin);
    }
}
