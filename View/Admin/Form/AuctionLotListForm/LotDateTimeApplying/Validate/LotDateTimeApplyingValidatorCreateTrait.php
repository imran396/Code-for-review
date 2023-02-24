<?php
/**
 * SAM-10180: Extract logic of date and time assignment to auction lots collection from the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate;

/**
 * Trait LotDateTimeApplyingValidatorCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate
 */
trait LotDateTimeApplyingValidatorCreateTrait
{
    protected ?LotDateTimeApplyingValidator $lotDateTimeApplyingValidator = null;

    /**
     * @return LotDateTimeApplyingValidator
     */
    protected function createLotDateTimeApplyingValidator(): LotDateTimeApplyingValidator
    {
        return $this->lotDateTimeApplyingValidator ?: LotDateTimeApplyingValidator::new();
    }

    /**
     * @param LotDateTimeApplyingValidator $lotDateTimeApplyingValidator
     * @return static
     * @internal
     */
    public function setLotDateTimeApplyingValidator(LotDateTimeApplyingValidator $lotDateTimeApplyingValidator): static
    {
        $this->lotDateTimeApplyingValidator = $lotDateTimeApplyingValidator;
        return $this;
    }
}
