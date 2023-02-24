<?php
/**
 * SAM-9177: User entity-maker - Account related issues for v3-4, v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\DirectAccount;

/**
 * Trait DirectAccountChangeValidatorCreateTrait
 * @package Sam\EntityMaker\User\Validate\Internal\DirectAccount
 */
trait DirectAccountChangeValidatorCreateTrait
{
    /**
     * @var DirectAccountChangeValidator|null
     */
    protected ?DirectAccountChangeValidator $directAccountChangeValidator = null;

    /**
     * @return DirectAccountChangeValidator
     */
    protected function createDirectAccountChangeValidator(): DirectAccountChangeValidator
    {
        return $this->directAccountChangeValidator ?: DirectAccountChangeValidator::new();
    }

    /**
     * @param DirectAccountChangeValidator $validator
     * @return $this
     * @internal
     */
    public function setDirectAccountChangeValidator(DirectAccountChangeValidator $validator): static
    {
        $this->directAccountChangeValidator = $validator;
        return $this;
    }
}
