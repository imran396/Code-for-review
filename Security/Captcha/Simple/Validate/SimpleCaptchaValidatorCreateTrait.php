<?php
/**
 * SAM-4713 : Simple captcha validator
 * https://bidpath.atlassian.net/browse/SAM-4713
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/4/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Captcha\Simple\Validate;

/**
 * Trait SimpleCaptchaValidatorAwareTrait
 * @package Sam\Security\Captcha\Simple\Validate
 */
trait SimpleCaptchaValidatorCreateTrait
{
    /**
     * @var SimpleCaptchaValidator|null
     */
    protected ?SimpleCaptchaValidator $simpleCaptchaValidator = null;

    /**
     * @return SimpleCaptchaValidator
     */
    protected function createSimpleCaptchaValidator(): SimpleCaptchaValidator
    {
        return $this->simpleCaptchaValidator ?: SimpleCaptchaValidator::new();
    }

    /**
     * @param SimpleCaptchaValidator $simpleCaptchaValidator
     * @return static
     * @internal
     */
    public function setSimpleCaptchaValidator(SimpleCaptchaValidator $simpleCaptchaValidator): static
    {
        $this->simpleCaptchaValidator = $simpleCaptchaValidator;
        return $this;
    }
}
