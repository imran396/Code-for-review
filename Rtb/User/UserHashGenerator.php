<?php
/**
 * SAM-5067: Improve security of lightly sensitive bidding information in timed and live auctions public communication
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\User;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UserHashGenerator
 * @package Sam\Rtb\User
 */
class UserHashGenerator extends CustomizableClass
{
    use UserLoaderAwareTrait;

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
     * @param int|null $lotItemId
     * @param int $auctionId
     * @return string|null  NULL if the user or lot item is not exist
     */
    public function generate(?int $userId, ?int $lotItemId, int $auctionId): ?string
    {
        if (!$userId || !$lotItemId) {
            return null;
        }

        $message = $this->buildMessage($userId, $lotItemId, $auctionId);
        $hash = hash('SHA256', $message, true);
        $encodedHash = base64_encode($hash);
        $shortenedHash = substr($encodedHash, 0, Constants\Rtb::USER_HASH_LENGTH);
        return $shortenedHash;
    }

    /**
     * @param int $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @return string
     */
    protected function buildMessage(int $userId, int $lotItemId, int $auctionId): string
    {
        $user = $this->getUserLoader()->load($userId, true);
        $createdOn = $user && $user->CreatedOn
            ? (new DateTime($user->CreatedOn))->getTimestamp()
            : '';
        $messageComponents = [$userId, $createdOn, $auctionId, $lotItemId];
        $message = implode(Constants\Rtb::USER_HASH_COMPONENTS_DELIMITER, $messageComponents);
        return $message;
    }
}
