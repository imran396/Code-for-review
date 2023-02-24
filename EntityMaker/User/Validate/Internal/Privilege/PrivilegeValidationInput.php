<?php
/**
 * SAM-9413: Make possible for portal admin to create bidder and consignor users linked with main account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;

/**
 * Contains user privileges inputs that should be validated along with necessary config data
 *
 * Class PrivilegeValidationInput
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege
 */
class PrivilegeValidationInput extends CustomizableClass
{
    public ?int $editorUserId = null;
    public int $systemAccountId;
    public ?int $id = null;

    /**
     * Admin role
     */
    public ?string $admin = null;
    /**
     * -- Privileges for admin role --
     */
    public ?string $manageAuctions = null;
    public ?string $manageInventory = null;
    public ?string $manageUsers = null;
    public ?string $manageInvoices = null;
    public ?string $manageSettlements = null;
    public ?string $manageSettings = null;
    public ?string $manageCcInfo = null;
    public ?string $salesStaff = null;
    public ?string $manageReports = null;
    public ?string $crossDomain = null;
    /**
     * -- Sub-privileges for manageAuctions --
     */
    public ?string $manageAllAuctions = null;
    public ?string $deleteAuction = null;
    public ?string $archiveAuction = null;
    public ?string $resetAuction = null;
    public ?string $information = null;
    public ?string $publish = null;
    public ?string $lots = null;
    public ?string $availableLots = null;
    public ?string $bidders = null;
    public ?string $remainingUsers = null;
    public ?string $runLiveAuction = null;
    public ?string $auctioneerScreen = null;
    public ?string $projector = null;
    public ?string $bidIncrements = null;
    public ?string $buyersPremium = null;
    public ?string $permissions = null;
    public ?string $createBidder = null;
    /**
     * -- Sub-privileges for manageUsers --
     */
    public ?string $userPasswords = null;
    public ?string $bulkUserExport = null;
    public ?string $userPrivileges = null;
    public ?string $deleteUser = null;

    /**
     * Bidder role
     */
    public ?string $bidder = null;
    /**
     * -- Privileges for bidder role --
     */
    public ?string $bidderPreferred = null;
    public ?string $bidderAgent = null;
    public ?string $bidderHouse = null;

    /**
     * Consignor role
     */
    public ?string $consignor = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $editorUserId,
        int $systemAccountId,
        ?int $id,
        // Admin role
        ?string $admin,
        // Privileges for admin role
        ?string $manageAuctions,
        ?string $manageInventory,
        ?string $manageUsers,
        ?string $manageInvoices,
        ?string $manageSettlements, // ?string $manageConsignorSettlements,
        ?string $manageSettings,
        ?string $manageCcInfo,
        ?string $salesStaff,
        ?string $manageReports, // ?string $reports,
        ?string $crossDomain, // ?string $superadmin,
        // Sub-privileges for manageAuctions
        ?string $manageAllAuctions,
        ?string $deleteAuction,
        ?string $archiveAuction,
        ?string $resetAuction,
        ?string $information,
        ?string $publish,
        ?string $lots,
        ?string $availableLots,
        ?string $bidders,
        ?string $remainingUsers,
        ?string $runLiveAuction,
        ?string $auctioneerScreen,
        ?string $projector,
        ?string $bidIncrements,
        ?string $buyersPremium,
        ?string $permissions,
        ?string $createBidder,
        // Sub-privileges for manageUsers
        ?string $userPasswords,
        ?string $bulkUserExport,
        ?string $userPrivileges,
        ?string $deleteUser,
        // Bidder role
        ?string $bidder,
        // Privileges for bidder role
        ?string $bidderPreferred,
        ?string $bidderAgent,
        ?string $bidderHouse,
        // Consignor role
        ?string $consignor
    ): static {
        $this->editorUserId = $editorUserId;
        $this->systemAccountId = $systemAccountId;
        $this->id = $id;
        // Admin role
        $this->admin = $admin;
        // Privileges for admin role
        $this->manageAuctions = $manageAuctions;
        $this->manageInventory = $manageInventory;
        $this->manageUsers = $manageUsers;
        $this->manageInvoices = $manageInvoices;
        $this->manageSettlements = $manageSettlements;
        $this->manageSettings = $manageSettings;
        $this->manageCcInfo = $manageCcInfo;
        $this->salesStaff = $salesStaff;
        $this->manageReports = $manageReports;
        $this->crossDomain = $crossDomain;
        // Sub-privileges for manageAuctions
        $this->manageAllAuctions = $manageAllAuctions;
        $this->deleteAuction = $deleteAuction;
        $this->archiveAuction = $archiveAuction;
        $this->resetAuction = $resetAuction;
        $this->information = $information;
        $this->publish = $publish;
        $this->lots = $lots;
        $this->availableLots = $availableLots;
        $this->bidders = $bidders;
        $this->remainingUsers = $remainingUsers;
        $this->runLiveAuction = $runLiveAuction;
        $this->auctioneerScreen = $auctioneerScreen;
        $this->projector = $projector;
        $this->bidIncrements = $bidIncrements;
        $this->buyersPremium = $buyersPremium;
        $this->permissions = $permissions;
        $this->createBidder = $createBidder;
        // Sub-privileges for manageUsers
        $this->userPasswords = $userPasswords;
        $this->bulkUserExport = $bulkUserExport;
        $this->userPrivileges = $userPrivileges;
        $this->deleteUser = $deleteUser;
        // Bidder role
        $this->bidder = $bidder;
        // Privileges for bidder role
        $this->bidderAgent = $bidderAgent;
        $this->bidderHouse = $bidderHouse;
        $this->bidderPreferred = $bidderPreferred;
        // Consignor role
        $this->consignor = $consignor;
        return $this;
    }

    public function fromMakerDto(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto
    ): static {
        return $this->construct(
            $configDto->editorUserId,
            $configDto->systemAccountId,
            Cast::toInt($inputDto->id),
            // Admin role
            $inputDto->admin,
            // Privileges for admin role
            $inputDto->manageAuctions,
            $inputDto->manageInventory,
            $inputDto->manageUsers,
            $inputDto->manageInvoices,
            $inputDto->manageConsignorSettlements,
            $inputDto->manageSettings,
            $inputDto->manageCcInfo,
            $inputDto->salesStaff,
            $inputDto->reports,
            $inputDto->superadmin,
            // Sub-privileges for manageAuctions
            $inputDto->manageAllAuctions,
            $inputDto->deleteAuction,
            $inputDto->archiveAuction,
            $inputDto->resetAuction,
            $inputDto->information,
            $inputDto->publish,
            $inputDto->lots,
            $inputDto->availableLots,
            $inputDto->bidders,
            $inputDto->remainingUsers,
            $inputDto->runLiveAuction,
            $inputDto->auctioneerScreen,
            $inputDto->projector,
            $inputDto->bidIncrements,
            $inputDto->buyersPremium,
            $inputDto->permissions,
            $inputDto->createBidder,
            // Sub-privileges for manageUsers
            $inputDto->userPasswords,
            $inputDto->bulkUserExport,
            $inputDto->userPrivileges,
            $inputDto->deleteUser,
            // Bidder role
            $inputDto->bidder,
            // Privileges for bidder role
            $inputDto->bidderPreferred,
            $inputDto->bidderAgent,
            $inputDto->bidderHouse,
            // Consignor role
            $inputDto->consignor
        );
    }

    public function isBooleanValueSet(string $field): bool
    {
        return (string)$this->{$field} !== '';
    }
}
