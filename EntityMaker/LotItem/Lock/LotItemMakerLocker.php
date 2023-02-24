<?php
/**
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\LotItem\Lock\Consignor\LotItemUniqueConsignorLockerCreateTrait;
use Sam\EntityMaker\LotItem\Lock\CustomField\LotItemUniqueCustomFieldLockerCreateTrait;
use Sam\EntityMaker\LotItem\Lock\ItemNo\LotItemUniqueItemNoLockerCreateTrait;
use Sam\EntityMaker\LotItem\Lock\LotItemMakerLockingResult as Result;

/**
 * Class LotItemMakerLocker
 * @package Sam\EntityMaker\LotItem
 * @method LotItemMakerInputDto getInputDto()
 * @method LotItemMakerConfigDto getConfigDto()
 */
class LotItemMakerLocker extends CustomizableClass
{
    use LotItemUniqueConsignorLockerCreateTrait;
    use LotItemUniqueCustomFieldLockerCreateTrait;
    use LotItemUniqueItemNoLockerCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return Result
     */
    public function lock(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
    ): Result {
        $result = Result::new();
        $itemNoLockingResult = $this->createLotItemUniqueItemNoLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($itemNoLockingResult);
        $userLockingResult = $this->createLotItemUniqueConsignorLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($userLockingResult);
        $customFieldLockingResult = $this->createLotItemUniqueCustomFieldLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($customFieldLockingResult);
        return $result;
    }

    public function unlock(LotItemMakerConfigDto $configDto): LotItemMakerConfigDto
    {
        $configDto = $this->createLotItemUniqueItemNoLocker()->unlock($configDto);
        $configDto = $this->createLotItemUniqueConsignorLocker()->unlock($configDto);
        $configDto = $this->createLotItemUniqueCustomFieldLocker()->unlock($configDto);
        return $configDto;
    }
}
