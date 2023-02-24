<?php
/**
 * Apply customer number to a user
 *
 * SAM-6733: Duplicate customer number at signup.
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Save;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Settings\SettingsManager;
use Sam\Storage\Lock\DbLockerCreateTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\User\CustomerNo\Suggest\UserCustomerNoAdviser;
use Sam\User\Save\Exception\UserCustomerNoExceededException;
use User;

/**
 * Class UserCustomerNoApplier
 * @package Sam\User\Save
 */
class UserCustomerNoApplier extends CustomizableClass
{
    use DbLockerCreateTrait;
    use OptionalsTrait;
    use UserWriteRepositoryAwareTrait;

    public const OP_AUTO_INCREMENT_CUSTOMER_NUM = 'autoIncrementCustomerNum';
    public const OP_MAKE_PERMANENT_BIDDER_NUM = 'makePermanentBidderNum';
    public const OP_SUGGESTED_CUSTOMER_NO = 'suggestedCustomerNo';
    public const OP_MYSQL_MAX_INT = 'mysqlMaxInt';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Apply customer number, use GET_LOCK to avoid duplicates
     * @param User $user
     * @param int $editorUserId
     * @return User
     */
    public function apply(User $user, int $editorUserId): User
    {
        if (!$this->fetchOptional(self::OP_AUTO_INCREMENT_CUSTOMER_NUM)) {
            return $user;
        }

        $customerNo = (int)$this->fetchOptional(self::OP_SUGGESTED_CUSTOMER_NO);
        if ($customerNo >= (int)$this->fetchOptional(self::OP_MYSQL_MAX_INT)) {
            throw new UserCustomerNoExceededException();
        }

        $user->CustomerNo = $customerNo;
        $user->UsePermanentBidderno = (bool)$this->fetchOptional(self::OP_MAKE_PERMANENT_BIDDER_NUM);
        $this->getUserWriteRepository()->saveWithModifier($user, $editorUserId);

        return $user;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_AUTO_INCREMENT_CUSTOMER_NUM] = $optionals[self::OP_AUTO_INCREMENT_CUSTOMER_NUM]
            ?? static function (): bool {
                return (bool)SettingsManager::new()->getForMain(Constants\Setting::AUTO_INCREMENT_CUSTOMER_NUM);
            };

        $optionals[self::OP_MAKE_PERMANENT_BIDDER_NUM] = $optionals[self::OP_MAKE_PERMANENT_BIDDER_NUM]
            ?? static function (): bool {
                return (bool)SettingsManager::new()->getForMain(Constants\Setting::MAKE_PERMANENT_BIDDER_NUM);
            };

        $optionals[self::OP_SUGGESTED_CUSTOMER_NO] = $optionals[self::OP_SUGGESTED_CUSTOMER_NO]
            ?? static function (): int {
                return UserCustomerNoAdviser::new()->construct()->suggest();
            };

        $optionals[self::OP_MYSQL_MAX_INT] = $optionals[self::OP_MYSQL_MAX_INT]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->db->mysqlMaxInt');
            };

        $this->setOptionals($optionals);
    }
}
