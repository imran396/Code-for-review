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

namespace Sam\EntityMaker\Auction\Dto;


/**
 * Trait AuctionMakerDtoHelperAwareTrait
 * @package Sam\EntityMaker\Auction\Dto
 */
trait AuctionMakerDtoHelperAwareTrait
{
    protected ?AuctionMakerDtoHelper $auctionMakerDtoHelper = null;

    /**
     * @return AuctionMakerDtoHelper
     */
    protected function getAuctionMakerDtoHelper(): AuctionMakerDtoHelper
    {
        if ($this->auctionMakerDtoHelper === null) {
            $this->auctionMakerDtoHelper = AuctionMakerDtoHelper::new();
        }
        return $this->auctionMakerDtoHelper;
    }

    /**
     * @param AuctionMakerDtoHelper $auctionMakerDtoHelper
     * @return static
     * @internal
     */
    public function setAuctionMakerDtoHelper(AuctionMakerDtoHelper $auctionMakerDtoHelper): static
    {
        $this->auctionMakerDtoHelper = $auctionMakerDtoHelper;
        return $this;
    }
}
