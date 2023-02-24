<?php
/**
 * SAM-8940: Fix access check of lot editing in soap
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

namespace Sam\EntityMaker\AuctionLot\Common\Access;

use AuctionLotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class AuctionLotMakerAccessChecker
 * @package Sam\EntityMaker\AuctionLot\Common\Access
 * @method AuctionLotMakerInputDto getInputDto()
 * @method AuctionLotMakerConfigDto getConfigDto()
 */
class AuctionLotMakerAccessChecker extends CustomizableClass
{
    use ConfigDtoAwareTrait;
    use InputDtoAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use UserLoaderAwareTrait;

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
     * @param AuctionLotMakerInputDto $inputDto
     * @param AuctionLotMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        AuctionLotMakerInputDto $inputDto,
        AuctionLotMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        return $this;
    }

    /**
     * Check access for lot item editing.
     * @return bool
     */
    public function canEdit(): bool
    {
        $configDto = $this->getConfigDto();
        $editorUser = $this->getUserLoader()->load($configDto->editorUserId, true);
        if (!$editorUser) {
            log_error("Editor user not found in lot item entity-maker" . composeSuffix(['u' => $configDto->editorUserId]));
            return false;
        }

        $auctionLot = $this->loadAuctionLot();
        $auctionLotAccountId = $auctionLot->AccountId ?? $configDto->serviceAccountId;
        if ($configDto->mode->isSoap()) {
            return $editorUser->AccountId === $auctionLotAccountId;
        }

        if ($this->hasEditorUserPrivilegeForCrossDomainAdmin()) {
            return true;
        }

        return $editorUser->AccountId === $auctionLotAccountId;
    }


    public function hasEditorUserPrivilegeForCrossDomainAdmin(): bool
    {
        return $this->getEditorAdminPrivilegeChecker()->hasPrivilegeForSuperadmin();
    }

    protected function loadAuctionLot(): ?AuctionLotItem
    {
        return $this->getAuctionLotLoader()->loadById(Cast::toInt($this->getInputDto()->id));
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
}
