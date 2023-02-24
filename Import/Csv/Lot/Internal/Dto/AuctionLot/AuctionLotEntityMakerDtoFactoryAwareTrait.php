<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Dto\AuctionLot;


trait AuctionLotEntityMakerDtoFactoryAwareTrait
{
    /**
     * @var AuctionLotEntityMakerDtoFactory|null
     */
    protected ?AuctionLotEntityMakerDtoFactory $auctionLotEntityMakerDtoFactory = null;

    /**
     * @return AuctionLotEntityMakerDtoFactory
     */
    protected function getAuctionLotEntityMakerDtoFactory(): AuctionLotEntityMakerDtoFactory
    {
        if ($this->auctionLotEntityMakerDtoFactory === null) {
            $this->auctionLotEntityMakerDtoFactory = AuctionLotEntityMakerDtoFactory::new();
        }
        return $this->auctionLotEntityMakerDtoFactory;
    }

    /**
     * @param AuctionLotEntityMakerDtoFactory $auctionLotEntityMakerDtoFactory
     * @return static
     * @internal
     */
    public function setAuctionLotEntityMakerDtoFactory(AuctionLotEntityMakerDtoFactory $auctionLotEntityMakerDtoFactory): static
    {
        $this->auctionLotEntityMakerDtoFactory = $auctionLotEntityMakerDtoFactory;
        return $this;
    }
}
