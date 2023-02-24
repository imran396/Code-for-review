<?php
/**
 *
 * SAM-4737: General Validator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-03-08
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Validate;

/**
 * Trait GeneralValidatorAwareTrait
 * @package Sam\Validate
 */
trait GeneralValidatorAwareTrait
{
    protected ?GeneralValidator $generalValidator = null;

    /**
     * @return GeneralValidator
     */
    protected function getGeneralValidator(): GeneralValidator
    {
        if ($this->generalValidator === null) {
            $this->generalValidator = GeneralValidator::new();
        }
        return $this->generalValidator;
    }

    /**
     * @param GeneralValidator $generalValidator
     * @return static
     * @internal
     */
    public function setGeneralValidator(GeneralValidator $generalValidator): static
    {
        $this->generalValidator = $generalValidator;
        return $this;
    }
}
