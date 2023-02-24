<?php
/**
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/20/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Role;

/**
 * Trait AclRoleDetectorCreateTrait
 * @package Sam\Application\Acl\Rol
 */
trait AclRoleDetectorCreateTrait
{
    protected ?AclRoleDetector $aclRoleDetector = null;

    /**
     * @return AclRoleDetector
     */
    protected function createAclRoleDetector(): AclRoleDetector
    {
        return $this->aclRoleDetector ?: AclRoleDetector::new();
    }

    /**
     * @param AclRoleDetector $aclRoleDetector
     * @return $this
     * @internal
     */
    public function setAclRoleDetector(AclRoleDetector $aclRoleDetector): static
    {
        $this->aclRoleDetector = $aclRoleDetector;
        return $this;
    }
}
