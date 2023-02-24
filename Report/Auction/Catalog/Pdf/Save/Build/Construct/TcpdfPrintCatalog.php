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

namespace Sam\Report\Auction\Catalog\Pdf\Save\Build\Construct;

use TCPDF;

/**
 * Class TcpdfPrintCatalog
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class TcpdfPrintCatalog extends TCPDF
{
    /**
     * @var int number of colums
     */
    protected $ncols = 2;

    /**
     * @var int columns width
     */
    protected $colwidth = 90;

    /**
     * @var int current column
     */
    protected $col = 0;

    /**
     * @var int y position of the beginning of column
     */
    protected $col_start_y;

    /**
     * Set position at a given column
     * @param int $columnIndex number (from 0 to $ncols-1)
     * @access public
     */
    public function SetCol(int $columnIndex): void
    {
        $this->col = $columnIndex;
        // set space between columns
        if ($this->ncols > 1) {
            $columnSpace = round(($this->w - $this->original_lMargin - $this->original_rMargin - ($this->ncols * $this->colwidth)) / ($this->ncols - 1));
        } else {
            $columnSpace = 0;
        }
        // set X position of the current column by case
        if ($this->rtl) {
            $x = $this->w - $this->original_rMargin - ($columnIndex * ($this->colwidth + $columnSpace));
            $this->SetRightMargin($this->w - $x);
            $this->SetLeftMargin($x - $this->colwidth);
        } else {
            $x = $this->original_lMargin + ($columnIndex * ($this->colwidth + $columnSpace));
            $this->SetLeftMargin($x);
            $this->SetRightMargin($this->w - $x - $this->colwidth);
        }
        $this->x = $x;
        if ($columnIndex > 0) {
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
     * Write Contents of file to pdf document.
     * @param string $file
     * @param bool $mode
     */
    public function Contents(string $file, bool $mode = false): void
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

    /**
     * function is empty so that there won't be a footer
     */
    public function Footer(): void
    {
    }
}
