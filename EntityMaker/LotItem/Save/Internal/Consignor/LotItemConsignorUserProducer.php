<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\Consignor;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\Consignor\ConsignorWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class LotItemConsignorUserProducer
 * @package Sam\EntityMaker\LotItem\Save\Internal\Consignor
 */
class LotItemConsignorUserProducer extends CustomizableClass
{
    use ConsignorWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use UserLoaderAwareTrait;
    use UserWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadConsignorUserOrCreate(
        string $username,
        int $accountId,
        int $editorUserId,
        bool $shouldAutoCreateConsignor
    ): ?User {
        if (!$username) {
            return null;
        }
        $user = $this->getUserLoader()->loadByUsername($username, true);
        if (
            !$user
            && $shouldAutoCreateConsignor
        ) {
            $user = $this->createUser($username, $accountId, $editorUserId);
        }
        $this->assignConsignorPrivilege($user, $editorUserId);
        return $user;
    }

    /**
     * Create user
     * @param string $name User name
     * @param int $accountId
     * @param int $editorUserid
     * @return User|null
     */
    protected function createUser(string $name, int $accountId, int $editorUserid): ?User
    {
        $user = $this->createEntityFactory()->user();
        $user->AccountId = $accountId;
        $user->toActive();
        $user->Username = trim($name);
        $this->getUserWriteRepository()->saveWithModifier($user, $editorUserid);
        return $user;
    }

    /**
     * @param User|null $user
     * @param int $editorUserId
     */
    protected function assignConsignorPrivilege(?User $user, int $editorUserId): void
    {
        if (!$user) {
            return;
        }
        $consignor = $this->getUserLoader()->loadConsignorOrCreate($user->Id);
        if (!$consignor->Id) {
            $this->getConsignorWriteRepository()->saveWithModifier($consignor, $editorUserId);
        }
    }
}
