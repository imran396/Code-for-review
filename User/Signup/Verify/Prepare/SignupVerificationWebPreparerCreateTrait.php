<?php
/**
 * SAM-5327: Refactor verification email module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\Verify\Prepare;

/**
 * Trait VerificationEmailSendServiceCreateTrait
 * @package Sam\User\Signup\Verify\Prepare
 */
trait SignupVerificationWebPreparerCreateTrait
{
    protected ?SignupVerificationWebPreparer $signupVerificationWebPreparer = null;

    /**
     * @return SignupVerificationWebPreparer
     */
    protected function createSignupVerificationWebPreparer(): SignupVerificationWebPreparer
    {
        $service = $this->signupVerificationWebPreparer ?: SignupVerificationWebPreparer::new();
        return $service;
    }

    /**
     * @param SignupVerificationWebPreparer $service
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSignupVerificationWebPreparer(SignupVerificationWebPreparer $service): static
    {
        $this->signupVerificationWebPreparer = $service;
        return $this;
    }
}
