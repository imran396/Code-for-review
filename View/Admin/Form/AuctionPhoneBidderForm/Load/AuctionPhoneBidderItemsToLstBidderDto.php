<?php
/**
 * SAM-8832: Apply DTOs for Auction Phone Bidder page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionPhoneBidderItemsToLstBidderDto
 * @package Sam\View\Admin\Form\AuctionPhoneBidderForm\Load
 */
class AuctionPhoneBidderItemsToLstBidderDto extends CustomizableClass
{
    public readonly string $bidderNum;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly int $userId;
    public readonly string $username;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $bidderNum
     * @param string $firstName
     * @param string $lastName
     * @param int $userId
     * @param string $username
     * @return $this
     */
    public function construct(
        string $bidderNum,
        string $firstName,
        string $lastName,
        int $userId,
        string $username
    ): static {
        $this->bidderNum = $bidderNum;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->userId = $userId;
        $this->username = $username;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (string)$row['bidder_num'],
            (string)$row['first_name'],
            (string)$row['last_name'],
            (int)$row['user_id'],
            (string)$row['username']
        );
    }
}
