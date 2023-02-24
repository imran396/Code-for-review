<?php
/**
 * SAM-4637: Refactor print catalog report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Catalog\Pdf\Download;

use Auction;
use Sam\Report\Auction\Catalog\Pdf\Path\AuctionPdfCatalogPathResolverCreateTrait;
use Sam\Report\Base\Pdf\PdfReportDownloaderBase;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * This class is responsible for sending the client auction catalog report in pdf format via php.
 *
 * Class AuctionCatalogPdfReportDownloader
 * @package Sam\Report\Auction\Catalog\Pdf\Download
 * @method Auction getAuction(bool $isReadOnlyDb = false) - availability is checked at validate method
 */
class AuctionPdfCatalogReportDownloader extends PdfReportDownloaderBase
{
    use AuctionAwareTrait;
    use AuctionPdfCatalogPathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return $this
     */
    public function construct(int $auctionId): static
    {
        $this->setAuctionId($auctionId);
        return $this;
    }

    /**
     * (@inheritDoc)
     */
    public function validate(): bool
    {
        $auction = $this->getAuction(true);
        if (!$auction) { // @phpstan-ignore-line
            log_warning(
                'Failed trying to export auction does not exist'
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return false;
        }

        return parent::validate();
    }

    /**
     * @inheritDoc
     */
    protected function detectFileRootPath(): string
    {
        return $this->createAuctionPdfCatalogPathResolver()->detectFileRootPath($this->getAuction());
    }

    /**
     * @inheritDoc
     */
    protected function makeOutputFileName(): string
    {
        return $this->createAuctionPdfCatalogPathResolver()->detectFileName($this->getAuction());
    }
}
