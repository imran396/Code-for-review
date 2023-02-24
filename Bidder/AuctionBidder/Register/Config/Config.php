<?php
/**
 * Mutual context between registration classes
 *
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Sep 14, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\AuctionBidder\Register\Config;

use Sam\Bidder\AuctionBidder\Register\Config\Opayo\OpayoThreeDSecureAuctionBidderRegistrationConfig;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Context
 * @package Sam\Bidder\AuctionBidder\Register\Config
 */
class Config extends CustomizableClass
{
    // config options

    /**
     * It presents on checking with Bidder->Preferred and Auction->ManualBidderApprovalOnly.
     * Also, this option allows unconditionally disable approving of user in auction during this auction bidder registration service.
     * @var bool
     */
    protected bool $isAutoApprove = false;

    /**
     * @var string
     */
    protected string $carrierMethod = '';

    /**
     * @var bool
     */
    protected bool $isPostAuctionImport = false;

    /**
     * @var float|null
     */
    protected ?float $postAuctionImportPremium = null;

    /**
     * CC processing for validation
     * @var bool
     */
    protected bool $isAuthOrCapture = false;

    /**
     * We want to register agent's buyers in auction together with agent as default behavior
     * @var bool
     */
    protected bool $shouldRegisterBuyers = false;

    /**
     * User should be registered in all auctions of the Sale Group, where target auction is assigned.
     * @var bool
     */
    protected bool $shouldRegisterInSaleGroup = false;

    /**
     * We approve registered user independently of most conditions and system settings.
     * E.g. Post Auction Import Premium should be assigned to every AuctionBidder record during registration via this feature, including previously assigned and approved bidders in auction.
     * isFlaggedUserApprovable = false, has higher priority. When disabled, then don't allow to approve flagged users (BLK, NAA).
     * @var bool
     */
    protected bool $isAbsoluteApprove = false;

    /**
     * Register user in auction and don't continue approval actions.
     * @var bool
     */
    protected bool $isRegisterOnly = false;

    /**
     * TODO: IK, 2022/05: This option might be added in further, or not.
     * Disapprove means drop Bidder# of AuctionBidder.
     * If registrator cannot approve, it doesn't assign bidder#, so there is no big sense in removing bidder# in case of approval skip.
     * However, we may want call registrator on approved bidder. Idk why, but if we do, then we may want to drop bidder# in case of fail with approving.
     * @var bool
     * protected bool $isDisapproveWhenCannotApprove = false;
     */

    /**
     * Restrict from registration the general user, who do not have Bidder Role
     * @var bool
     */
    protected bool $isRestrictionByBidderRole = false;

    /**
     * Restrict from registration the user and his buyers, who are flagged by NAA, BLK
     * @var bool
     */
    protected bool $isRestrictionByUserFlag = false;

    /**
     * Allow to approve flagged users (BLK, NAA)
     * @var bool
     */
    protected bool $isFlaggedUserApprovable = false;

    /**
     * Required bidder# for force assignment to general user.
     * @var string
     */
    protected string $bidderNumber = '';

    /**
     * Send registration and approval emails
     * @var bool
     */
    protected bool $isEmailNotification = false;

    /**
     * Requires to run specific to Opayo billing registration of user in auction and approval procedure.
     * @var OpayoThreeDSecureAuctionBidderRegistrationConfig|null
     */
    protected ?OpayoThreeDSecureAuctionBidderRegistrationConfig $opayoThreeDSecureAuctionBidderRegistrationConfig = null;

    /**
     * Required cvv if we charge through Opayo and respective AVS2 rules applied
     * @var string
     */
    protected string $cvv = '';

    protected int $auctionId;

    protected int $userId;

    /**
     * @var int|null
     */
    protected ?int $editorUserId = null;
    // end config options

