<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Auth;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class RtbAuthChecker
 * @package
 */
class RtbAuthChecker extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;

    public const PARAM_USER_ID = 'user_id';
    public const PARAM_USER_TYPE = 'user_type';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param int|null $checkUserId
     * @param int|null $userType
     * @param string $sessionId
     * @return bool
     */
    public function check(?int $editorUserId, ?int $checkUserId, ?int $userType, string $sessionId): bool
    {
        $success = false;
        $logData = ['editor u' => $editorUserId, 'check u' => $checkUserId, 'ut' => $userType, session_name() => $sessionId];
        if (
            $checkUserId
            && $userType
            && $sessionId
            && $editorUserId
        ) {
            if ($editorUserId === $checkUserId) {
                if ($userType === Constants\Rtb::UT_CLERK) {
                    $hasPrivilegeForManageAuctions = $this->getAdminPrivilegeChecker()
                        ->initByUserId($editorUserId)
                        ->hasPrivilegeForManageAuctions();
                    if ($hasPrivilegeForManageAuctions) {
                        $success = true;
                    }
                    $logData['hasPrivilegeForManageAuctions'] = $hasPrivilegeForManageAuctions;
                } elseif (in_array($userType, [Constants\Rtb::UT_BIDDER, Constants\Rtb::UT_VIEWER], true)) {
                    $success = true;
                }
            }
        }
        if (!$success) {
            log_error('Cannot authenticate user for rtb console connection' . composeSuffix($logData));
        }
        return $success;
    }
}
