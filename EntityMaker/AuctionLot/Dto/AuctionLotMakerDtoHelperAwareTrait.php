<?php
/**
 * SAM-10316: Decouple DtoHelperAwareTrait from BaseMakerValidator and BaseMakerProducer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Dto;


/**
 * Trait AuctionLotMakerDtoHelperAwareTrait
 * @package Sam\EntityMaker\AuctionLot\Dto
 */
trait AuctionLotMakerDtoHelperAwareTrait
{
    protected ?AuctionLotMakerDtoHelper $auctionLotMakerDtoHelper = null;

    /**
     * @return AuctionLotMakerDtoHelper
     */
    protected function getAuctionLotMakerDtoHelper(): AuctionLotMakerDtoHelper
    {
        if ($this->auctionLotMakerDtoHelper === null) {
            $this->auctionLotMakerDtoHelper = AuctionLotMakerDtoHelper::new();
        }
        return $this->auctionLotMakerDtoHelper;
    }

    /**
     * @param AuctionLotMakerDtoHelper $auctionLotMakerDtoHelper
     * @return static
     * @internal
     */
    public function setAuctionLotMakerDtoHelper(AuctionLotMakerDtoHelper $auctionLotMakerDtoHelper): static
    {
        $this->auctionLotMakerDtoHelper = $auctionLotMakerDtoHelper;
        return $this;
    }
}
