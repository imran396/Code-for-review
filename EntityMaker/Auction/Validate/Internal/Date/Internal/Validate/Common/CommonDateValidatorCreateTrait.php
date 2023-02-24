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

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common;

/**
 * Trait CommonDateValidatorCreateTrait
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common
 */
trait CommonDateValidatorCreateTrait
{
    protected ?CommonDateValidator $commonDateValidator = null;

    /**
     * @return CommonDateValidator
     */
    protected function createCommonDateValidator(): CommonDateValidator
    {
        return $this->commonDateValidator ?: CommonDateValidator::new();
    }

    /**
     * @param CommonDateValidator $commonDateValidator
     * @return static
     * @internal
     */
    public function setCommonDateValidator(CommonDateValidator $commonDateValidator): static
    {
        $this->commonDateValidator = $commonDateValidator;
        return $this;
    }
}
