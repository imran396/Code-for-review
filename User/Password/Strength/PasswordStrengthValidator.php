<?php
/**
 * SAM-1238: Increased password security
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password\Strength;

/**
 * Trait PasswordStrengthValidator
 * @package Sam\User\Password\Strength
 */
trait PasswordStrengthValidator
{
    protected ?Validator $passwordStrengthValidator = null;

    /**
     * @return Validator
     */
    public function getPasswordStrengthValidator(): Validator
    {
        if ($this->passwordStrengthValidator === null) {
            $this->passwordStrengthValidator = Validator::new();
        }
        return $this->passwordStrengthValidator;
    }

    /**
     * @param Validator $passwordStrengthValidator
     * @return static
     */
    public function setPasswordStrengthValidator(Validator $passwordStrengthValidator): static
    {
        $this->passwordStrengthValidator = $passwordStrengthValidator;
        return $this;
    }
}
