<?php
/**
 * SAM-10438: Wrap ZF MVC functions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Mvc\Legacy;

/**
 * Trait LegacyAdminMvcCreateTrait
 * @package Sam\Application\Mvc\Legacy
 */
trait AdminLegacyMvcCreateTrait
{
    protected ?AdminLegacyMvc $adminLegacyMvc = null;

    /**
     * @return AdminLegacyMvc
     */
    protected function createAdminLegacyMvc(): AdminLegacyMvc
    {
        return $this->adminLegacyMvc ?: AdminLegacyMvc::new();
    }

    /**
     * @param AdminLegacyMvc $adminLegacyMvc
     * @return $this
     * @internal
     */
    public function setAdminLegacyMvc(AdminLegacyMvc $adminLegacyMvc): static
    {
        $this->adminLegacyMvc = $adminLegacyMvc;
        return $this;
    }
}
