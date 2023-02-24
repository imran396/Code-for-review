<?php
/**
 * SAM-4702: User Log modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Log\Save;

/**
 * Trait UserLoggerAwareTrait
 * @package Sam\User\Log\Save
 */
trait UserLoggerAwareTrait
{
    protected ?UserLogger $userLogger = null;

    /**
     * @return UserLogger
     */
    protected function getUserLogger(): UserLogger
    {
        if ($this->userLogger === null) {
            $this->userLogger = UserLogger::new();
        }
        return $this->userLogger;
    }

    /**
     * @param UserLogger $userLogger
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserLogger(UserLogger $userLogger): static
    {
        $this->userLogger = $userLogger;
        return $this;
    }
}
