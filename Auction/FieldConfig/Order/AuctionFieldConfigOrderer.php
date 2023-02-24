<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Order;

use AuctionFieldConfig;
use InvalidArgumentException;
use Sam\Auction\FieldConfig\Meta\AuctionFieldConfigMetadata;
use Sam\Auction\FieldConfig\Meta\AuctionFieldConfigMetadataProviderAwareTrait;
use Sam\Auction\FieldConfig\Produce\AuctionFieldConfigDto;
use Sam\Auction\FieldConfig\Produce\AuctionFieldConfigProducerCreateTrait;
use Sam\Auction\FieldConfig\Provider\AuctionFieldConfigProviderAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionFieldConfigOrderer
 * @package Sam\Auction\FieldConfig\Order
 */
class AuctionFieldConfigOrderer extends CustomizableClass
{
    use AuctionFieldConfigMetadataProviderAwareTrait;
    use AuctionFieldConfigProviderAwareTrait;
    use AuctionFieldConfigProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Reset ordering or Auction Field Config records to default state and persist changes in DB.
     * @param int $accountId
     * @param int $editorUserId
     */
    public function resetAndSave(int $accountId, int $editorUserId): void
    {
        $fieldConfigDtos = $this->prepareResetDtos($accountId);
        $this->createAuctionFieldConfigProducer()->produce($fieldConfigDtos, $accountId, $editorUserId);
    }

    /**
     * Prepare the data of Auction Field Config records for saving in DB:
     * - collect their data in array of DTOs;
     * - reset order number to default state defined by installation config.
     * @param int $accountId
     * @return AuctionFieldConfigDto[]
     */
    public function prepareResetDtos(int $accountId): array
    {
        $fieldConfigs = $this->getAuctionFieldConfigProvider()->loadActualAuctionFieldConfigs($accountId);
        $fieldConfigs = ArrayHelper::assignProperty($fieldConfigs, 'Order', null);
        $fieldConfigMetadata = $this->getAuctionFieldConfigMetadataProvider()->getAll();
        $sortedFieldConfigs = $this->sort($fieldConfigs, $fieldConfigMetadata);

        $fieldConfigDtos = [];
        $order = 1;
        foreach ($sortedFieldConfigs as $fieldConfig) {
            $fieldConfigDtos[] = AuctionFieldConfigDto::new()->construct(
                $fieldConfig->Index,
                $order++,
                $fieldConfig->Visible,
                $fieldConfig->Required
            );
        }
        return $fieldConfigDtos;
    }

    /**
     * Detect fields order based on field configs and metadata.
     * Metadata contains default order and field config contains new order if it was changed.
     *
     * @param AuctionFieldConfig[] $fieldConfigs
     * @param AuctionFieldConfigMetadata[] $fieldsConfigMetadata
     * @return AuctionFieldConfig[]
     */
    public function sort(array $fieldConfigs, array $fieldsConfigMetadata): array
    {
        $unorderedFields = $this->detectUnorderedFields($fieldConfigs);
        $orderedFields = $this->detectOrderedFields($fieldConfigs);
        do {
            $count = count($unorderedFields);
            foreach ($unorderedFields as $unorderedFieldIndex => $unorderedField) {
                if ($fieldsConfigMetadata[$unorderedField]->relIndex) {
                    foreach ($orderedFields as $orderedFieldIndex => $orderedField) {
                        if ($orderedField === $fieldsConfigMetadata[$unorderedField]->relIndex) {
                            $orderedFieldIndex++;
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
        } while (count($unorderedFields) !== $count); // break if array length didn't changed

        // un-ordered fields still left, that may happen due to incorrect configuration
        if (count($unorderedFields) > 0) {
            $message = 'Can\'t resolve auction field ordering for' . composeSuffix(['index(es)' => implode(', ', $unorderedFields)]);
            throw new InvalidArgumentException($message);
        }

        $orderedFieldConfigs = [];
        foreach ($orderedFields as $field) {
            $orderedFieldConfigs[$field] = $fieldConfigs[$field];
        }
        return $orderedFieldConfigs;
    }

    /**
     * @param AuctionFieldConfig[] $fieldConfigs
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
     * @param AuctionFieldConfig[] $fieldConfigs
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
