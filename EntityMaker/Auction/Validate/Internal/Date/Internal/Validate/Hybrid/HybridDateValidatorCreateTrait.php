<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid;

/**
 * Trait HybridDateValidatorCreateTrait
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid
 */
trait HybridDateValidatorCreateTrait
{
    protected ?HybridDateValidator $hybridDateValidator = null;

    /**
     * @return HybridDateValidator
     */
    protected function createHybridDateValidator(): HybridDateValidator
    {
        return $this->hybridDateValidator ?: HybridDateValidator::new();
    }

    /**
     * @param HybridDateValidator $hybridDateValidator
     * @return static
     * @internal
     */
    public function setHybridDateValidator(HybridDateValidator $hybridDateValidator): static
    {
        $this->hybridDateValidator = $hybridDateValidator;
        return $this;
    }
}
