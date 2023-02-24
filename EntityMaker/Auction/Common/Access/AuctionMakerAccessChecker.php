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

namespace Sam\EntityMaker\Auction\Common\Access;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class AuctionAccessChecker
 * @package Sam\EntityMaker\Auction\Common\Access
 * @method AuctionMakerInputDto getInputDto()
 * @method AuctionMakerConfigDto getConfigDto()
 */
class AuctionMakerAccessChecker extends CustomizableClass
{
    use ConfigDtoAwareTrait;
    use InputDtoAwareTrait;
    use AuctionLoaderAwareTrait;
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
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        return $this;
    }

    /**
     * Check access for auction item editing.
     * @return bool
     */
    public function canEdit(): bool
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $editorUser = $this->getUserLoader()->load($configDto->editorUserId, true);
        if (!$editorUser) {
            log_error("Editor user not found in auction item entity-maker" . composeSuffix(['u' => $configDto->editorUserId]));
            return false;
        }

        $auction = $this->getAuctionLoader()->load($inputDto->id);
        $auctionAccountId = $auction->AccountId ?? $configDto->serviceAccountId;
        if ($configDto->mode->isSoap()) {
            return $editorUser->AccountId === $auctionAccountId;
        }

        if ($this->hasEditorUserPrivilegeForCrossDomainAdmin()) {
            return true;
        }

        return $editorUser->AccountId === $auctionAccountId;
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
