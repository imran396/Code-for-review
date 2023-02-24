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

namespace Sam\Auction\FieldConfig\Provider\Internal;

use AuctionFieldConfig;
use Sam\Auction\FieldConfig\Internal\Load\AuctionFieldConfigLoaderCreateTrait;
use Sam\Auction\FieldConfig\Meta\AuctionFieldConfigMetadata;
use Sam\Auction\FieldConfig\Meta\AuctionFieldConfigMetadataProviderAwareTrait;
use Sam\Auction\FieldConfig\Order\AuctionFieldConfigOrdererCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ActualFieldConfigDetector
 * @package Sam\Auction\FieldConfig\Provider\Internal
 * @internal
 */
class ActualFieldConfigDetector extends CustomizableClass
{
    use AuctionFieldConfigMetadataProviderAwareTrait;
    use AuctionFieldConfigLoaderCreateTrait;
    use AuctionFieldConfigOrdererCreateTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return actual field configs with applied restrictions
     *
     * @param int $accountId
     * @return AuctionFieldConfig[]
     */
    public function detect(int $accountId): array
    {
        $fieldConfigs = $this->createAuctionFieldConfigLoader()->loadAll($accountId);
        $fieldConfigMetadata = $this->getAuctionFieldConfigMetadataProvider()->getAll();
        if (!$this->isActualFieldConfigs($fieldConfigs, $fieldConfigMetadata)) {
            $fieldConfigs = $this->actualizeFieldConfigs(
                $fieldConfigs,
                array_keys($fieldConfigMetadata),
                $accountId
            );
            $fieldConfigs = $this->createAuctionFieldConfigOrderer()->sort($fieldConfigs, $fieldConfigMetadata);
        }
        $fieldConfigs = $this->applyRestrictions($fieldConfigs);
        return $fieldConfigs;
    }

    /**
     * Apply restriction rules from metadata
     * @param AuctionFieldConfig[] $auctionFieldConfigs
     * @return AuctionFieldConfig[]
     */
    private function applyRestrictions(array $auctionFieldConfigs): array
    {
        foreach ($auctionFieldConfigs as $auctionFieldConfig) {
            $metadata = $this->getAuctionFieldConfigMetadataProvider()->getByIndex($auctionFieldConfig->Index);
            if ($metadata->alwaysRequired) {
                $auctionFieldConfig->Required = true;
            } elseif (!$metadata->requirable) {
                $auctionFieldConfig->Required = false;
            }
        }
        return $auctionFieldConfigs;
    }

    /**
     * Check if each field of AuctionFieldConfig[] exists currently in system
     * and check if config field count is equal to actual field count (core->admin->auction->fieldConfig including custom fields)
     * @param AuctionFieldConfig[] $auctionFieldConfigs
     * @param AuctionFieldConfigMetadata[] $fieldsConfigMetadata
     * @return bool
     */
    protected function isActualFieldConfigs(array $auctionFieldConfigs, array $fieldsConfigMetadata): bool
    {
        $configFieldCount = count($auctionFieldConfigs);
        $actualFieldCount = count($fieldsConfigMetadata);
        if ($configFieldCount !== $actualFieldCount) {
            log_debug("Config field count ({$configFieldCount}) not equal to actual count ({$actualFieldCount}). Config will be updated.");
            return false;
        }
        foreach ($auctionFieldConfigs as $auctionFieldConfig) {
            if (!isset($fieldsConfigMetadata[$auctionFieldConfig->Index])) {
                log_debug('Field with index "' . $auctionFieldConfig->Index . '" is not among available fields. Config will be updated.');
                return false;
            }
        }
        return true;
    }

    /**
     * @param AuctionFieldConfig[] $existingFieldConfigs
     * @param string[] $knownFields
     * @param int $accountId
     * @return AuctionFieldConfig[]
     */
    protected function actualizeFieldConfigs(array $existingFieldConfigs, array $knownFields, int $accountId): array
    {
        $actualEntities = [];
        // Get existing field config entries and assign them to ordered and un-ordered field arrays
        foreach ($existingFieldConfigs as $fieldConfig) {
            if (in_array($fieldConfig->Index, $knownFields, true)) {
                $actualEntities[$fieldConfig->Index] = $fieldConfig;
            }
        }

        // Create new objects for not existing field configs
        foreach ($knownFields as $field) {
            if (!array_key_exists($field, $actualEntities)) {
                $actualEntities[$field] = $this->makeFieldConfigEntity($field, $accountId);
            }
        }
        return $actualEntities;
    }

    protected function makeFieldConfigEntity(string $index, int $accountId): AuctionFieldConfig
    {
        $fieldConfig = $this->createEntityFactory()->auctionFieldConfig();
        $fieldConfig->AccountId = $accountId;
        $fieldConfig->Index = $index;
        $fieldConfig->Visible = true;
        $fieldConfig->Active = true;
        $fieldConfig->Required = false;
        return $fieldConfig;
    }
}
