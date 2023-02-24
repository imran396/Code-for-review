<?php
/**
 * SAM-9462: Lot CSV import - fix item# and lot# uniqueness check
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Validate\LotOverwriting;

/**
 * Trait LotOverwritingValidatorCreateTrait
 * @package Sam\Import\Csv\Lot\Internal\Validate\Row
 */
trait LotOverwritingValidatorCreateTrait
{
    /**
     * @var LotOverwritingValidator|null
     */
    protected ?LotOverwritingValidator $lotOverwritingValidator = null;

    /**
     * @return LotOverwritingValidator
     */
    protected function createLotOverwritingValidator(): LotOverwritingValidator
    {
        return $this->lotOverwritingValidator ?: LotOverwritingValidator::new();
    }

    /**
     * @param LotOverwritingValidator $lotOverwritingValidator
     * @return static
     * @internal
     */
    public function setLotOverwritingValidator(LotOverwritingValidator $lotOverwritingValidator): static
    {
        $this->lotOverwritingValidator = $lotOverwritingValidator;
        return $this;
    }
}
