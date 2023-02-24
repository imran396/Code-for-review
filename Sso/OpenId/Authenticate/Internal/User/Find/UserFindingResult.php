<?php
/**
 * Result-object describes user search outcome.
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

namespace Sam\Sso\OpenId\Authenticate\Internal\User\Find;

use Sam\Core\Service\CustomizableClass;

class UserFindingResult extends CustomizableClass
{
    public ?int $userId;
    public bool $isFoundByUuid = false;
    public bool $isFoundByEmail = false;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $userId = null,
        bool $isFoundByUuid = false,
        bool $isFoundByEmail = false
    ): static {
        $this->userId = $userId;
        $this->isFoundByUuid = $isFoundByUuid;
        $this->isFoundByEmail = $isFoundByEmail;
        return $this;
    }

    public function notFound(): static
    {
        return $this->construct();
    }

    public function foundByEmail(int $userId): static
    {
        return $this->construct($userId, false, true);
    }

    public function foundByUuid(int $userId): static
    {
        return $this->construct($userId, true);
    }
}
