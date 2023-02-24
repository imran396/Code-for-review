<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\Username\Internal\Detect;

/**
 * Trait UniqueUsernameLockRequirementCheckerCreateTrait
 * @package Sam\EntityMaker\User\Lock\Username\Internal
 */
trait UniqueUsernameLockRequirementCheckerCreateTrait
{
    protected ?UniqueUsernameLockRequirementChecker $uniqueUsernameLockRequirementChecker = null;

    /**
     * @return UniqueUsernameLockRequirementChecker
     */
    protected function createUniqueUsernameLockRequirementChecker(): UniqueUsernameLockRequirementChecker
    {
        return $this->uniqueUsernameLockRequirementChecker ?: UniqueUsernameLockRequirementChecker::new();
    }

    /**
     * @param UniqueUsernameLockRequirementChecker $uniqueUsernameLockRequirementChecker
     * @return static
     * @internal
     */
    public function setUniqueUsernameLockRequirementChecker(UniqueUsernameLockRequirementChecker $uniqueUsernameLockRequirementChecker): static
    {
        $this->uniqueUsernameLockRequirementChecker = $uniqueUsernameLockRequirementChecker;
        return $this;
    }
}
