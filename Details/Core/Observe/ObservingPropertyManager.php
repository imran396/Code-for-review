<?php
/**
 * Helps to observe data changes related to Seo Url templates.
 * We describe placeholder related classes and properties in ConfigManager::$keysConfig[<key>][<type>]['observe']
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Observe;

use Account;
use Auction;
use AuctionAuctioneer;
use AuctionCache;
use AuctionCustData;
use AuctionCustField;
use AuctionLotItem;
use InvalidArgumentException;
use Location;
use LotItem;
use LotItemCustData;
use LotItemCustField;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\ConfigManagerAwareInterface;
use Sam\Details\Core\PlaceholderManager;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use SamTaxCountryStates;
use SettingAuction;
use SettingSeo;
use SettingSystem;
use TermsAndConditions;

/**
 * Class ObservingPropertyManager
 * @package Sam\Details
 */
abstract class ObservingPropertyManager extends CustomizableClass implements ConfigManagerAwareInterface
{
    use AuctionLoaderAwareTrait;
    use FilesystemCacheManagerAwareTrait;
    use LotItemLoaderAwareTrait;

    /**
     * @var array<array{'class': string, 'properties': string[]}>
     */
    protected array $alwaysObservingProperties = [];
    /**
     * @var string[]
     */
    protected array $availableClasses = [
        Account::class,
        Auction::class,
        AuctionAuctioneer::class,
        AuctionCache::class,
        AuctionCustData::class,
        AuctionCustField::class,
        AuctionLotItem::class,
        Location::class,
        LotItem::class,
        LotItemCustData::class,
        SamTaxCountryStates::class,
    ];
    /**
     * @var Account|Auction|AuctionAuctioneer|AuctionCache|AuctionCustData|AuctionCustField|AuctionLotItem|Location|LotItem|LotItemCustData|LotItemCustField|TermsAndConditions|SamTaxCountryStates
     */
    protected $entity;
    /**
     * @var string
     */
    protected string $keyPrefix = '';
    /**
     * @var array|null
     */
    protected ?array $observingProperties = null;
    /**
     * @var PlaceholderManager|null
     */
    protected ?PlaceholderManager $placeholderManager = null;
    /**
     * @var int|null
     */
    protected ?int $systemAccountId = null;
    /**
     * @var string|null
     */
    protected ?string $template = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function getEntity(
    ): \Account|\Auction|\AuctionAuctioneer|\AuctionCache|\AuctionCustData|\AuctionCustField|\AuctionLotItem|\Location|\LotItem|\LotItemCustData|\LotItemCustField|\SamTaxCountryStates|\SettingAuction|SettingSeo|SettingSystem|\TermsAndConditions
    {
        if ($this->entity === null) {
            throw new InvalidArgumentException("Observing entity not defined");
        }
        return $this->entity;
    }

    public function setEntity(object $entity): static
    {
        if (in_array($entity::class, $this->availableClasses)) {
            $this->entity = $entity;
        } else {
            $class = $entity::class;
            throw new InvalidArgumentException("Entity class ({$class}) not allowed");
        }
        return $this;
    }

    /**
     * @return string[]
     */
    public function getObservingProperties(): array
    {
        if ($this->observingProperties === null) {
            $this->observingProperties = $this->loadObservingProperties();
        }
        return $this->observingProperties;
    }

    public function getPlaceholderManager(): PlaceholderManager
    {
        if ($this->placeholderManager === null) {
            $this->placeholderManager = PlaceholderManager::new()
                ->setConfigManager($this->getConfigManager())
                ->setTemplate($this->getTemplate());
        }
        return $this->placeholderManager;
    }

