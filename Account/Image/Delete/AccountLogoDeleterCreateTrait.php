<?php
/**
 * SAM-7943: Refactor \Account_Image class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Image\Delete;

/**
 * Trait AccountLogoDeleterCreateTrait
 * @package Sam\Account\Image
 */
trait AccountLogoDeleterCreateTrait
{
    protected ?AccountLogoDeleter $accountLogoDeleter = null;

    /**
     * @return AccountLogoDeleter
     */
    protected function createAccountLogoDeleter(): AccountLogoDeleter
    {
        return $this->accountLogoDeleter ?: AccountLogoDeleter::new();
    }

    /**
     * @param AccountLogoDeleter $accountLogoDeleter
     * @return static
     * @internal
     */
    public function setAccountLogoDeleter(AccountLogoDeleter $accountLogoDeleter): static
    {
        $this->accountLogoDeleter = $accountLogoDeleter;
        return $this;
    }
}
