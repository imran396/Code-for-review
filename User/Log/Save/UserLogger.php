<?php
/**
 * User logging manager
 * SAM-4702: User Log modules
 * SAM-1444: Walmart - Track user profile changes
 * SAM-823: User Flagging
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.02.2019 (Feb 20, 2013)
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Log\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\UserLog\UserLogWriteRepositoryAwareTrait;
use Sam\User\Log\Load\UserLogLoaderCreateTrait;

/**
 * Class UserLogger
 * @package Sam\User\Log\Save
 */
class UserLogger extends CustomizableClass
{
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use UserLogLoaderCreateTrait;
    use UserLogWriteRepositoryAwareTrait;

    /**
     * Possible Logging mode options for user profile fields logging
     * Defined by configuration constant: core->user->logProfile->mode
     */
    private const MODE_OFF = 0;     // do not log changes in profile fields
    private const MODE_USER = 1;    // log only user's own changes
    private const MODE_ALL = 2;     // log any changes (user's own, admin and system)

    /**
     *  We keep there logged users in the current execution session,
     *  so we could update one user_log entry, instead of adding new entries for every handled object (User, UserInfo, UserBilling, UserShipping)
     */
    protected static array $processingUserLogIds = [];     // user.id => user_log.id

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if we should log user change according to settings
     * @param int|null $changedUserId user, who is changed. null when creating new.
     * @param int|null $modifierUserId user, who perform change. null means we cannot find
     * @return bool
     */
    public function shouldLog(?int $changedUserId, ?int $modifierUserId): bool
    {
        $shouldLog = false;
        $mode = (int)cfg()->core->user->logProfile->mode;
        if ($mode !== self::MODE_OFF) {
            $shouldLog = $mode === self::MODE_ALL
                || ($mode === self::MODE_USER
                    && $modifierUserId === $changedUserId);
        }
        return $shouldLog;
    }

    /**
     * Add message to user_log table
     *
     * @param string $message
     * @param int $changedUserId
     * @param int|null $editorUserId
     * @return void
     */
    public function addToDb(string $message, int $changedUserId, ?int $editorUserId = null): void
    {
        $userLog = null;
        if (array_key_exists($changedUserId, self::$processingUserLogIds)) {
            $userLog = $this->createUserLogLoader()->load(self::$processingUserLogIds[$changedUserId]);
        }
        if (!$userLog) {
            $userLog = $this->createEntityFactory()->userLog();
        }
        $userLog->TimeLog = $this->getCurrentDateUtc();
        $userLog->Note .= ($userLog->Note ? PHP_EOL : '') . $message;
        $userLog->AdminId = $editorUserId;
        $userLog->UserId = $changedUserId;
        $this->getUserLogWriteRepository()->saveWithModifier($userLog, $editorUserId);
        self::$processingUserLogIds[$changedUserId] = $userLog->Id;
    }

    /**
     * Add message to logs/user_log.log
     *
     * @param string $message
     * @param int $changedUserId
     * @param int|null $editorUserId
     * @return void
     */
    public function addToFile(string $message, int $changedUserId, ?int $editorUserId = null): void
    {
        $adminId = $editorUserId ?: 'null';
        $postfix = composeSuffix(['changed u' => $changedUserId, 'editor u' => $adminId]);
        $message .= $postfix;
        log_always($message, 'user_log.log');
    }
}
