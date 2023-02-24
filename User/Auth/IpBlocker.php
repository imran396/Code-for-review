<?php
/**
 * Ip block manager
 * TODO: Place here more related functionality
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           02 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;

/**
 * Class IpBlocker
 * @package Sam\User\Auth
 */
class IpBlocker extends CustomizableClass
{
    use ServerRequestReaderAwareTrait;
    use UserAwareTrait;
    use UserLoginReadRepositoryCreateTrait;

    protected string $ip = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if IP is blocked
     * @return bool
     */
    public function isBlocked(): bool
    {
        $isBlocked = $this->createUserLoginReadRepository()
            ->enableReadOnlyDb(true)
            ->filterBlocked(true)
            ->filterIpAddress($this->getIp())
            ->exist();
        return $isBlocked;
    }

    /**
     * Perform actions for blocking user by ip
     */
    public function block(): void
    {
        Logger::new()->logBlockedByIp((int)$this->getUserId(), $this->getIp());
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return static
     */
    public function setIp(string $ip): static
    {
        $this->ip = $ip;
        return $this;
    }
}
