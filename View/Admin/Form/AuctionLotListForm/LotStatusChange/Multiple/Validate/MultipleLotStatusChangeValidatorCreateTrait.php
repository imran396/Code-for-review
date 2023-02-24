<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate;

/**
 * Trait MultipleLotStatusChangeValidatorCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate
 */
trait MultipleLotStatusChangeValidatorCreateTrait
{
    protected ?MultipleLotStatusChangeValidator $multipleLotStatusChangeValidator = null;

    /**
     * @return MultipleLotStatusChangeValidator
     */
    protected function createMultipleLotStatusChangeValidator(): MultipleLotStatusChangeValidator
    {
        return $this->multipleLotStatusChangeValidator ?: MultipleLotStatusChangeValidator::new();
    }

    /**
     * @param MultipleLotStatusChangeValidator $multipleLotStatusChangeValidator
     * @return static
     * @internal
     */
    public function setMultipleLotStatusChangeValidator(MultipleLotStatusChangeValidator $multipleLotStatusChangeValidator): static
    {
        $this->multipleLotStatusChangeValidator = $multipleLotStatusChangeValidator;
        return $this;
    }
}
