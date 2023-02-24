<?php
/**
 * SAM-9553: Apply ConfigRepository dependency
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Captcha\Feature;

/**
 * Trait CaptchaAvailabilityCheckerCreateTrait
 * @package Sam\Security\Captcha\Feature
 */
trait CaptchaAvailabilityCheckerCreateTrait
{
    /**
     * @var CaptchaAvailabilityChecker|null
     */
    protected ?CaptchaAvailabilityChecker $captchaAvailabilityChecker = null;

    /**
     * @return CaptchaAvailabilityChecker
     */
    protected function createCaptchaAvailabilityChecker(): CaptchaAvailabilityChecker
    {
        return $this->captchaAvailabilityChecker ?: CaptchaAvailabilityChecker::new();
    }

    /**
     * @param CaptchaAvailabilityChecker $captchaAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setCaptchaAvailabilityChecker(CaptchaAvailabilityChecker $captchaAvailabilityChecker): static
    {
        $this->captchaAvailabilityChecker = $captchaAvailabilityChecker;
        return $this;
    }
}
