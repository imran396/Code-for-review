<?php
/**
 * This service detects expected direct account of target user.
 * This account should be used for assignment to user entity and defines context of filtering operations for checking entities that are related to user.
 *
 * SAM-9177: User entity-maker - Account related issues for v3-4, v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Common\DirectAccount;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\User\Load\UserLoader;
use Sam\EntityMaker\User\Common\DirectAccount\UserDirectAccountDetectionInput as Input;
use User;

/**
 * Class UserMakerAccountDetector
 * @package Sam\EntityMaker\User\Common\Account
 */
class UserDirectAccountDetector extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int
    public const OP_TARGET_USER = 'targetUser'; // ?User

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param Input $input
     * @return int
     */
    public function detect(Input $input): int
    {
        if ($input->inputAccountId) {
            return $input->inputAccountId;
        }

        if ($input->targetUserId) {
            /** @var User|null $targetUser */
            $targetUser = $this->fetchOptional(self::OP_TARGET_USER, [$input->targetUserId]);
            if ($targetUser && $targetUser->AccountId) {
                return $targetUser->AccountId;
            }
        }

        if ($input->isBidderOrConsignor) {
            return (int)$this->fetchOptional(self::OP_MAIN_ACCOUNT_ID);
        }

        return $input->systemAccountId;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->portal->mainAccountId');
            };
        $optionals[self::OP_TARGET_USER] = array_key_exists(self::OP_TARGET_USER, $optionals)
            ? $optionals[self::OP_TARGET_USER]
            : static function (int $targetUserId): ?User {
                return UserLoader::new()->load($targetUserId, true);
            };
        $this->setOptionals($optionals);
    }
}