    public function detectSystemAccountIdByEntity(
        \Account|\Auction|\AuctionAuctioneer|\AuctionCache|\AuctionCustData|\AuctionCustField|\AuctionLotItem|\Location|\LotItem|\LotItemCustData|\LotItemCustField|\SamTaxCountryStates|\TermsAndConditions|SettingAuction|SettingSeo|SettingSystem $entity
    ): ?int {
        $systemAccountId = null;
        $class = $entity::class;
        if ($entity instanceof Account) {
            $account = $entity;
            $systemAccountId = $account->Id;
        } elseif (in_array(
            $class,
            [
                Auction::class,
                AuctionAuctioneer::class,
                AuctionLotItem::class,
                Location::class,
                LotItem::class,
                SettingAuction::class,
                SettingSeo::class,
                SettingSystem::class,
                TermsAndConditions::class,
            ],
            true
        )
        ) {
            /** @var Auction|AuctionAuctioneer|AuctionLotItem|Location|LotItem|SettingAuction|SettingSystem|SettingSeo|TermsAndConditions $entity */
            $systemAccountId = $entity->AccountId;
        } elseif (in_array($class, [AuctionCache::class, AuctionCustData::class], true)) {
            /** @var AuctionCache|AuctionCustData $entity */
            $auctionId = $entity->AuctionId;
            $systemAccountId = $this->detectAccountIdByAuctionId($auctionId);
        } elseif ($entity instanceof LotItemCustData) {
            $lotCustomData = $entity;
            $systemAccountId = $this->detectAccountIdByLotItemId($lotCustomData->LotItemId);
        } elseif ($entity instanceof SamTaxCountryStates) {
            if ($entity->AccountId !== null) {
                $systemAccountId = $entity->AccountId;
            } elseif ($entity->LotItemId !== null) {
                $systemAccountId = $this->detectAccountIdByLotItemId($entity->LotItemId);
            } elseif ($entity->AuctionId !== null) {
                $systemAccountId = $this->detectAccountIdByAuctionId($entity->AuctionId);
            }
        }
        return $systemAccountId;
    }

    /**
     * @param int|null $auctionId null leads to null
     */
    private function detectAccountIdByAuctionId(?int $auctionId): ?int
    {
        if (!$auctionId) {
            return null;
        }
        $auction = $this->getAuctionLoader()
            ->clear()
            ->load($auctionId, true);
        if ($auction === null) {
            log_error("Available auction not found for detecting account" . composeSuffix(['a' => $auctionId]));
            return null;
        }
        return $auction->AccountId;
    }

    private function detectAccountIdByLotItemId(int $lotItemId): ?int
    {
        $lotItem = $this->getLotItemLoader()
            ->clear()
            ->load($lotItemId, true);
        if ($lotItem === null) {
            log_error("Available lot item not found for detecting account" . composeSuffix(['li' => $lotItemId]));
            return null;
        }
        return $lotItem->AccountId;
    }

    public function getSystemAccountId(): int
    {
        if ($this->systemAccountId === null) {
            $entity = $this->getEntity();
            $this->systemAccountId = $this->detectSystemAccountIdByEntity($entity);
        }
        if ($this->systemAccountId === null) {
            throw new InvalidArgumentException("SystemAccountId not defined");
        }
        return $this->systemAccountId;
    }

