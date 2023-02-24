<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Produce;

use LotFieldConfig;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\LotFieldConfig\Delete\LotFieldConfigDeleterCreateTrait;
use Sam\Lot\LotFieldConfig\Load\LotFieldConfigLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotFieldConfig\LotFieldConfigWriteRepositoryAwareTrait;

/**
 * Class LotFieldConfigProducer
 * @package Sam\Lot\LotFieldConfig\Produce
 */
class LotFieldConfigProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use LotFieldConfigDeleterCreateTrait;
    use LotFieldConfigLoaderCreateTrait;
    use LotFieldConfigWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotFieldConfigDto[] $fieldConfigDtos
     * @param int $accountId
     * @param int $editorUserid
     */
    public function produce(array $fieldConfigDtos, int $accountId, int $editorUserid): void
    {
        $fieldConfigIds = [];
        foreach ($fieldConfigDtos as $fieldConfigDto) {
            $fieldConfig = $this->createLotFieldConfigLoader()->loadByIndexForAccount($fieldConfigDto->key, $accountId);
            if (!$fieldConfig) {
                $fieldConfig = $this->makeFieldConfigEntity($fieldConfigDto->key, $accountId);
            }
            $fieldConfig->Required = $fieldConfigDto->required;
            $fieldConfig->Visible = $fieldConfigDto->visible;
            $fieldConfig->Order = $fieldConfigDto->order;
            $this->getLotFieldConfigWriteRepository()->saveWithModifier($fieldConfig, $editorUserid);
            $fieldConfigIds[] = $fieldConfig->Id;
        }
        $this->deleteAbsentFieldConfigs($fieldConfigIds, $accountId, $editorUserid);
    }

    protected function deleteAbsentFieldConfigs(array $existingFieldConfigIds, int $accountId, int $editorUserId): void
    {
        $fieldConfigs = $this->createLotFieldConfigLoader()->loadForAccount($accountId);
        foreach ($fieldConfigs as $fieldConfig) {
            if (!in_array($fieldConfig->Id, $existingFieldConfigIds, true)) {
                $this->createLotFieldConfigDeleter()->delete($fieldConfig, $editorUserId);
            }
        }
    }

    protected function makeFieldConfigEntity(string $index, int $accountId): LotFieldConfig
    {
        $fieldConfig = $this->createEntityFactory()->lotFieldConfig();
        $fieldConfig->AccountId = $accountId;
        $fieldConfig->Index = $index;
        $fieldConfig->Active = true;
        return $fieldConfig;
    }
}
