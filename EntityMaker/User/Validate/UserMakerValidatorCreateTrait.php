<?php
/**
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate;

/**
 * Trait UserMakerValidatorCreateTrait
 * @package Sam\EntityMaker\User\Validate
 */
trait UserMakerValidatorCreateTrait
{
    /**
     * @var UserMakerValidator|null
     */
    protected ?UserMakerValidator $userMakerValidator = null;

    /**
     * @return UserMakerValidator
     */
    protected function createUserMakerValidator(): UserMakerValidator
    {
        return $this->userMakerValidator ?: UserMakerValidator::new();
    }

    /**
     * @param UserMakerValidator $userMakerValidator
     * @return static
     * @internal
     */
    public function setUserMakerValidator(UserMakerValidator $userMakerValidator): static
    {
        $this->userMakerValidator = $userMakerValidator;
        return $this;
    }
}
