<?php
/**
 * Lot Item Consignor User Updater
 *
 * SAM-6248: Refactor Lot Info Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 8, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\Consignor\Save;

use Consignor;
use RuntimeException;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\Consignor\ConsignorWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use User;

/**
 * Class LotItemConsignorUserUpdater
 */
class LotItemConsignorUserUpdater extends CustomizableClass
{
    use AccountAwareTrait;
    use ConsignorWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use UserWriteRepositoryAwareTrait;

    // Incoming values

    protected string $username = '';

    // Outgoing values

    protected ?User $createdUser = null;
    protected ?Consignor $createdConsignor = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Create ConsignorUser
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $user = $this->createEntityFactory()->user();
        $user->AccountId = $this->getAccountId();
        $user->toActive();
        $user->Username = $this->username;
        $this->getUserWriteRepository()->saveWithModifier($user, $editorUserId);
        $this->createdUser = $user;

        $consignor = $this->createEntityFactory()->consignor();
        $consignor->UserId = $user->Id;
        $this->getConsignorWriteRepository()->saveWithModifier($consignor, $editorUserId);
        $this->createdConsignor = $consignor;
    }

    /**
     * Result with User entity that was created in update() command method
     * @return User
     */
    public function createdUser(): User
    {
        if (!$this->createdUser) {
            throw new RuntimeException('Created user unknown, probably command method was not called');
        }
        return $this->createdUser;
    }

    /**
     * Result with Consignor entity that was created in update() command method
     * @return Consignor
     */
    public function createdConsignor(): Consignor
    {
        if (!$this->createdConsignor) {
            throw new RuntimeException('Created consignor unknown, probably command method was not called');
        }
        return $this->createdConsignor;
    }
}
