<?php
/**
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
 * Trait SignupVerificationPreparerCreateTrait
 * @package Sam\User\Signup\Verify\Prepare
 */
trait SignupVerificationPreparerCreateTrait
{
    protected ?SignupVerificationPreparer $signupVerificationPreparer = null;

    /**
     * @return SignupVerificationPreparer
     */
    protected function createSignupVerificationPreparer(): SignupVerificationPreparer
    {
        $signupVerificationPreparer = $this->signupVerificationPreparer ?: SignupVerificationPreparer::new();
        return $signupVerificationPreparer;
    }

    /**
     * @param SignupVerificationPreparer $signupVerificationPreparer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSignupVerificationPreparer(SignupVerificationPreparer $signupVerificationPreparer): static
    {
        $this->signupVerificationPreparer = $signupVerificationPreparer;
        return $this;
    }
}
