<?php
/**
 * SAM-6902: Decompose services from \Sam\Qform\QformHelpersAwareTrait to a separate independant <Servise>AwareTrait
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Messenger;

/**
 * Trait AdminMessengerRendererAwareTrait
 * @package Sam\Qform
 */
trait AdminMessengerRendererAwareTrait
{
    protected ?AdminMessengerRenderer $adminMessengerRenderer = null;

    /**
     * @return AdminMessengerRenderer
     */
    protected function getAdminMessengerRenderer(): AdminMessengerRenderer
    {
        if ($this->adminMessengerRenderer === null) {
            $this->adminMessengerRenderer = AdminMessengerRenderer::new();
        }
        return $this->adminMessengerRenderer;
    }

    /**
     * @param AdminMessengerRenderer $adminMessengerRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAdminMessengerRenderer(AdminMessengerRenderer $adminMessengerRenderer): static
    {
        $this->adminMessengerRenderer = $adminMessengerRenderer;
        return $this;
    }
}
