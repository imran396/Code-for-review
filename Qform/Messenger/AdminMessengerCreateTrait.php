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
 * Trait AdminMessengerAwareTrait
 * @package Sam\View
 */
trait AdminMessengerCreateTrait
{
    protected ?AdminMessenger $adminMessenger = null;

    /**
     * @return AdminMessenger
     */
    protected function createAdminMessenger(): AdminMessenger
    {
        return $this->adminMessenger ?: AdminMessenger::new();
    }

    /**
     * @param AdminMessenger $adminMessenger
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAdminMessenger(AdminMessenger $adminMessenger): static
    {
        $this->adminMessenger = $adminMessenger;
        return $this;
    }
}
