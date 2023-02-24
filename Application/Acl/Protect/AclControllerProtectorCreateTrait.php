<?php
/**
 * SAM-9538: Decouple ACL checking logic from front controller
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Protect;

/**
 * Trait AclProtectorCreateTrait
 * @package Sam\Application\Acl
 */
trait AclControllerProtectorCreateTrait
{
    protected ?AclControllerProtector $aclControllerProtector = null;

    /**
     * @return AclControllerProtector
     */
    protected function createAclControllerProtector(): AclControllerProtector
    {
        return $this->aclControllerProtector ?: AclControllerProtector::new();
    }

    /**
     * @param AclControllerProtector $aclControllerProtector
     * @return $this
     * @internal
     */
    public function setAclControllerProtector(AclControllerProtector $aclControllerProtector): static
    {
        $this->aclControllerProtector = $aclControllerProtector;
        return $this;
    }
}
