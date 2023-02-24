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


namespace Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Construct;

use Sam\Lang\TranslatorAwareTrait;
use TCPDF;

/**
 * Class TcpdfRealizedPrices
 * @package Sam\Report\Auction\RealizedPrice\Pdf\Save\Construct
 */
class TcpdfRealizedPrices extends TCPDF
{
    use TranslatorAwareTrait;

    /** @var string */
    public $title;
    /** @var string */
    public static $date = '';
    /** @var int */
    public static $count = 0;
    /** @var int */
    public static $soldCount = 0;
    /** @var int */
    public $maxCount = 0;
    /** @var string */
    public static $creationDateTimeFormatted = '';

    /**
     * @var int number of columns
     * @access protected
     */
    protected $ncols = 2;

    /**
     * @var int
     * @access protected
     */
    protected $colwidth = 90;

    /**
     * @var int column
     * @access protected
     */
    protected $col = 0;

    /**
     * @var int position of the beginning of column
     * @access protected
     */
    protected $col_start_y;

    /**
     * Set position at a given column
     * @param int $col column number (from 0 to $ncols-1)
     * @access public
     */
    public function SetCol(int $col): void
    {
        $this->col = $col;
        // set space between columns
        if ($this->ncols > 1) {
            $columnSpace = round(($this->w - $this->original_lMargin - $this->original_rMargin - ($this->ncols * $this->colwidth)) / ($this->ncols - 1));
        } else {
            $columnSpace = 0;
        }
        // set X position of the current column by case
        if ($this->rtl) {
            $x = $this->w - $this->original_rMargin - ($col * ($this->colwidth + $columnSpace));
            $this->SetRightMargin($this->w - $x);
            $this->SetLeftMargin($x - $this->colwidth);
        } else {
            $x = $this->original_lMargin + ($col * ($this->colwidth + $columnSpace));
            $this->SetLeftMargin($x);
            $this->SetRightMargin($this->w - $x - $this->colwidth);
        }
        $this->x = $x;
        if ($col > 0) {
            // set Y position for the column
            $this->y = $this->col_start_y;
        }
        // fix for HTML mode
        $this->newline = true;
    }

    /**
     * Overwrites the AcceptPageBreak() method to switch between columns
     * @return bool false
     * @access public
     */
    public function AcceptPageBreak(): bool
    {
        if ($this->col < ($this->ncols - 1)) {
            // go to next column
            $this->SetCol($this->col + 1);
            $marginLines = $this->getHeaderMargin() + 2;
            for ($i = 0; $i < $marginLines; $i++) {
                $this->Ln();
            }
        } else {
            // go back to first column on the new page
            $this->AddPage();
            $this->SetCol(0);
        }
        // avoid page breaking from checkPageBreak()
        return false;
    }

    /**
     * @param string $file
     * @param bool $mode
     */
    public function Contents(string $file, bool $mode = false): void
    {
        $this->Ln(60);
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
            $this->writeHTML($txt, true, false, true, false, 'J');
        } else {
            // ------ TEXT MODE ------
            $this->Write(0, $txt, '', false, 'L', true);
        }
        $this->Ln();
        // Go back to first column
        $this->SetCol(0);
        // restore previous margin values
        $this->SetLeftMargin($lMargin);
        $this->SetRightMargin($rMargin);
    }

    public function Header(): void
    {
        $this->SetFont(PDF_FONT_NAME_MAIN, '', 20);
        $w = $this->GetStringWidth($this->title) + 6;
        $this->SetX((210 - $w) / 2);
        $this->SetTextColor(0, 0, 0);
        $this->Cell($w, 9, $this->title, 0, 0, 'L');
        $this->Ln(10);

        $tr = $this->getTranslator();

        $auctionDate = sprintf(
            '%s %s',
            $tr->translate('AUCTION_DATES', "auctions"),
            self::$date
        );
        $this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
        $this->Cell(10, 9, $auctionDate, 0, 0, 'L');
        $this->Ln(5);

        $creationDateTimeFormatted = sprintf(
            '%s %s',
            $tr->translate('AUCTION_LAST_UPDATED', "auctions"),
            self::$creationDateTimeFormatted
        );
        $this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
        $this->Cell(
            10,
            9,
            $creationDateTimeFormatted,
            0,
            0,
            'L'
        );
        $this->Ln(5);

        $lotCount = sprintf(
            '%s %s %s',
            $tr->translate('AUCTION_LOT_COUNT', "auctions"),
            self::$count,
            $tr->translate('AUCTION_LOTS', "auctions")
        );
        $this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
        $this->Cell(10, 9, $lotCount, 0, 0, 'L');

        $soldLots = sprintf(
            '%s %s %s',
            $tr->translate('AUCTION_LOTS_SOLD', "auctions"),
            self::$soldCount,
            $tr->translate('AUCTION_LOTS', "auctions")
        );
        $this->Ln(5);
        $this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
        $this->Cell(10, 9, $soldLots, 0, 0, 'L');

        $this->Ln(15);

        $this->SetFont(PDF_FONT_NAME_MAIN, '', 16);
        $this->Cell(10, 9, $tr->translate('AUCTION_REALIZED_PRICES_RESULTS', "auctions"), 0, 0, 'L');

        $this->Ln(15);
        $pdfBulletPoint = $tr->translate('AUCTIONS_PDF_BULLED_POINT', "auctions");
        $this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
        $arraySplit = preg_split('/[.]/', $pdfBulletPoint, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($arraySplit as $key => $value) {
            $this->Cell(10, 3 * $key, $value, 0, 0, 'L');
            $this->Ln(3);
        }
        $this->Ln(5);
    }
}
