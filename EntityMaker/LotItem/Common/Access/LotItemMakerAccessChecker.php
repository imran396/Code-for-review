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

namespace Sam\EntityMaker\LotItem\Common\Access;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class LotItemMakerAccessChecker
 * @package Sam\EntityMaker\LotItem
 * @method LotItemMakerInputDto getInputDto()
 * @method LotItemMakerConfigDto getConfigDto()
 */
class LotItemMakerAccessChecker extends CustomizableClass
{
    use ConfigDtoAwareTrait;
    use InputDtoAwareTrait;
    use LotItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /** @var AdminPrivilegeChecker|null */
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
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
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
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $editorUser = $this->getUserLoader()->load($configDto->editorUserId, true);
        if (!$editorUser) {
            log_error("Editor user not found in lot item entity-maker" . composeSuffix(['u' => $configDto->editorUserId]));
            return false;
        }

        $lotItem = $this->getLotItemLoader()->load((int)$inputDto->id);
        $lotItemAccountId = $lotItem->AccountId ?? $configDto->serviceAccountId;
        if ($configDto->mode->isSoap()) {
            return $editorUser->AccountId === $lotItemAccountId;
        }

        if ($this->hasEditorUserPrivilegeForCrossDomainAdmin()) {
            return true;
        }

        return $editorUser->AccountId === $lotItemAccountId;
    }


    public function hasEditorUserPrivilegeForCrossDomainAdmin(): bool
    {
        return $this->getEditorAdminPrivilegeChecker()->hasPrivilegeForSuperadmin();
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
