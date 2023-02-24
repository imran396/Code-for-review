<?php
/**
 * Placeholders related data for translation and db access
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core;

use AuctionCustData;
use AuctionCustField;
use InvalidArgumentException;
use LotItemCustData;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\Details\Core\Placeholder\Placeholder;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class ConfigManager
 * @package Sam\Details
 */
abstract class ConfigManager extends CustomizableClass
{
    use AuctionCustomFieldLoaderAwareTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomFieldTranslationManagerAwareTrait;
    use UserCustomFieldTranslationManagerAwareTrait;

    /**
     * Available keys, null - means all keys are available
     */
    protected ?array $availableKeys = null;
    /**
     * Cache all auction custom fields
     * @var AuctionCustField[]|null
     */
    protected ?array $auctionCustomFields = null;
    /**
     * Available Lot Custom Fields, some of them are restricted by Lot Category
     * @var int[]|null
     */
    protected ?array $availableLotCustomFieldIds = null;
    /**
     * Enabled types, true by default
     * @var bool[]
     */
    protected array $enabledTypes = [
        Constants\Placeholder::REGULAR => true,
        Constants\Placeholder::MONEY => true,
        Constants\Placeholder::URL => true,
        Constants\Placeholder::INDEXED_ARRAY => true,
        Constants\Placeholder::DATE => true,
        Constants\Placeholder::DATE_ADDITIONAL => true,
        Constants\Placeholder::BOOLEAN => true,
        Constants\Placeholder::AUCTION_CUSTOM_FIELD => false,
        Constants\Placeholder::LOT_CUSTOM_FIELD => false,
        Constants\Placeholder::USER_CUSTOM_FIELD => false,
        Constants\Placeholder::LANG_LABEL => true,
        Constants\Placeholder::INLINE_TEXT => true,
        Constants\Placeholder::COMPOSITE => true,
        Constants\Placeholder::BEGIN_END => true,
    ];
    /**
     * Flag when we have completed observing info here (we should define with custom field info)
     */
    private bool $isOptionObserveCompleted = false;
    /**
     * <type> => [
     *   <key> => [
     *     'lang' => [<lang field key>, <lang section>],
     *     'select' => [<result set field>, ..],
     * @var array<array<string, array{lang?: string[], select: string[]|string, available?: bool, observable?:bool}>>
     */
    protected array $keysConfig = [];
    /**
     * Cache all lot custom fields
     * @var LotItemCustField[]
     */
    protected ?array $lotCustomFields = null;

    public function construct(): static
    {
        $this->applyDefaults();
        if ($this->isTypeEnabled(Constants\Placeholder::AUCTION_CUSTOM_FIELD)) {
            $this->addCustomFields($this->getAllAuctionCustomFields());
        }
        if ($this->isTypeEnabled(Constants\Placeholder::LOT_CUSTOM_FIELD)) {
            $this->addCustomFields($this->getAllLotCustomFields());
        }
        $this->addLangLabelsToConfig();
        return $this;
    }

    public function getAvailableKeys(): ?array
    {
        return $this->availableKeys;
    }

    /**
     * Define available keys
     * @param string[] $keys
     */
    public function setAvailableKeys(array $keys): static
    {
        $this->availableKeys = ArrayCast::makeStringArray($keys);
        return $this;
    }

    /**
     * @param bool[] $enabledTypes
     */
    public function setEnabledTypes(array $enabledTypes): static
    {
        $this->enabledTypes = ArrayCast::makeBoolArray($enabledTypes);
        return $this;
    }

