<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Feed\Internal\Privilege;

/**
 * Trait PrivilegeCheckerCreateTrait
 * @package Sam\Application\Controller\Responsive\Feed\Internal\Privilege
 */
trait PrivilegeCheckerCreateTrait
{
    /**
     * @var PrivilegeChecker|null
     */
    protected ?PrivilegeChecker $privilegeChecker = null;

    /**
     * @return PrivilegeChecker
     */
    protected function createPrivilegeChecker(): PrivilegeChecker
    {
        return $this->privilegeChecker ?: PrivilegeChecker::new();
    }

    /**
     * @param PrivilegeChecker $privilegeChecker
     * @return static
     * @internal
     */
    public function setPrivilegeChecker(PrivilegeChecker $privilegeChecker): static
    {
        $this->privilegeChecker = $privilegeChecker;
        return $this;
    }
}
