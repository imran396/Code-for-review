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
 * Trait PasswordStrengthFormHelperAwareTrait
 * @package Sam\User\Password\Strength
 */
trait PasswordStrengthFormHelperAwareTrait
{
    protected ?FormHelper $passwordStrengthFormHelper = null;

    /**
     * @return FormHelper
     */
    protected function getPasswordStrengthFormHelper(): FormHelper
    {
        if ($this->passwordStrengthFormHelper === null) {
            $this->passwordStrengthFormHelper = FormHelper::new();
        }
        return $this->passwordStrengthFormHelper;
    }

    /**
     * @param FormHelper $passwordStrengthFormHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setPasswordStrengthFormHelper(FormHelper $passwordStrengthFormHelper): static
    {
        $this->passwordStrengthFormHelper = $passwordStrengthFormHelper;
        return $this;
    }
}
