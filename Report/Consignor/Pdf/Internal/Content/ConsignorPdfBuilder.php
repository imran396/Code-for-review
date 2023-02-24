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

namespace Sam\Report\Consignor\Pdf\Internal\Content;

use Sam\Report\Base\Pdf\PdfReportBase;

/**
 * Class ConsignorPdfBuilder
 * @package Sam\Report\Consignor\Pdf
 */
class ConsignorPdfBuilder extends PdfReportBase
{
    public ?int $auctionId = null;

    /**
     * Pdf_ConsignorReport constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        parent::__construct($title);
    }

    /**
     * Overwrite parent Header method to
     * skip drawing header for consignors Pdf report
     */
    public function Header(): void
    {
        //Page header
    }

    /**
     * @param int $h
     */
    public function Ln($h = null): void
    {
        parent::Ln($h);
        if ($this->y >= 270) {
            $this->AddPage();
        }
    }

    /**
     * @param $tag
     * @param $attr
     */
    public function OpenTag($tag, $attr): void
    {
        if ($tag === "P") {
            $this->Ln(3);
        } elseif (strtolower($tag) === "div") {
            $this->Ln(2);
        } else {
            parent::OpenTag($tag, $attr);
        }
    }

    /**
     * @param string $output
     */
    public function Process(string $output): void
    {
        $this->AddPage();
        $this->Contents($output, true);

        $this->Output(path()->uploadAuctionImage() . '/consignors-pdf-report-' . $this->auctionId . '.pdf', 'I');
    }
}
