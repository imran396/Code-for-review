<?php
/**
 * SAM-3528 : Captcha alternative on other pages
 * https://bidpath.atlassian.net/browse/SAM-3528
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Captcha\Alternative\Validate;

/**
 * Trait AlternativeCaptchaValidatorAwareTrait
 * @package Sam\Security\Captcha\Alternative\Validate
 */
trait AlternativeCaptchaValidatorAwareTrait
{
    protected ?AlternativeCaptchaValidator $alternativeCaptchaValidator = null;

    /**
     * @return AlternativeCaptchaValidator
     */
    protected function getAlternativeCaptchaValidator(): AlternativeCaptchaValidator
    {
        if ($this->alternativeCaptchaValidator === null) {
            $this->alternativeCaptchaValidator = AlternativeCaptchaValidator::new();
        }
        return $this->alternativeCaptchaValidator;
    }

    /**
     * @param AlternativeCaptchaValidator $alternativeCaptchaValidator
     * @return static
     * @internal
     */
    public function setAlternativeCaptchaValidator(AlternativeCaptchaValidator $alternativeCaptchaValidator): static
    {
        $this->alternativeCaptchaValidator = $alternativeCaptchaValidator;
        return $this;
    }
}
