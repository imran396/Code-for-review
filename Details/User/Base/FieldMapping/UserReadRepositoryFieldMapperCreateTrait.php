<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\Base\FieldMapping;

/**
 * Trait UserReadRepositoryFieldMapperCreateTrait
 * @package Sam\Details\User\Base
 */
trait UserReadRepositoryFieldMapperCreateTrait
{
    /**
     * @var UserReadRepositoryFieldMapper|null
     */
    protected ?UserReadRepositoryFieldMapper $userReadRepositoryFieldMapper = null;

    protected function createUserReadRepositoryFieldMapper(): UserReadRepositoryFieldMapper
    {
        return $this->userReadRepositoryFieldMapper ?: UserReadRepositoryFieldMapper::new();
    }

    /**
     * @internal
     */
    public function setUserReadRepositoryFieldMapper(UserReadRepositoryFieldMapper $userReadRepositoryFieldMapper): static
    {
        $this->userReadRepositoryFieldMapper = $userReadRepositoryFieldMapper;
        return $this;
    }
}
