<?php
/**
 * SAM-10437: Adjust Referrer/Origin configuration for v3-6, v3-7
 * SAM-5676: Refactor Origin/Referer checking logic and implement unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate;

/**
 * Trait OriginRefererCheckValidatorCreateTrait
 * @package Sam\Application\Protect\Csrf\OriginReferer\Validate
 */
trait OriginRefererCheckValidatorCreateTrait
{
    protected ?OriginRefererCheckValidator $originRefererCheckValidator = null;

    /**
     * @return OriginRefererCheckValidator
     */
    protected function createOriginRefererCheckValidator(): OriginRefererCheckValidator
    {
        return $this->originRefererCheckValidator ?: OriginRefererCheckValidator::new();
    }

    /**
     * @param OriginRefererCheckValidator $originRefererCheckValidator
     * @return static
     * @internal
     */
    public function setOriginRefererCheckValidator(OriginRefererCheckValidator $originRefererCheckValidator): static
    {
        $this->originRefererCheckValidator = $originRefererCheckValidator;
        return $this;
    }
}
