<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Log\Load;

/**
 * Trait UserLogLoaderCreateTrait
 * @package Sam\User\Log\Load
 */
trait UserLogLoaderCreateTrait
{
    protected ?UserLogLoader $userLogLoader = null;

    /**
     * @return UserLogLoader
     */
    protected function createUserLogLoader(): UserLogLoader
    {
        return $this->userLogLoader ?: UserLogLoader::new();
    }

    /**
     * @param UserLogLoader $userLogLoader
     * @return static
     * @internal
     */
    public function setUserLogLoader(UserLogLoader $userLogLoader): static
    {
        $this->userLogLoader = $userLogLoader;
        return $this;
    }
}
