<?php
/**
 * Validation input DTO
 *
 * SAM-8833: Lot item entity maker - extract item# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate;

use InvalidServiceAccount;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;

/**
 * Class ItemNoValidationDto
 * @package Sam\EntityMaker\LotItem
 */
class ItemNoValidationInput extends CustomizableClass
{
    public ?int $lotItemId;
    public Mode $mode;
    public bool $isSetItemFullNum;
    public bool $isSetItemNum;
    public int $accountId;
    public int $editorUserId;
    public string $itemFullNum;
    public string $itemNum;
    public string $itemNumExt;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $itemNum
     * @param string $itemNumExt
     * @param string $itemFullNum
     * @param int|null $lotItemId
     * @param int $accountId
     * @param bool $isSetItemNum
     * @param bool $isSetItemFullNum
     * @param Mode $mode
     * @param int $editorUserId
     * @return $this
     */
    public function construct(
        string $itemNum,
        string $itemNumExt,
        string $itemFullNum,
        ?int $lotItemId,
        int $accountId,
        bool $isSetItemNum,
        bool $isSetItemFullNum,
        Mode $mode,
        int $editorUserId
    ): static {
        $this->itemNum = $itemNum;
        $this->itemNumExt = $itemNumExt;
        $this->itemFullNum = $itemFullNum;
        $this->lotItemId = $lotItemId;
        $this->accountId = $accountId;
        $this->isSetItemNum = $isSetItemNum;
        $this->isSetItemFullNum = $isSetItemFullNum;
        $this->mode = $mode;
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return $this
     */
    public function fromMakerDto(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
    ): static {
        if (!$configDto->serviceAccountId) {
            throw InvalidServiceAccount::withDefaultMessage();
        }

        return $this->construct(
            (string)$inputDto->itemNum,
            (string)$inputDto->itemNumExt,
            (string)$inputDto->itemFullNum,
            Cast::toInt($inputDto->id),
            $configDto->serviceAccountId,
            isset($inputDto->itemNum),
            isset($inputDto->itemFullNum),
            $configDto->mode,
            $configDto->editorUserId
        );
    }

    public function logData(): array
    {
        return [
            'itemNum' => $this->itemNum,
            'itemNumExt' => $this->itemNumExt,
            'itemFullNum' => $this->itemFullNum,
            'lotItemId' => $this->lotItemId,
            'accountId' => $this->accountId,
            'isSetItemNum' => $this->isSetItemNum,
            'isSetItemFullNum' => $this->isSetItemFullNum,
            'mode' => $this->mode->name,
            'editorUserId' => $this->editorUserId
        ];
    }
}
