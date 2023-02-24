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

namespace Sam\Account\Image\Path;

/**
 * Trait AccountLogoPathHelperCreateTrait
 * @package Sam\Account\Image
 */
trait AccountLogoPathResolverCreateTrait
{
    protected ?AccountLogoPathResolver $accountLogoPathResolver = null;

    /**
     * @return AccountLogoPathResolver
     */
    protected function createAccountLogoPathResolver(): AccountLogoPathResolver
    {
        return $this->accountLogoPathResolver ?: AccountLogoPathResolver::new();
    }

    /**
     * @param AccountLogoPathResolver $pathResolver
     * @return static
     * @internal
     */
    public function setAccountLogoPathResolver(AccountLogoPathResolver $pathResolver): static
    {
        $this->accountLogoPathResolver = $pathResolver;
        return $this;
    }
}
