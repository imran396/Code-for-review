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


namespace Sam\Report\Auction\Catalog\Pdf\Save\Build\RowItem;

use Sam\Core\Service\CustomizableClass;

/**
 * Class PdfPrintCatalogRowItemDto
 * Immutable value object, described one certain lot item in PdfPrintCatalog.
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class PdfPrintCatalogRowItemDto extends CustomizableClass
{
    public readonly string $lotDescription;
    public readonly string $lotEstimate;
    public readonly string $lotNumber;
    public readonly string $lotName;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * PdfPrintCatalogRowItemDto public constructor.
     * @param string $lotNumber
     * @param string $lotEstimate
     * @param string $lotName
     * @param string $lotDescription
     * @return $this
     */
    public function construct(
        string $lotNumber,
        string $lotEstimate,
        string $lotName = '',
        string $lotDescription = ''
    ): static {
        $this->lotNumber = $lotNumber;
        $this->lotEstimate = trim($lotEstimate);
        $this->lotName = trim($lotName);
        $this->lotDescription = trim($lotDescription);
        return $this;
    }
}
