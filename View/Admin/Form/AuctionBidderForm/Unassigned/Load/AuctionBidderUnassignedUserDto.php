<?php
/**
 * SAM-7709: Apply DTOs for loaded data at Auction Bidders page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderForm\Unassigned\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderUnassignedUserDto
 * @package Sam\View\Admin\Form\AuctionBidderForm\Unassigned\Load
 */
class AuctionBidderUnassignedUserDto extends CustomizableClass
{
    public string $customerNo = '';
    public string $email = '';
    public string $firstName = '';
    public int $flag = 0;
    public int $userId = 0;
    public string $lastName = '';
    public string $username = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $customerNo
     * @param string $email
     * @param string $firstName
     * @param int $flag
     * @param int $id
     * @param string $lastName
     * @param string $username
     * @return static
     */
    public function construct(
        string $customerNo,
        string $email,
        string $firstName,
        int $flag,
        int $id,
        string $lastName,
        string $username
    ): static {
        $this->customerNo = $customerNo;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->flag = $flag;
        $this->userId = $id;
        $this->lastName = $lastName;
        $this->username = $username;
        return $this;
    }

    /**
     * Constructor based on data loaded from DB
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (string)$row['customer_no'],
            (string)$row['email'],
            (string)$row['first_name'],
            (int)$row['flag'],
            (int)$row['id'],
            (string)$row['last_name'],
            (string)$row['username']
        );
    }
}
