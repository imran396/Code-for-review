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

namespace Sam\View\Admin\Form\AuctionBidderForm\Assigned\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderAssignedUserDto
 * @package Sam\View\Admin\Form\AuctionBidderForm\Assigned\Load
 */
class AuctionBidderAssignedUserDto extends CustomizableClass
{
    public int $auctionBidderId = 0;
    public string $bidderNum = '';
    public int $currentTotal = 0;
    public string $email = '';
    public string $firstName = '';
    public bool $isReseller = false;
    public bool $isResellerApproved = false;
    public string $lastName = '';
    public string $registeredOn = '';
    public string $resellerId = '';
    public string $resellerCertificate = '';
    public int $userAccountId = 0;
    public int $userId = 0;
    public string $username = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionBidderId
     * @param string $bidderNum
     * @param int $currentTotal
     * @param string $email
     * @param string $firstName
     * @param bool $isReseller
     * @param bool $isResellerApproved
     * @param string $lastName
     * @param string $registeredOn
     * @param string $resellerId
     * @param string $resellerCertificate
     * @param int $userAccountId
     * @param int $userId
     * @param string $username
     * @return $this
     */
    public function construct(
        int $auctionBidderId,
        string $bidderNum,
        int $currentTotal,
        string $email,
        string $firstName,
        bool $isReseller,
        bool $isResellerApproved,
        string $lastName,
        string $registeredOn,
        string $resellerId,
        string $resellerCertificate,
        int $userAccountId,
        int $userId,
        string $username
    ): static {
        $this->auctionBidderId = $auctionBidderId;
        $this->bidderNum = $bidderNum;
        $this->currentTotal = $currentTotal;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->isReseller = $isReseller;
        $this->isResellerApproved = $isResellerApproved;
        $this->lastName = $lastName;
        $this->registeredOn = $registeredOn;
        $this->resellerId = $resellerId;
        $this->resellerCertificate = $resellerCertificate;
        $this->userAccountId = $userAccountId;
        $this->userId = $userId;
        $this->username = $username;
        return $this;
    }

    /**
     * Constructor based on data loaded from DB
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['auction_bidder_id'],
            (string)$row['bidder_num'],
            (int)$row['current_total'],
            (string)$row['email'],
            (string)$row['first_name'],
            (bool)$row['is_reseller'],
            (bool)$row['is_reseller_approved'],
            (string)$row['last_name'],
            (string)$row['registered_on'],
            (string)$row['reseller_id'],
            (string)$row['reseller_certificate'],
            (int)$row['user_account_id'],
            (int)$row['user_id'],
            (string)$row['username']
        );
    }
}
