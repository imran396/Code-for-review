<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Autocomplete\Internal\Load;

/**
 * Trait UserAddedByDataLoaderCreateTrait
 * @package
 */
trait UserAddedByDataLoaderCreateTrait
{
    protected ?UserAddedByDataLoader $userAddedByDataLoader = null;

    /**
     * @return UserAddedByDataLoader
     */
    protected function createUserAddedByDataLoader(): UserAddedByDataLoader
    {
        return $this->userAddedByDataLoader ?: UserAddedByDataLoader::new();
    }

    /**
     * @param UserAddedByDataLoader $userAddedByDataLoader
     * @return $this
     * @internal
     */
    public function setUserAddedByDataLoader(UserAddedByDataLoader $userAddedByDataLoader): static
    {
        $this->userAddedByDataLoader = $userAddedByDataLoader;
        return $this;
    }
}
