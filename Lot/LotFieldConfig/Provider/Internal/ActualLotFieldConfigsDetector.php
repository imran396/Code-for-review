<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider\Internal;

use LotFieldConfig;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\LotFieldConfig\Load\LotFieldConfigLoaderCreateTrait;
use Sam\Lot\LotFieldConfig\Meta\LotFieldConfigMetadata;
use Sam\Lot\LotFieldConfig\Meta\LotFieldConfigMetadataProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Order\LotFieldConfigOrdererCreateTrait;

/**
 * Class ActualLotFieldConfigsDetector
 * @package Sam\Lot\LotFieldConfig\Provider\Internal
 * @internal
 */
class ActualLotFieldConfigsDetector extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use LotFieldConfigLoaderCreateTrait;
    use LotFieldConfigMetadataProviderAwareTrait;
    use LotFieldConfigOrdererCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @return LotFieldConfig[]
     */
    public function detect(int $accountId): array
    {
        $configLoader = $this->createLotFieldConfigLoader();
        $fieldConfigs = $configLoader->loadForAccount($accountId);
        $fieldConfigsMetadata = $this->getLotFieldConfigMetadataProvider()->getAll($accountId);
        if (!$this->isFieldConfigsActual($fieldConfigs, $fieldConfigsMetadata)) {
            $fieldConfigs = $this->actualizeFieldConfigEntities(array_keys($fieldConfigsMetadata), $fieldConfigs, $accountId);
            $fieldConfigs = $this->createLotFieldConfigOrderer()->sort($fieldConfigs, $fieldConfigsMetadata);
        }
        $fieldConfigs = $this->applyRestrictions($fieldConfigs, $accountId);
        return $fieldConfigs;
    }

    /**
     * Apply default settings from template array
     * @param LotFieldConfig[] $lotFieldConfigs
     * @return LotFieldConfig[]
     */
    private function applyRestrictions(array $lotFieldConfigs, int $accountId): array
    {
        foreach ($lotFieldConfigs as $lotFieldConfig) {
            $metadata = $this->getLotFieldConfigMetadataProvider()->getByIndex($lotFieldConfig->Index, $accountId);
            if ($metadata->alwaysRequired) {
                $lotFieldConfig->Required = true;
            } elseif (!$metadata->requirable) {
                $lotFieldConfig->Required = false;
            }
            if ($metadata->alwaysVisible) {
                $lotFieldConfig->Visible = true;
            }
        }
        return $lotFieldConfigs;
    }

    /**
     * Check if each field of LotFieldConfig[] exists currently in system
     * and check if config field count is equal to actual field count (core->admin->inventory->fieldConfig including custom fields)
     * @param LotFieldConfig[] $lotFieldConfigs
     * @param LotFieldConfigMetadata[] $fieldsConfigsMetadata
     * @return bool
     */
    protected function isFieldConfigsActual(array $lotFieldConfigs, array $fieldsConfigsMetadata): bool
    {
        $configFieldCount = count($lotFieldConfigs);
        $actualFieldCount = count($fieldsConfigsMetadata);
        if ($configFieldCount !== $actualFieldCount) {
            log_debug("Config field count ({$configFieldCount}) not equal to actual count ({$actualFieldCount}). Config will be updated.");
            return false;
        }
        foreach ($lotFieldConfigs as $lotFieldConfig) {
            if (!isset($fieldsConfigsMetadata[$lotFieldConfig->Index])) {
                log_debug('Field with index "' . $lotFieldConfig->Index . '" is not among available fields. Config will be updated.');
                return false;
            }
        }
        return true;
    }

    /**
     * @param string[] $knownFields
     * @param LotFieldConfig[] $fieldConfigsInDb
     * @param int $accountId
     * @return LotFieldConfig[]
     */
    protected function actualizeFieldConfigEntities(array $knownFields, array $fieldConfigsInDb, int $accountId): array
    {
        $actualEntities = [];
        // Get existing lot field config entries and assign them to ordered and un-ordered field arrays
        foreach ($fieldConfigsInDb as $lotFieldConfigInDb) {
            if (in_array($lotFieldConfigInDb->Index, $knownFields, true)) {
                $actualEntities[$lotFieldConfigInDb->Index] = $lotFieldConfigInDb;
            }
        }

        // Create new objects for not existing lot field configs
        foreach ($knownFields as $field) {
            if (!array_key_exists($field, $actualEntities)) {
                $lotFieldConfig = $this->makeLotFieldConfigEntity($field, $accountId);
                $actualEntities[$lotFieldConfig->Index] = $lotFieldConfig;
            }
        }
        return $actualEntities;
    }

    protected function makeLotFieldConfigEntity(string $index, int $accountId): LotFieldConfig
    {
        $lotFieldConfig = $this->createEntityFactory()->lotFieldConfig();
        $lotFieldConfig->AccountId = $accountId;
        $lotFieldConfig->Index = $index;
        $lotFieldConfig->Visible = true;
        $lotFieldConfig->Active = true;
        $lotFieldConfig->Required = false;
        return $lotFieldConfig;
    }
}