    /**
     * TODO: extract from here
     * @var string
     */
    protected string $errorMessage = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setAuctionId(int $auctionId): static
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    public function getAuctionId(): int
    {
        return $this->auctionId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    // config options

    /**
     * @param bool $enable
     * @return static
     */
    public function enableAbsoluteApprove(bool $enable): static
    {
        $this->isAbsoluteApprove = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAbsoluteApprove(): bool
    {
        return $this->isAbsoluteApprove;
    }

    /**
     * Only perform user registration in auction.
     * @param bool $enable
     * @return static
     */
    public function enableRegisterOnly(bool $enable): static
    {
        $this->isRegisterOnly = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRegisterOnly(): bool
    {
        return $this->isRegisterOnly;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableAutoApprove(bool $enable): static
    {
        $this->isAutoApprove = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoApprove(): bool
    {
        return $this->isAutoApprove;
    }

    /**
     * @param string $carrierMethod
     * @return static
     */
    public function setCarrierMethod(string $carrierMethod): static
    {
        $this->carrierMethod = trim($carrierMethod);
        return $this;
    }

    /**
     * @return string
     */
    public function getCarrierMethod(): string
    {
        return $this->carrierMethod;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enablePostAuctionImport(bool $enable): static
    {
        $this->isPostAuctionImport = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPostAuctionImport(): bool
    {
        return $this->isPostAuctionImport;
    }

    /**
     * @param float $premium
     * @return static
     */
    public function setPostAuctionImportPremium(float $premium): static
    {
        $this->postAuctionImportPremium = $premium;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPostAuctionImportPremium(): ?float
    {
        return $this->postAuctionImportPremium;
    }

    /**
     * Enable/disable CC processing. Is enabled by default.
     * @param bool $enable
     * @return static
     */
    public function enableAuthOrCapture(bool $enable): static
    {
        $this->isAuthOrCapture = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuthOrCapture(): bool
    {
        return $this->isAuthOrCapture;
    }

    public function setCvv(string $cvv): static
    {
        $this->cvv = $cvv;
        return $this;
    }

    public function getCvv(): string
    {
        return $this->cvv;
    }

    /**
     * Requires to run specific to Opayo billing registration of user in auction and approval procedure.
     * Pass specific config object to enable opayo processing.
     * @param OpayoThreeDSecureAuctionBidderRegistrationConfig|null $opayoAuctionRegistrationConfig null - pass null to disable.
     * @return $this
     */
    public function enableOpayoThreeDSecureApprove(?OpayoThreeDSecureAuctionBidderRegistrationConfig $opayoAuctionRegistrationConfig = null): static
    {
        $this->opayoThreeDSecureAuctionBidderRegistrationConfig = $opayoAuctionRegistrationConfig;
        return $this;
    }

    /**
     * Check if we require to run specific to Opayo billing registration.
     * @return bool
     */
    public function isOpayoThreeDSecureApproveEnabled(): bool
    {
        return $this->opayoThreeDSecureAuctionBidderRegistrationConfig !== null;
    }

    /**
     * Result with object with options required for registration for specific Opayo billing registration.
     * @return OpayoThreeDSecureAuctionBidderRegistrationConfig
     */
    public function getOpayoThreeDSecureApproveConfig(): OpayoThreeDSecureAuctionBidderRegistrationConfig
    {
        return $this->opayoThreeDSecureAuctionBidderRegistrationConfig;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableRegisterBuyers(bool $enable): static
    {
        $this->shouldRegisterBuyers = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldRegisterBuyers(): bool
    {
        return $this->shouldRegisterBuyers;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableRegisterInSaleGroup(bool $enable): static
    {
        $this->shouldRegisterInSaleGroup = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldRegisterInSaleGroup(): bool
    {
        return $this->shouldRegisterInSaleGroup;
    }

    /**
     * When enabled, we should not register user without Bidder Role.
     * @param bool $enable
     * @return static
     */
    public function enableRestrictionByBidderRole(bool $enable): static
    {
        $this->isRestrictionByBidderRole = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRestrictionByBidderRole(): bool
    {
        return $this->isRestrictionByBidderRole;
    }

    /**
     * Restrict from registration users, who are flagged by NAA, BLK
     * @param bool $enable
     * @return static
     */
    public function enableRestrictionByUserFlag(bool $enable): static
    {
        $this->isRestrictionByUserFlag = $enable;
        return $this;
    }

    /**
     * When enabled, we should not register BLK, NAA flagged user, i.e. create AuctionBidder record.
     * @return bool
     */
    public function isRestrictionByUserFlag(): bool
    {
        return $this->isRestrictionByUserFlag;
    }

    /**
     * Restrict from approving users (assign bidder#), who are flagged by NAA, BLK
     * @param bool $enable
     * @return static
     */
    public function enableFlaggedUserApprovable(bool $enable): static
    {
        $this->isFlaggedUserApprovable = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFlaggedUserApprovable(): bool
    {
        return $this->isFlaggedUserApprovable;
    }

    /**
     * Force assign bidder# to general user.
     * @param string $bidderNumber
     * @return $this
     */
    public function assignBidderNumber(string $bidderNumber): static
    {
        $this->bidderNumber = $bidderNumber;
        return $this;
    }

    /**
     * Return bidder# for general user.
     * @return string
     */
    public function getBidderNumber(): string
    {
        return $this->bidderNumber;
    }

    public function enableEmailNotification(bool $enable): static
    {
        $this->isEmailNotification = $enable;
        return $this;
    }

    public function isEmailNotification(): bool
    {
        return $this->isEmailNotification;
    }

    public function setEditorUserId(int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    public function getEditorUserId(): ?int
    {
        return $this->editorUserId;
    }

    // end config options

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $message
     */
    public function setErrorMessage(string $message): void
    {
        $this->errorMessage = trim($message);
    }
}
