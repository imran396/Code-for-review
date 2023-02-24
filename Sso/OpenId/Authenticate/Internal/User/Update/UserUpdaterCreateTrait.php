<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\Authenticate\Internal\User\Update;

trait UserUpdaterCreateTrait
{
    protected ?UserUpdater $userUpdater = null;

    /**
     * @return UserUpdater
     */
    protected function createUserUpdater(): UserUpdater
    {
        return $this->userUpdater ?: UserUpdater::new();
    }

    /**
     * @param UserUpdater $userUpdater
     * @return $this
     * @internal
     */
    public function setUserUpdater(UserUpdater $userUpdater): static
    {
        $this->userUpdater = $userUpdater;
        return $this;
    }
}
