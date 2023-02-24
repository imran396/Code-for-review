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

namespace Sam\Auction\FieldConfig\Provider;

use AuctionFieldConfig;
use Sam\Auction\FieldConfig\Provider\Internal\ActualFieldConfigDetectorCreateTrait;
use Sam\Auction\FieldConfig\Provider\Map\AuctionFieldMapInterface;
use Sam\Auction\FieldConfig\Provider\Map\DummyFieldMap;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Provide field config options
 *
 * Class AuctionFieldConfigProvider
 * @package Sam\Auction\FieldConfig\Provider
 */
class AuctionFieldConfigProvider extends CustomizableClass
{
    use ActualFieldConfigDetectorCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * @var AuctionFieldMapInterface|null
     */
    protected ?AuctionFieldMapInterface $fieldMap = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setFieldMap(?AuctionFieldMapInterface $fieldMap): static
    {
        $this->fieldMap = $fieldMap;
        return $this;
    }

    /**
     * @param int $accountId
     * @return AuctionFieldConfig[]
     */
    public function loadActualAuctionFieldConfigs(int $accountId): array
    {
        $fn = function () use ($accountId) {
            $auctionFieldConfigs = $this->createActualFieldConfigDetector()->detect($accountId);
            $auctionFieldConfigs = ArrayHelper::indexEntities($auctionFieldConfigs, 'Index');
            return $auctionFieldConfigs;
        };

        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::AUCTION_FIELD_CONFIG_ACCOUNT_ID,
            $accountId
        );
        $auctionFieldConfigs = $this->getEntityMemoryCacheManager()->load($entityKey, $fn);

        return $auctionFieldConfigs;
    }

    public function isRequired(string $field, int $accountId): bool
    {
        if ($this->getFieldMap()->isAlwaysOptionalField($field)) {
            return false;
        }
        return $this->getAuctionFieldConfig($field, $accountId)->Required ?? false;
    }

    public function isVisible(string $field, int $accountId): bool
    {
        $filedConfig = $this->getAuctionFieldConfig($field, $accountId);
        $isRequired = $filedConfig->Required ?? false;
        $isVisible = $filedConfig->Visible ?? true;
        return $isRequired || $isVisible;
    }

    public function isRequiredCustomField(int $customFieldId, int $accountId): bool
    {
        return $this->getCustomFiledConfig($customFieldId, $accountId)->Required ?? false;
    }

    public function isVisibleCustomField(int $customFieldId, int $accountId): bool
    {
        $filedConfig = $this->getCustomFiledConfig($customFieldId, $accountId);
        $isRequired = $filedConfig->Required ?? false;
        $isVisible = $filedConfig->Visible ?? true;
        return $isRequired || $isVisible;
    }

    protected function getAuctionFieldConfig(string $field, int $accountId): ?AuctionFieldConfig
    {
        $index = $this->getFieldMap()->getFieldConfigIndex($field);
        if ($index) {
            return $this->getConfigByIndex($index, $accountId);
        }
        return null;
    }

    protected function getCustomFiledConfig(int $customFieldId, int $accountId): ?AuctionFieldConfig
    {
        $index = "fc{$customFieldId}";
        return $this->getConfigByIndex($index, $accountId);
    }

    protected function getConfigByIndex(string $index, int $accountId): ?AuctionFieldConfig
    {
        $fieldConfigs = $this->loadActualAuctionFieldConfigs($accountId);
        return $fieldConfigs[$index] ?? null;
    }

    protected function getFieldMap(): AuctionFieldMapInterface
    {
        if (!$this->fieldMap) {
            $this->fieldMap = DummyFieldMap::new();
        }
        return $this->fieldMap;
    }
}