    /**
     * Check if there are available placeholders of type
     */
    public function hasAvailableKeysForType(int $type): bool
    {
        if (
            $this->isTypeEnabled($type)
            && array_key_exists($type, $this->keysConfig)
        ) {
            foreach ($this->keysConfig[$type] as $configs) {
                if ($configs['available']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function enableType(int $type, bool $enable = true): static
    {
        $this->enabledTypes[$type] = $enable;
        return $this;
    }

    public function isTypeEnabled(int $type): bool
    {
        return $this->enabledTypes[$type];
    }

    /**
     * Apply default values, we make all keys available by default.
     * Lot custom fields may be not available because of category restriction.
     */
    protected function applyDefaults(): void
    {
        foreach ($this->keysConfig as &$keysOptions) {
            foreach ($keysOptions as $key => &$options) {
                $isAvailable = $this->availableKeys === null
                    || in_array($key, $this->availableKeys, true);
                $options['available'] = $isAvailable;
            }
        }
    }

    /**
     * Enable auction custom fields among these placeholders
     */
    public function enableAuctionCustomFields(bool $enable): static
    {
        $this->enableType(Constants\Placeholder::AUCTION_CUSTOM_FIELD, $enable);
        return $this;
    }

    /**
     * Enable lot item custom fields among these placeholders
     */
    public function enableLotCustomFields(bool $enable): static
    {
        $this->enableType(Constants\Placeholder::LOT_CUSTOM_FIELD, $enable);
        return $this;
    }

    /**
     * Add or update configuration options
     */
    public function with(int $type, string $key, array $options): static
    {
        $this->keysConfig[$type][$key] = $options;
        return $this;
    }

    /**
     * @return array<array<string, array{lang?: string[], select: string[]|string, available?: bool, observable?: bool}>>
     */
    public function getKeysConfig(): array
    {
        return $this->keysConfig;
    }

    /**
     * @param string[] $keys
     * @return string[]
     */
    public function getObservingPropertiesForEntityClassByPlaceholderKeys(string $class, array $keys): array
    {
        $properties = [];
        foreach ($keys ?: [] as $key) {
            $observingProperties = $this->getObservingProperties($key);
            foreach ($observingProperties as $observing) {
                if ($observing['class'] === $class) {
                    $properties = array_merge($properties, $observing['properties']);
                }
            }
        }
        return array_values(array_unique($properties));
    }

    public function getObservingProperties(string $key): array
    {
        $this->completeObserveOptions();
        $observingProperties = $this->getOption($key, 'observe');
        if ($observingProperties === null) {
            // It is fine that on-the-fly built placeholders do not have observing properties
            // (e.g. related to translation labels, installation config options, {time_left}).
            log_trace("ObservingProperties absent for placeholder " . Placeholder::decorateView($key));
            return [];
        }
        return $observingProperties;
    }

    /**
     * Add observing options for custom fields and others
     */
    protected function completeObserveOptions(): void
    {
        if (!$this->isOptionObserveCompleted) {
            foreach ($this->keysConfig as $type => $keysOptions) {
                if (in_array($type, Constants\Placeholder::$customFieldTypes, true)) {
                    // Complete observing information for custom field placeholders
                    foreach ($keysOptions as $key => $options) {
                        if ($type === Constants\Placeholder::AUCTION_CUSTOM_FIELD) {
                            $options['observe'] = [
                                ['class' => AuctionCustField::class, 'properties' => ['Active', 'Type']],
                                ['class' => AuctionCustData::class, 'properties' => ['Active', 'Numeric', 'Text']],
                            ];
                        } elseif ($type === Constants\Placeholder::LOT_CUSTOM_FIELD) {
                            $options['observe'] = [
                                ['class' => LotItemCustField::class, 'properties' => ['Active', 'Type']],
                                ['class' => LotItemCustData::class, 'properties' => ['Active', 'Numeric', 'Text']],
                            ];
                        } elseif ($type === Constants\Placeholder::USER_CUSTOM_FIELD) {
                            $options['observe'] = [
                                ['class' => UserCustField::class, 'properties' => ['Active', 'Type']],
                                ['class' => UserCustData::class, 'properties' => ['Active', 'Numeric', 'Text']],
                            ];
                        }
                        $this->with($type, $key, $options);
                    }
                } else {
                    foreach ($keysOptions as $key => $options) {
                        $selects = empty($options['select']) ? [] : (array)$options['select'];
                        $isObservable = $options['observable'] ?? null;
                        if ($isObservable === false) {
                            // Skip placeholders whose observability is explicitly disabled
                            continue;
                        }
                        foreach ($selects as $select) {
                            $observingProperties = $this->detectObservingProperties($select);
                            if (is_array($observingProperties)) {
                                $observe = empty($options['observe']) ? [] : $options['observe'];
                                $options['observe'] = array_merge($observe, $observingProperties);
                                $this->with($type, $key, $options);
                            }
                        }
                    }
                }
            }
            $this->isOptionObserveCompleted = true;
        }
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    protected function detectObservingProperties(string $resultField): ?array
    {
        throw new InvalidArgumentException("Observe options completion not implemented");
    }

    public function getOptionsByKey(string $key): array
    {
        foreach ($this->keysConfig as $options) {
            if (isset($options[$key])) {
                return $options[$key];
            }
        }
        return [];
    }

    /**
     * @return mixed
     */
    public function getOption(string $key, string $name): mixed
    {
        $options = $this->getOptionsByKey($key);
        return $options[$name] ?? null;
    }

    /**
     * Return placeholder keys in array grouped by type and without other options
     * @return array [[$type1] => [$key1, $key2], [$type2] => [$key3, $key4]]
     */
    public function getKeysGroupedByType(): array
    {
        $keysGroupedByType = [];
        foreach ($this->keysConfig as $type => $configsPerType) {
            $keysGroupedByType[$type] = array_keys($configsPerType);
        }
        return $keysGroupedByType;
    }

    /**
     * Return placeholder keys
     */
    public function getKeys(): array
    {
        $keys = [];
        foreach ($this->keysConfig as $configsPerType) {
            $keys = array_merge($keys, array_keys($configsPerType));
        }
        return $keys;
    }

    /**
     * @return string[]
     */
    public function getConfigsInSingleArray(): array
    {
        $all = [];
        foreach ($this->keysConfig as $configsPerType) {
            $configsPerType = array_keys($configsPerType);
            $all = array_merge($all, $configsPerType);
        }
        return $all;
    }

    public function makeBeginKey(string $relatedKey, bool $isLogicalNot = false): string
    {
        return sprintf(
            '%s%s__begin',
            $isLogicalNot ? '!' : '',
            $relatedKey
        );
    }

    public function makeEndKey(string $relatedKey, bool $isLogicalNot = false): string
    {
        return sprintf(
            '%s%s__end',
            $isLogicalNot ? '!' : '',
            $relatedKey
        );
    }

    public function makeLangLabelKey(string $relatedKey): string
    {
        return 'label_' . $relatedKey;
    }

    public function getLangLabelInfo(string $key): ?array
    {
        return empty($this->keysConfig[Constants\Placeholder::LANG_LABEL][$key]['lang'])
            ? null : $this->keysConfig[Constants\Placeholder::LANG_LABEL][$key]['lang'];
    }

    public function getAuctionCustomFieldByPlaceholderKey(string $placeholderKey): ?AuctionCustField
    {
        $customFields = $this->getAllAuctionCustomFields();
        return $customFields[$placeholderKey] ?? null;
    }

    /**
     * Find AuctionCustField by its id
     */
    public function getAuctionCustomFieldById(int $auctionCustomFieldId): ?AuctionCustField
    {
        foreach ($this->getAllAuctionCustomFields() ?: [] as $auctionCustomField) {
            if ($auctionCustomField->Id === $auctionCustomFieldId) {
                return $auctionCustomField;
            }
        }
        log_debug("AuctionCustField not found, probably deleted" . composeSuffix(['acf' => $auctionCustomFieldId]));
        return null;
    }

    public function getLotCustomFieldByPlaceholderKey(string $placeholderKey): ?LotItemCustField
    {
        $lotCustomFields = $this->getAllLotCustomFields();
        $customField = $lotCustomFields[$placeholderKey] ?? null;
        if ($customField === null) {
            log_error('Lot custom field cannot be found by placeholder' . composeSuffix(['key' => $placeholderKey]));
        }
        return $customField;
    }

    /**
     * Find LotItemCustField by its id
     */
    public function getLotCustomFieldById(int $lotCustomFieldId): ?LotItemCustField
    {
        foreach ($this->getAllLotCustomFields() as $lotCustomField) {
            if ($lotCustomField->Id === $lotCustomFieldId) {
                return $lotCustomField;
            }
        }
        log_debug("LotItemCustField not found, probably deleted" . composeSuffix(['lcf' => $lotCustomFieldId]));
        return null;
    }

    /**
     * Complete placeholder keys with custom fields
     * Note: Possibly Lot Details would output auction custom fields in further.
     * @param LotItemCustField[]|AuctionCustField[]|UserCustField[] $customFields
     */
    protected function addCustomFields(array $customFields): ConfigManager
    {
        foreach ($customFields as $key => $customField) {
            $type = $label = $section = null;
            $isAvailable = true;
            if ($customField instanceof LotItemCustField) {
                $type = Constants\Placeholder::LOT_CUSTOM_FIELD;
                $label = $this->getLotCustomFieldTranslationManager()->makeKeyForName($customField->Name);
                $section = 'customfields';
                $isAvailable = $this->isAvailableLotCustomField($customField->Id);
            } elseif ($customField instanceof AuctionCustField) {
                $type = Constants\Placeholder::AUCTION_CUSTOM_FIELD;
                $label = $this->getAuctionCustomFieldTranslationManager()->makeKeyForName($customField->Name);
                $section = 'auctioncustomfields';
            } elseif ($customField instanceof UserCustField) {
                $type = Constants\Placeholder::USER_CUSTOM_FIELD;
                $label = $this->getUserCustomFieldTranslationManager()->makeKeyForName($customField->Name);
                $section = 'usercustomfields';
            }
            $this->with(
                $type,
                $key,
                [
                    'lang' => [$label, $section],
                    'available' => $isAvailable,
                ]
            );
        }
        return $this;
    }

    /**
     * Complete placeholder keys with translation labels
     */
    protected function addLangLabelsToConfig(): void
    {
        $typeLang = Constants\Placeholder::LANG_LABEL;
        if (!$this->isTypeEnabled($typeLang)) {
            return;
        }
        foreach ($this->keysConfig as $type => $keysOptions) {
            if ($type === $typeLang) {
                continue;
            }
            foreach ($keysOptions as $key => $options) {
                if (!empty($options['lang'])) {
                    $langLabelKey = $this->makeLangLabelKey($key);
                    $this->with(
                        $typeLang,
                        $langLabelKey,
                        [
                            'available' => $options['available'],
                            'lang' => $options['lang'],
                        ]
                    );
                }
            }
        }
    }

    /**
     * Return array of all lotItem custom fields.
     * Indexed by placeholder key.
     * @return LotItemCustField[]
     */
    public function getAllLotCustomFields(): array
    {
        if (!$this->isTypeEnabled(Constants\Placeholder::LOT_CUSTOM_FIELD)) {
            return [];
        }
        if ($this->lotCustomFields === null) {
            $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
            $this->setAllLotCustomFields($lotCustomFields);
        }
        return $this->lotCustomFields;
    }

    /**
     * Indexed by placeholder key.
     * @param LotItemCustField[] $lotCustomFields
     */
    public function setAllLotCustomFields(array $lotCustomFields): static
    {
        $this->lotCustomFields = [];
        foreach ($lotCustomFields as $customField) {
            $key = $this->makeLotCustomFieldKey($customField->Name);
            $this->lotCustomFields[$key] = $customField;
        }
        if ($this->lotCustomFields) {
            $this->enableLotCustomFields(true);
        }
        return $this;
    }

    /**
     * Define custom fields that are available for current conditions (category linked to lot info template)
     * @param int[] $lotCustomFieldIds
     */
    public function setAvailableLotCustomFieldIds(array $lotCustomFieldIds): static
    {
        $this->availableLotCustomFieldIds = ArrayCast::makeIntArray($lotCustomFieldIds);
        return $this;
    }

    /**
     * When property is not explicitly defined, we consider all lot custom field as available
     * Available fields could be predefined according to concrete lot template linked with category.
     * Lot custom field access can be restricted by category.
     * @return int[]
     */
    public function getAvailableLotCustomFieldIds(): array
    {
        if ($this->availableLotCustomFieldIds === null) {
            $this->availableLotCustomFieldIds = [];
            foreach ($this->getAllLotCustomFields() as $lotCustomField) {
                $this->availableLotCustomFieldIds[] = $lotCustomField->Id;
            }
        }
        return $this->availableLotCustomFieldIds;
    }

    /**
     * Return placeholder key for lotItem custom field
     * @param string $name - custom field name
     */
    public function makeLotCustomFieldKey(string $name): string
    {
        return 'lcf_' . DbTextTransformer::new()->toDbColumn($name);
    }

    /**
     * Return array of all auction custom fields.
     * Indexed by placeholder key.
     * @return AuctionCustField[]
     */
    public function getAllAuctionCustomFields(): array
    {
        if (!$this->isTypeEnabled(Constants\Placeholder::AUCTION_CUSTOM_FIELD)) {
            return [];
        }
        if ($this->auctionCustomFields === null) {
            $auctionCustomFields = $this->getAuctionCustomFieldLoader()->loadAll();
            $this->setAllAuctionCustomFields($auctionCustomFields);
        }
        return $this->auctionCustomFields;
    }

    /**
     * Indexed by placeholder key.
     * @param AuctionCustField[] $auctionCustomFields
     */
    public function setAllAuctionCustomFields(array $auctionCustomFields): static
    {
        $this->auctionCustomFields = [];
        foreach ($auctionCustomFields as $customField) {
            $key = $this->makeAuctionCustomFieldKey($customField->Name);
            $this->auctionCustomFields[$key] = $customField;
        }
        if ($this->auctionCustomFields) {
            $this->enableAuctionCustomFields(true);
        }
        return $this;
    }

    /**
     * Return placeholder key for auction custom field
     * @param string $name - custom field name
     */
    public function makeAuctionCustomFieldKey(string $name): string
    {
        return 'acf_' . DbTextTransformer::new()->toDbColumn($name);
    }

    public function isAvailableLotCustomField(int $lotCustomFieldId): bool
    {
        return in_array($lotCustomFieldId, $this->getAvailableLotCustomFieldIds(), true);
    }
}
