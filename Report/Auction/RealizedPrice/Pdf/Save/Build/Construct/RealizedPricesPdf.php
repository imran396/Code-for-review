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

use Sam\Report\Base\Pdf\PdfReportBase;

/**
 * Class Pdf_RealizedPrices
 */
class RealizedPricesPdf extends PdfReportBase
{
    /** @var string */
    public $title;

    public function Header(): void
    {
        //Page header
        $this->SetFont('Arial', '', 20);
        $this->title = mb_convert_encoding($this->title, "CP1252", "UTF-8");
        // $w = $this->GetStringWidth(wordwrap($this->title, 100, "\n", true)) + 6;
        $this->SetX(10);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(0, 6, wordwrap($this->title, 100, "\n", true), 0, 'C');

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
                $this->SetXY($z, 110);
                $this->MultiCell(17, 4, $header[0], 1, 'R', true);
                $this->SetXY($z + 18, 110);
                $this->MultiCell(17, 4, $header[1], 1, 'R', true);
                $z += 37;
                $this->maxCount -= 34;
            }
        }
        $this->y0 = $this->GetY();
        $this->SetCol(0);
        $this->SetY($this->y0);
    }

}
