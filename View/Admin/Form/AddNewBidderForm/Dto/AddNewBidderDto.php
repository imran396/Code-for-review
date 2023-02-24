<?php
/**
 * SAM-5716: Extract auction bidder validation and building logic from "Add New Bidder" form
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AddNewBidderForm\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AddNewBidderDto
 * @package Sam\View\Admin\Form\AddNewBidderForm
 */
class AddNewBidderDto extends CustomizableClass
{
    public readonly int $auctionAccountId;
    public readonly int $auctionId;
    public readonly string $email;
    public readonly string $bidderNum;
    public readonly bool $preferredBidder;
    public readonly BidderAddressDto $shippingAddress;
    public readonly BidderAddressDto $billingAddress;
    public readonly string $ccNumber;
    public readonly string $ccExpDate;
    public readonly bool $reseller;
    public readonly string $resellerId;
    public readonly string $assignExistingUser;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $auctionAccountId,
        int $auctionId,
        string $email,
        string $bidderNum,
        bool $preferredBidder,
        BidderAddressDto $shippingAddress,
        BidderAddressDto $billingAddress,
        string $ccNumber,
        string $ccExpDate,
        bool $reseller,
        string $resellerId,
        string $assignExistingUser
    ): static {
        $this->auctionAccountId = $auctionAccountId;
        $this->auctionId = $auctionId;
        $this->email = trim($email);
        $this->bidderNum = trim($bidderNum);
        $this->preferredBidder = $preferredBidder;
        $this->shippingAddress = $shippingAddress;
        $this->billingAddress = $billingAddress;
        $this->ccNumber = trim($ccNumber);
        $this->ccExpDate = trim($ccExpDate);
        $this->reseller = $reseller;
        $this->resellerId = trim($resellerId);
        $this->assignExistingUser = trim($assignExistingUser);
        return $this;
    }

    /**
     * Check if user confirmed to assign existing user (and email wasn't changed)
     * @return bool
     */
    public function isAssigningExistingUser(): bool
    {
        return $this->assignExistingUser === $this->email;
    }
}
