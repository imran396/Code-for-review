<?php
/**
 * SAM-3103: bulk vs piecemeal and buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Internal\Validate\BulkGroup;

/**
 * Trait LotBulkGroupToBuyNowValidatorCreateTrait
 * @package Sam\EntityMaker\AuctionLot\Internal\Validate\BulkGroup
 * @internal
 */
trait LotBulkGroupToBuyNowValidatorCreateTrait
{
    protected ?LotBulkGroupToBuyNowValidator $lotBulkGroupToBuyNowValidator = null;

    /**
     * @return LotBulkGroupToBuyNowValidator
     */
    protected function createLotBulkGroupToBuyNowValidator(): LotBulkGroupToBuyNowValidator
    {
        return $this->lotBulkGroupToBuyNowValidator ?: LotBulkGroupToBuyNowValidator::new();
    }

    /**
     * @param LotBulkGroupToBuyNowValidator $lotBulkGroupToBuyNowValidator
     * @return static
     * @internal
     */
    public function setLotBulkGroupToBuyNowValidator(LotBulkGroupToBuyNowValidator $lotBulkGroupToBuyNowValidator): static
    {
        $this->lotBulkGroupToBuyNowValidator = $lotBulkGroupToBuyNowValidator;
        return $this;
    }
}