    public function setSystemAccountId(?int $systemAccountId): static
    {
        $this->systemAccountId = $systemAccountId;
        return $this;
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            throw new InvalidArgumentException("Template not defined");
        }
        return $this->template;
    }

    // Observing properties loading/saving/calculation

    /**
     * Return observing properties of observing entity. They are loaded from file cache
     * or calculated by information from config manager and db (and stored in file cache)
     * @return string[]
     */
    protected function loadObservingProperties(): array
    {
        $observingProperties = $this->loadObservingPropertiesFromCache();
        if ($observingProperties === null) {
            $class = $this->getEntity()::class;
            $props = $this->detectCustomFieldAdditionalProperties();
            $observingProperties = $this->calculateObservingProperties($class, $props);
            if ($observingProperties) {
                $this->saveObservingPropertiesToCache($observingProperties, $class, $props);
            }
        }
        return $observingProperties;
    }

    /**
     * Return additional arguments required for caching and identifying entities.
     * For custom field related placeholders processing optimization.
     * @return array - empty array [] when not found, eg. LotItemCustField deleted
     */
    protected function detectCustomFieldAdditionalProperties(): array
    {
        $entity = $this->getEntity();
        $props = [];
        if ($entity instanceof AuctionCustField) {
            $auctionCustomField = $entity;
            $acfKey = $this->getConfigManager()->makeAuctionCustomFieldKey($auctionCustomField->Name);
            $props = ['id' => $auctionCustomField->Id, 'key' => $acfKey];
        } elseif ($entity instanceof LotItemCustField) {
            $lotCustomField = $entity;
            $lcfKey = $this->getConfigManager()->makeLotCustomFieldKey($lotCustomField->Name);
            $props = ['id' => $lotCustomField->Id, 'key' => $lcfKey];
        } elseif ($entity instanceof AuctionCustData) {
            $auctionCustomData = $entity;
            $auctionCustomField = $this->getConfigManager()
                ->getAuctionCustomFieldById($auctionCustomData->AuctionCustFieldId);
            if ($auctionCustomField) {
                $acfKey = $this->getConfigManager()->makeAuctionCustomFieldKey($auctionCustomField->Name);
                $props = ['id' => $auctionCustomField->Id, 'key' => $acfKey];
            }
        } elseif ($entity instanceof LotItemCustData) {
            $lotCustomData = $entity;
            $lotCustomField = $this->getConfigManager()->getLotCustomFieldById($lotCustomData->LotItemCustFieldId);
            if ($lotCustomField) {
                $lcfKey = $this->getConfigManager()->makeLotCustomFieldKey($lotCustomField->Name);
                $props = ['id' => $lotCustomField->Id, 'key' => $lcfKey];
            }
        }
        return $props;
    }

    protected function isCustomFieldClass(string $className): bool
    {
        return in_array(
            $className,
            [
                AuctionCustField::class,
                LotItemCustField::class,
                AuctionCustData::class,
                LotItemCustData::class
            ],
            true
        );
    }

    /**
     * @param array $props additional info
     * @return string[]
     */
    protected function calculateObservingProperties(string $class, array $props = []): array
    {
        $observingProperties = [];
        $actualKeys = $this->getPlaceholderManager()->getActualKeys(true, true);
        if ($this->isCustomFieldClass($class)) {
            if (
                isset($props['key'])
                && in_array($props['key'], $actualKeys, true)
            ) {
                $observingProperties[] = $this->getConfigManager()
                    ->getObservingPropertiesForEntityClassByPlaceholderKeys($class, $actualKeys);
            }
        } else {
            $observingProperties[] = $this->getConfigManager()
                ->getObservingPropertiesForEntityClassByPlaceholderKeys($class, $actualKeys);
        }
        foreach ($this->alwaysObservingProperties as $alwaysObservingProperties) {
            if ($alwaysObservingProperties['class'] === $class) {
                $observingProperties[] = $alwaysObservingProperties['properties'];
            }
        }
        return array_merge([], ...$observingProperties);
    }

    /**
     * @return string[]|null
     */
    protected function loadObservingPropertiesFromCache(): ?array
    {
        $class = $this->getEntity()::class;
        $props = [];
        if ($this->isCustomFieldClass($class)) {
            $props = $this->detectCustomFieldAdditionalProperties();
            if (!$props) {
                return null;
            }
        }

        $cacheKey = $this->getObservingPropertiesCacheKey($class, $props);
        return $this->getFilesystemCacheManager()
            ->setExtension(FilesystemCacheManager::EXT_PHP)
            ->setNamespace('seo_url')
            ->get($cacheKey);
    }

    /**
     * Delete files for all observed entities
     */
    public function dropObservingPropertiesCaches(): void
    {
        foreach ($this->availableClasses as $class) {
            if (in_array($class, [AuctionCustData::class, AuctionCustField::class], true)) {
                $auctionCustomFields = $this->getConfigManager()->getAllAuctionCustomFields();
                foreach ($auctionCustomFields as $auctionCustomField) {
                    $acfKey = $this->getConfigManager()->makeAuctionCustomFieldKey($auctionCustomField->Name);
                    $props = ['id' => $auctionCustomField->Id, 'key' => $acfKey];
                    $this->dropObservingPropertiesCache($class, $props);
                }
            } elseif (in_array($class, [LotItemCustData::class, LotItemCustField::class], true)) {
                $lotCustomFields = $this->getConfigManager()->getAllLotCustomFields();
                foreach ($lotCustomFields as $lotCustomField) {
                    $lcfKey = $this->getConfigManager()->makeLotCustomFieldKey($lotCustomField->Name);
                    $props = ['id' => $lotCustomField->Id, 'key' => $lcfKey];
                    $this->dropObservingPropertiesCache($class, $props);
                }
            } else {
                $this->dropObservingPropertiesCache($class, []);
            }
        }
    }

    protected function dropObservingPropertiesCache(string $class, array $props): void
    {
        $cacheKey = $this->getObservingPropertiesCacheKey($class, $props);
        $this->getFilesystemCacheManager()
            ->setExtension(FilesystemCacheManager::EXT_PHP)
            ->setNamespace('seo_url')
            ->delete($cacheKey);
    }

    /**
     * Re-calculates all caches for observing properties
     */
    public function updateObservingPropertiesCaches(): void
    {
        $this->dropObservingPropertiesCaches();
        foreach ($this->availableClasses as $class) {
            if (in_array($class, [AuctionCustData::class, AuctionCustField::class], true)) {
                $auctionCustomFields = $this->getConfigManager()->getAllAuctionCustomFields();
                foreach ($auctionCustomFields as $auctionCustomField) {
                    $acfKey = $this->getConfigManager()->makeAuctionCustomFieldKey($auctionCustomField->Name);
                    $props = ['id' => $auctionCustomField->Id, 'key' => $acfKey];
                    $observingProperties = $this->calculateObservingProperties($class, $props);
                    $this->saveObservingPropertiesToCache($observingProperties, $class, $props);
                }
            } elseif (in_array($class, [LotItemCustData::class, LotItemCustField::class], true)) {
                $lotCustomFields = $this->getConfigManager()->getAllLotCustomFields();
                foreach ($lotCustomFields as $lotCustomField) {
                    $lcfKey = $this->getConfigManager()->makeLotCustomFieldKey($lotCustomField->Name);
                    $props = ['id' => $lotCustomField->Id, 'key' => $lcfKey];
                    $observingProperties = $this->calculateObservingProperties($class, $props);
                    $this->saveObservingPropertiesToCache($observingProperties, $class, $props);
                }
            } else {
                $observingProperties = $this->calculateObservingProperties($class);
                $this->saveObservingPropertiesToCache($observingProperties, $class);
            }
        }
    }

    /**
     * @param string[] $observingProperties
     */
    protected function saveObservingPropertiesToCache(array $observingProperties, string $class, array $props = []): void
    {
        $cacheKey = $this->getObservingPropertiesCacheKey($class, $props);
        $this->getFilesystemCacheManager()
            ->setExtension(FilesystemCacheManager::EXT_PHP)
            ->setNamespace('seo_url')
            ->set($cacheKey, $observingProperties);
    }

    protected function getObservingPropertiesCacheKey(string $class, array $props = []): string
    {
        if (in_array($class, [AuctionCustData::class, AuctionCustField::class], true)) {
            // $key = 'acf_' . $props['id'];
            $postfix = $props['key'];
        } elseif (in_array($class, [LotItemCustData::class, LotItemCustField::class], true)) {
            // $key = 'lcf_' . $props['id'];
            $postfix = $props['key'];
        } else {
            $postfix = 'acc_' . $this->getSystemAccountId();
        }
        return $this->keyPrefix . '_' . $class . '_' . $postfix;
    }

    /**
     * @return string[]
     */
    public function getAvailableClasses(): array
    {
        return $this->availableClasses;
    }

    public function isApplicable(string $entityClassName): bool
    {
        return in_array($entityClassName, $this->getAvailableClasses(), true);
    }
}
