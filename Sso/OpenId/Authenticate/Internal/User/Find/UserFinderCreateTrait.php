<?php
/**
 * SAM-10584: SAM SSO
 * SAM-10724: Login through SSO
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Jun 15, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Sso\OpenId\Authenticate\Internal\User\Find;

trait UserFinderCreateTrait
{
    protected ?UserFinder $userFinder = null;

    /**
     * @return UserFinder
     */
    protected function createUserFinder(): UserFinder
    {
        return $this->userFinder ?? UserFinder::new();
    }

    /**
     * @param UserFinder $userFinder
     * @return static
     * @internal
     */
    public function setUserFinder(UserFinder $userFinder): static
    {
        $this->userFinder = $userFinder;
        return $this;
    }
}
