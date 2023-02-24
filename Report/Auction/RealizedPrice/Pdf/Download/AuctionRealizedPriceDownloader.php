<?php
/**
 * SAM-4638: Refactor PDF Prices Realized
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\RealizedPrice\Pdf\Download;

use Auction;
use Sam\Report\Auction\RealizedPrice\Pdf\Path\AuctionRealizedPricePdfPathResolverCreateTrait;
use Sam\Report\Base\Pdf\PdfReportDownloaderBase;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * This class is responsible for sending the client auction realized price report in pdf format via php.
 *
 * Class AuctionRealizedPriceDownloader
 * @package Sam\Report\Auction\RealizedPrice\Pdf\Download
 * @method Auction getAuction(bool $isReadOnlyDb = false) - availability is checked at validate method
 */
class AuctionRealizedPriceDownloader extends PdfReportDownloaderBase
{
    use AuctionAwareTrait;
    use AuctionRealizedPricePdfPathResolverCreateTrait;

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
     * @return bool
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
    protected function makeOutputFileName(): string
    {
        $fileName = $this->createAuctionRealizedPricePdfPathResolver()->detectFileName($this->getAuction());
        return $fileName;
    }

    /**
     * @inheritDoc
     */
    protected function detectFileRootPath(): string
    {
        $filePath = $this->createAuctionRealizedPricePdfPathResolver()->detectFileRootPath($this->getAuction());
        return $filePath;
    }
}
