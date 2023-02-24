<?php
/**
 * Searches for user.id by user identified by email or username.
 *
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\DetectUser;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Read\CsvRow;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UserDetector
 * @package Sam\Import\Csv\Bidder\Internal\Load
 * @internal
 */
class UserDetector extends CustomizableClass
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
     * Detect user.id for user identified by email or username.
     *
     * @param CsvRow $csvRow
     * @param int $syncMode
     * @return int|null
     */
    public function detectUserId(CsvRow $csvRow, int $syncMode): ?int
    {
        if ($syncMode === Constants\Csv\Bidder::SYNC_BY_EMAIL) {
            $email = $csvRow->getCell(Constants\Csv\User::EMAIL);
            $row = $this->getUserLoader()->loadSelectedByEmail(['id'], $email);
        } else {
            $username = $csvRow->getCell(Constants\Csv\User::USERNAME);
            $row = $this->getUserLoader()->loadSelectedByUsername(['id'], $username);
        }

        return Cast::toInt($row['id'] ?? null);
    }
}
