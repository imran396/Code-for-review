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

namespace Sam\Lot\LotFieldConfig\Order;

use InvalidArgumentException;
use LotFieldConfig;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\LotFieldConfig\Meta\LotFieldConfigMetadata;
use Sam\Lot\LotFieldConfig\Meta\LotFieldConfigMetadataProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotFieldConfig\LotFieldConfigWriteRepositoryAwareTrait;

/**
 * Class LotFieldConfigOrderer
 * @package Sam\Lot\LotFieldConfig\Order
 */
class LotFieldConfigOrderer extends CustomizableClass
{
    use LotFieldConfigMetadataProviderAwareTrait;
    use LotFieldConfigProviderAwareTrait;
    use LotFieldConfigWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function resetAndSave(int $accountId, int $editorUserId): void
    {
        $fieldConfigs = $this->reset($accountId);
        foreach ($fieldConfigs as $fieldConfig) {
            $this->getLotFieldConfigWriteRepository()->saveWithModifier($fieldConfig, $editorUserId);
        }
    }

    /**
     * @param int $accountId
     * @return LotFieldConfig[]
     */
    public function reset(int $accountId): array
    {
        $fieldConfigs = $this->getLotFieldConfigProvider()->loadActualLotFieldConfigs($accountId);
        $fieldConfigs = ArrayHelper::assignProperty($fieldConfigs, 'Order', null);
        $fieldConfigMetadata = $this->getLotFieldConfigMetadataProvider()->getAll($accountId);
        $sortedFieldConfigs = $this->sort($fieldConfigs, $fieldConfigMetadata);

        $order = 1;
        foreach ($sortedFieldConfigs as $fieldConfig) {
            $fieldConfig->Order = $order++;
        }
        return $sortedFieldConfigs;
    }

    /**
     * @param LotFieldConfig[] $fieldConfigs
     * @param LotFieldConfigMetadata[] $fieldsConfigMetadata
     * @return LotFieldConfig[]
     */
    public function sort(array $fieldConfigs, array $fieldsConfigMetadata): array
    {
        $unorderedFields = $this->detectUnorderedFields($fieldConfigs);
        $orderedFields = $this->detectOrderedFields($fieldConfigs);
        do {
            $count = count($unorderedFields);
            foreach (
                [
                    Constants\LotFieldConfig::REL_DIR_AFTER,    // I suggest start resolving ordering from lots with AFTER relation
                    Constants\LotFieldConfig::REL_DIR_BEFORE
                ] as $processedDirection
            ) {
                foreach ($unorderedFields as $unorderedFieldIndex => $unorderedField) {
                    if ($fieldsConfigMetadata[$unorderedField]->relDir === $processedDirection) {
                        if ($fieldsConfigMetadata[$unorderedField]->relIndex) {
                            foreach ($orderedFields as $orderedFieldIndex => $orderedField) {
                                if ($orderedField === $fieldsConfigMetadata[$unorderedField]->relIndex) {
                                    if ($fieldsConfigMetadata[$unorderedField]->relDir === Constants\LotFieldConfig::REL_DIR_AFTER) {
                                        $orderedFieldIndex++;
                                    }
                                    $orderedFields = array_merge(
                                        array_slice($orderedFields, 0, $orderedFieldIndex),
                                        [$unorderedField],
                                        array_slice($orderedFields, $orderedFieldIndex)
                                    );
                                    unset($unorderedFields[$unorderedFieldIndex]);
                                    break;
                                }
                            }
                        } else {
                            array_unshift($orderedFields, $unorderedField);
                            unset($unorderedFields[$unorderedFieldIndex]);
                        }
                    }
                }
            }
        } while (count($unorderedFields) !== $count); // break if array length didn't changed

        // un-ordered fields still left, that may happen due to incorrect configuration
        if (count($unorderedFields) > 0) {
            $message = 'Can\'t resolve lot field ordering for' . composeSuffix(['index(es)' => implode(', ', $unorderedFields)]);
            throw new InvalidArgumentException($message);
        }

        $orderedFieldConfigs = [];
        foreach ($orderedFields as $field) {
            $orderedFieldConfigs[$field] = $fieldConfigs[$field];
        }
        return $orderedFieldConfigs;
    }

    /**
     * @param LotFieldConfig[] $fieldConfigs
     * @return array
     */
    protected function detectUnorderedFields(array $fieldConfigs): array
    {
        $fields = [];
        foreach ($fieldConfigs as $field => $config) {
            if ($config->Order === null) {
                $fields[] = $field;
            }
        }
        return $fields;
    }

    /**
     * @param LotFieldConfig[] $fieldConfigs
     * @return array
     */
    protected function detectOrderedFields(array $fieldConfigs): array
    {
        $fields = [];
        foreach ($fieldConfigs as $field => $config) {
            if ($config->Order !== null) {
                $fields[] = $field;
            }
        }
        return $fields;
    }
}
