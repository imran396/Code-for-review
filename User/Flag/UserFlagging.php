<?php
/**
 * Manage user flagging functionality
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Nov 30, 2011
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Flag;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Flag\UserFlagPureDetector;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAccount\UserAccountWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Account\Load\UserAccountLoaderAwareTrait;
use Sam\User\Account\Save\UserAccountProducerAwareTrait;
use Sam\User\Log\Save\UserLoggerAwareTrait;
use User;
use UserAccount;
use UserBilling;

/**
 * Class UserFlagging
 * @package Sam\User
 */
class UserFlagging extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use UserAccountLoaderAwareTrait;
    use UserAccountProducerAwareTrait;
    use UserAccountWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;
    use UserLoggerAwareTrait;

    private const MSG_FLAGGED_AS_SAME_CC_USER = '%s, because using the same CC as user %s';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $userId
     * @param int|null $accountId
     * @return int
     */
    public function detectFlag(?int $userId, ?int $accountId): int
    {
        $user = $this->getUserLoader()->load($userId);
        $userFlag = $this->detectFlagByUser($user, $accountId);
        return $userFlag;
    }

    /**
     * @param User|null $user
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function detectFlagByUser(?User $user = null, ?int $accountId = null, bool $isReadOnlyDb = false): int
    {
        if (!$user) {
            return Constants\User::FLAG_NONE;
        }

        if (in_array($user->Flag, [Constants\User::FLAG_BLOCK, Constants\User::FLAG_NOAUCTIONAPPROVAL], true)) {
            return $user->Flag;
        }

        $accountId = $accountId ?: $user->AccountId;
        $userAccount = $this->getUserAccountLoader()->load($user->Id, $accountId, $isReadOnlyDb);
        if (
            $userAccount
            && $userAccount->Flag
        ) {
            return (int)$userAccount->Flag;
        }

        return Constants\User::FLAG_NONE;
    }


    /**
     * Check if user can be approved at auction
     *
     * @param int $userId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAuctionApprovalByUserId(int $userId, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $user = $this->getUserLoader()->load($userId, $isReadOnlyDb);
        $userFlag = $this->detectFlagByUser($user, $accountId, $isReadOnlyDb);
        $is = UserFlagPureDetector::new()->isAuctionApprovalFlag($userFlag);
        return $is;
    }

    /**
     * Check if user can be approved at auction
     *
     * @param User $user
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAuctionApproval(User $user, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $userFlag = $this->detectFlagByUser($user, $accountId, $isReadOnlyDb);
        $is = UserFlagPureDetector::new()->isAuctionApprovalFlag($userFlag);
        return $is;
    }

    /**
     * @param User $user
     * @param UserBilling $userBilling
     * @param int $editorUserId
     * @return int
     */
    public function checkUserFlag(User $user, UserBilling $userBilling, int $editorUserId): int
    {
        $userFlag = $user->Flag ?: Constants\User::FLAG_NONE;
        // Process checking only if CC is not empty
        if (
            $userBilling->CcNumber !== ''
            && $userBilling->CcNumberHash !== ''
        ) {
            // Load all users with the same CC regardless of account Id
            $sameCcUsers = $this->getUserLoader()->loadByCcHash(
                $userBilling->CcNumberHash,
                null,
                null,
                $user->Id
            );
            // Set user flag to the highest flag of verifiable users with same CC
            foreach ($sameCcUsers as $sameCcUser) {
                if (
                    Constants\User::FLAG_SEVERITY[$sameCcUser->Flag] > Constants\User::FLAG_SEVERITY[$user->Flag]
                    && Constants\User::FLAG_SEVERITY[$sameCcUser->Flag] > Constants\User::FLAG_SEVERITY[$userFlag]
                ) {
                    $userFlag = $sameCcUser->Flag;
                    $flagName = UserPureRenderer::new()->makeFlag($userFlag);
                    $message = $this->getFlaggedAsSameCcUserMessage($flagName, $sameCcUser->Username);
                    $this->log($message, $user->Id, $editorUserId);
                }
            }
        }
        return $userFlag;
    }

    /**
     * @param UserAccount $userAccount
     * @return int
     */
    public function checkUserAccountFlag(UserAccount $userAccount): int
    {
        $flag = $userAccount->Flag ?: Constants\User::FLAG_NONE;
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($userAccount->UserId);
        if (
            $userBilling->CcNumber !== ''
            && $userBilling->CcNumberHash !== ''
        ) {
            // Load all users with the same CC and the same account Id
            $sameCcUsers = $this->getUserLoader()->loadByCcHash(
                $userBilling->CcNumberHash,
                $userAccount->AccountId,
                null,
                $userAccount->UserId
            );
            foreach ($sameCcUsers as $sameCcUser) {
                // Load user account for user with same CC
                $sameCcUserAccount = $this->getUserAccountLoader()
                    ->load($sameCcUser->Id, $sameCcUser->AccountId);
                if (!$sameCcUserAccount) {
                    continue;
                }
                // Set user account flag to the highest flag of verifiable user accounts related to users with same CC
                $sameCcUserAccountFlag = $sameCcUserAccount->Flag ?: Constants\User::FLAG_NONE;
                if (Constants\User::FLAG_SEVERITY[$sameCcUserAccountFlag] > Constants\User::FLAG_SEVERITY[$flag]) {
                    $flag = $sameCcUserAccount->Flag;
                }
            }
        }
        return $flag;
    }

    /**
     * Set user.flag value and log if flag changed
     *
     * @param User $user - Whom to set flag
     * @param int $accountId
     * @param int $userFlag - New flag value
     * @param int $editorUserId
     */
    public function setFlagAccountAndLog(User $user, int $accountId, int $userFlag, int $editorUserId): void
    {
        $this->logFlagAccountChangeByAdmin($user, $accountId, $userFlag);
        $userAccount = $this->getUserAccountLoader()->load($user->Id, $accountId, true);
        if (!$userAccount) {
            $userAccount = $this->getUserAccountProducer()->add($user->Id, $accountId, $editorUserId);
        }
        $userAccount->Flag = $userFlag;
        $this->getUserAccountWriteRepository()->saveWithModifier($userAccount, $editorUserId);
    }

    /**
     * Log message to all storage
     *
     * @param string $message
     * @param int $userId
     * @param int|null $editorUserId
     */
    public function log(string $message, int $userId, ?int $editorUserId): void
    {
        $userLogger = $this->getUserLogger();
        $userLogger->addToFile($message, $userId, $editorUserId);
        $userLogger->addToDb($message, $userId, $editorUserId);
    }

    /**
     * Log user.flag change
     * may be added second argument to set show 'Logged in admin' or other message
     *
     * @param User $user whom change flag
     * @param int|null $userFlag new flag value
     * @return bool change message is logged successful
     */
    public function logFlagChangeByAdmin(User $user, ?int $userFlag): bool
    {
        if (!$user->Id) {
            // Don't log flag change, when modified user is not yet created
            return false;
        }

        $userFlag = Cast::toInt($userFlag, Constants\User::FLAGS);
        if (
            $user->Flag !== $userFlag
            && $this->cfg()->get('core->general->debugLevel') >= Constants\Debug::INFO
        ) {
            $message = null;
            if ($userFlag === Constants\User::FLAG_BLOCK) {
                $message = 'Flagged Block';
            } elseif ($userFlag === Constants\User::FLAG_NOAUCTIONAPPROVAL) {
                $message = 'Flagged No Auction Approval';
            } elseif ($userFlag === Constants\User::FLAG_NONE) {
                if ($user->Flag === Constants\User::FLAG_BLOCK) {
                    $message = 'Un-Flagged Block';
                } elseif ($user->Flag === Constants\User::FLAG_NOAUCTIONAPPROVAL) {
                    $message = 'Un-Flagged No Auction Approval';
                }
            }
            $this->log($message, $user->Id, $this->getEditorUserId());
            return true;
        }
        return false;
    }

    /**
     * Log user.flag change
     * may be added second argument to set show 'Logged in admin' or other message
     *
     * @param User $user whom change flag
     * @param int $accountId
     * @param int $userFlag new flag value
     * @return bool change message is logged successful
     */
    private function logFlagAccountChangeByAdmin(User $user, int $accountId, int $userFlag): bool
    {
        $userAccount = $this->getUserAccountLoader()->load($user->Id, $accountId, true);
        $userAccountFlagId = $userAccount ? (int)$userAccount->Flag : null;
        if (
            $userAccountFlagId !== $userFlag
            && $this->cfg()->get('core->general->debugLevel') >= Constants\Debug::INFO
        ) {
            $message = null;
            if ($userFlag === Constants\User::FLAG_NOAUCTIONAPPROVAL) {
                $message = 'Flagged No Auction Approval';
            } elseif ($userFlag === Constants\User::FLAG_NONE) {
                if ($user->Flag === Constants\User::FLAG_NOAUCTIONAPPROVAL) {
                    $message = 'Un-Flagged No Auction Approval';
                } else {
                    $message = 'Un-Flagged No selection';
                }
            } else { // No Selection
                if ($user->Flag === Constants\User::FLAG_NOAUCTIONAPPROVAL) {
                    $message = 'Un-Flagged No Auction Approval';
                } elseif ($user->Flag === Constants\User::FLAG_NONE) {
                    $message = 'Un-Flagged None';
                }
            }
            $this->log($message, $user->Id, $this->getEditorUserId());
            return true;
        }
        return false;
    }

    /**
     * @param string $flagName
     * @param string $sameCcUserName
     * @return string
     */
    protected function getFlaggedAsSameCcUserMessage(string $flagName, string $sameCcUserName): string
    {
        $message = sprintf(self::MSG_FLAGGED_AS_SAME_CC_USER, $flagName, $sameCcUserName);
        return $message;
    }
}
