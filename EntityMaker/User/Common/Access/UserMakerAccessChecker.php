<?php
/**
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Common\Access;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;

/**
 * Class UserMakerAccessChecker
 * @package Sam\EntityMaker\User\Common
 *
 * @method UserMakerInputDto getInputDto()
 * @method UserMakerConfigDto getConfigDto()
 */
class UserMakerAccessChecker extends CustomizableClass
{
    use ConfigDtoAwareTrait;
    use InputDtoAwareTrait;

    protected ?BidderPrivilegeChecker $targetBidderPrivilegeChecker = null;
    protected ?AdminPrivilegeChecker $targetAdminPrivilegeChecker = null;
    protected ?AdminPrivilegeChecker $editorAdminPrivilegeChecker = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param UserMakerInputDto $inputDto
     * @param UserMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        return $this;
    }

    // --- Target user privilege checking logic ---

    /**
     * Check, if target user will have Bidder role in result of current entity-making operation,
     * or if target user already has Bidder role and it won't be unset.
     * @return bool
     */
    public function willTargetUserHaveBidderRole(): bool
    {
        $inputDto = $this->getInputDto();
        return isset($inputDto->bidder)
            ? ValueResolver::new()->isTrue($inputDto->bidder)
            : $this->getTargetBidderPrivilegeChecker()->isBidder();
    }

    // - DI -

    public function setTargetAdminPrivilegeChecker(AdminPrivilegeChecker $checker): static
    {
        $this->targetAdminPrivilegeChecker = $checker;
        return $this;
    }

    protected function getTargetAdminPrivilegeChecker(): AdminPrivilegeChecker
    {
        if ($this->targetAdminPrivilegeChecker === null) {
            $this->targetAdminPrivilegeChecker = AdminPrivilegeChecker::new()
                ->initByUserId(Cast::toInt($this->getInputDto()->id));
        }
        return $this->targetAdminPrivilegeChecker;
    }

    public function setTargetBidderPrivilegeChecker(BidderPrivilegeChecker $checker): static
    {
        $this->targetBidderPrivilegeChecker = $checker;
        return $this;
    }

    protected function getTargetBidderPrivilegeChecker(): BidderPrivilegeChecker
    {
        if ($this->targetBidderPrivilegeChecker === null) {
            $this->targetBidderPrivilegeChecker = BidderPrivilegeChecker::new()
                ->initByUserId(Cast::toInt($this->getInputDto()->id));
        }
        return $this->targetBidderPrivilegeChecker;
    }

    // --- Editor user privilege checking logic ---

    public function hasEditorUserPrivilegeForCrossDomainAdmin(): bool
    {
        return $this->getEditorAdminPrivilegeChecker()->hasPrivilegeForSuperadmin();
    }

    public function hasEditorUserPrivilegeForManageCcInfo(): bool
    {
        return $this->getEditorAdminPrivilegeChecker()->hasPrivilegeForManageCcInfo();
    }

    public function hasEditorUserSubPrivilegeForUserPrivileges(): bool
    {
        return $this->getEditorAdminPrivilegeChecker()->hasSubPrivilegeForUserPrivileges();
    }

    // - DI -

    public function setEditorAdminPrivilegeChecker(AdminPrivilegeChecker $checker): static
    {
        $this->editorAdminPrivilegeChecker = $checker;
        return $this;
    }

    protected function getEditorAdminPrivilegeChecker(): AdminPrivilegeChecker
    {
        if ($this->editorAdminPrivilegeChecker === null) {
            $this->editorAdminPrivilegeChecker = AdminPrivilegeChecker::new()
                ->initByUserId($this->getConfigDto()->editorUserId);
        }
        return $this->editorAdminPrivilegeChecker;
    }

// Possible implementations for (sub)privilege checking functions
//    protected ?bool $willTargetHavePrivilegeForManageAuctions = null;
//    private ?bool $willTargetHavePrivilegeForManageUsers = null;
//
//    /**
//     * Check, if target user will have "Manage Auctions" privilege of Admin role in result of current operation,
//     * or if target user already has "Manage Auctions" privilege and it won't be unset.
//     * @return bool
//     */
//    public function willTargetHavePrivilegeForManageAuctions(): bool
//    {
//        if ($this->willTargetHavePrivilegeForManageAuctions === null) {
//            $inputDto = $this->getInputDto();
//            if (isset($inputDto->manageAuctions)) {
//                $isManageAuctions = ValueResolver::new()->isTrue($inputDto->manageAuctions);
//                $isAdmin = isset($inputDto->admin)
//                    ? ValueResolver::new()->isTrue($inputDto->admin)
//                    : $this->getTargetAdminPrivilegeChecker()->isAdmin();
//                $has = $isAdmin && $isManageAuctions;
//            } else {
//                $has = $this->getTargetAdminPrivilegeChecker()->hasPrivilegeForManageAuctions();
//            }
//            $this->willTargetHavePrivilegeForManageAuctions = $has;
//        }
//        return $this->willTargetHavePrivilegeForManageAuctions;
//    }

//    /**
//     * Check, if target user will have "Manage Users" privilege of Admin role in result of current operation,
//     * or if target user already has "Manage Users" privilege and it won't be unset.
//     * @return bool
//     */
//    public function willTargetHavePrivilegeForManageUsers(): bool
//    {
//        if ($this->willTargetHavePrivilegeForManageUsers === null) {
//
//            $inputDto = $this->getInputDto();
//            if (isset($inputDto->manageUsers)) {
//                $isManageUsers = ValueResolver::new()->isTrue($inputDto->manageAuctions);
//                $isAdmin = isset($inputDto->admin)
//                    ? ValueResolver::new()->isTrue($inputDto->admin)
//                    : $this->getTargetAdminPrivilegeChecker()->isAdmin();
//                $has = $isAdmin && $isManageUsers;
//            } else {
//                $has = $this->getTargetAdminPrivilegeChecker()->hasPrivilegeForManageUsers();
//            }
//            $this->willTargetHavePrivilegeForManageUsers = $has;
//        }
//        return $this->willTargetHavePrivilegeForManageAuctions;
//    }
}
