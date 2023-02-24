<?php
/**
 * SAM-6315: Unit tests for hybrid fair warning manager
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Hybrid\FairWarning\Validate;


/**
 * Trait HybridFairWarningValidatorCreateTrait
 * @package Sam\Auction\Hybrid\FairWarning\Validate
 */
trait HybridFairWarningValidatorCreateTrait
{
    /**
     * @var HybridFairWarningValidator|null
     */
    protected ?HybridFairWarningValidator $hybridFairWarningValidator = null;

    /**
     * @return HybridFairWarningValidator
     */
    protected function createHybridFairWarningValidator(): HybridFairWarningValidator
    {
        return $this->hybridFairWarningValidator ?: HybridFairWarningValidator::new();
    }

    /**
     * @param HybridFairWarningValidator $hybridFairWarningValidator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setHybridFairWarningValidator(HybridFairWarningValidator $hybridFairWarningValidator): static
    {
        $this->hybridFairWarningValidator = $hybridFairWarningValidator;
        return $this;
    }
}
