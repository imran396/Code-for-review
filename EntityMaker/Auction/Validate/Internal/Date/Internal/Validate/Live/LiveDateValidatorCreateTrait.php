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

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Live;

/**
 * Trait LiveDateValidatorCreateTrait
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Live
 */
trait LiveDateValidatorCreateTrait
{
    protected ?LiveDateValidator $liveDateValidator = null;

    /**
     * @return LiveDateValidator
     */
    protected function createLiveDateValidator(): LiveDateValidator
    {
        return $this->liveDateValidator ?: LiveDateValidator::new();
    }

    /**
     * @param LiveDateValidator $liveDateValidator
     * @return static
     * @internal
     */
    public function setLiveDateValidator(LiveDateValidator $liveDateValidator): static
    {
        $this->liveDateValidator = $liveDateValidator;
        return $this;
    }
}
