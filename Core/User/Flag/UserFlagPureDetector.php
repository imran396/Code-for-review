<?php
/**
 * SAM-10480: Extract pure user flag checking logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\User\Flag;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UserFlagPureDetector
 * @package Sam\Core\User\Flag
 */
class UserFlagPureDetector extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * Determine effective user flag on the base of his direct account's flag and some collateral account's flag.
     * @param int|null $directAccountUserFlag
     * @param int|null $collateralAccountUserFlag
     * @return int
     */
    public function detectEffective(
        ?int $directAccountUserFlag = null,
        ?int $collateralAccountUserFlag = null
    ): int {
        if ($directAccountUserFlag === null) {
            return Constants\User::FLAG_NONE;
        }

        if (in_array($directAccountUserFlag, [Constants\User::FLAG_BLOCK, Constants\User::FLAG_NOAUCTIONAPPROVAL], true)) {
            return $directAccountUserFlag;
        }

        if ($collateralAccountUserFlag) {
            return $collateralAccountUserFlag;
        }

        return Constants\User::FLAG_NONE;
    }

    /**
     * Check if flag allows to approve user at auction
     *
     * @param int $userFlag flag value
     * @return bool with that flag is/isn't possible to be approved at auction
     */
    public function isAuctionApprovalFlag(int $userFlag): bool
    {
        $is = !in_array($userFlag, [Constants\User::FLAG_NOAUCTIONAPPROVAL, Constants\User::FLAG_BLOCK], true);
        return $is;
    }
}
