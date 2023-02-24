<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider;

use LotFieldConfig;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\LotFieldConfig\Provider\Internal\ActualLotFieldConfigsDetectorCreateTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\DummyFieldMap;
use Sam\Lot\LotFieldConfig\Provider\Map\LotFieldMapInterface;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;


/**
 * Class LotFieldConfigProvider
 * @package Sam\Lot\LotFieldConfig\Provider
 */
class LotFieldConfigProvider extends CustomizableClass
{
    use ActualLotFieldConfigsDetectorCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * @var LotFieldMapInterface|null
     */
    protected ?LotFieldMapInterface $fieldMap = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setFieldMap(?LotFieldMapInterface $fieldMap): static
    {
        $this->fieldMap = $fieldMap;
        return $this;
    }

    /**
     * @param int $accountId
     * @return LotFieldConfig[]
     */
    public function loadActualLotFieldConfigs(int $accountId): array
    {
        $fn = function () use ($accountId) {
            $lotFieldConfigs = $this->createActualLotFieldConfigsDetector()->detect($accountId);
            $lotFieldConfigs = ArrayHelper::indexEntities($lotFieldConfigs, 'Index');
            return $lotFieldConfigs;
        };

        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey(
            Constants\MemoryCache::LOT_FIELD_CONFIG_ACCOUNT_ID,
            $accountId
        );
        $lotFieldConfigs = $this->getEntityMemoryCacheManager()->load($entityKey, $fn);

        return $lotFieldConfigs;
    }

    public function isRequired(string $field, int $accountId): bool
    {
        if ($this->getFieldMap()->isAlwaysOptionalField($field)) {
            return false;
        }
        return $this->getLotFieldConfig($field, $accountId)->Required ?? false;
    }

    public function isVisible(string $field, int $accountId): bool
    {
        $filedConfig = $this->getLotFieldConfig($field, $accountId);
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

    protected function getLotFieldConfig(string $field, int $accountId): ?LotFieldConfig
    {
        $index = $this->getFieldMap()->getFieldConfigIndex($field);
        if ($index) {
            return $this->getConfigByIndex($index, $accountId);
        }
        return null;
    }

    protected function getCustomFiledConfig(int $customFieldId, int $accountId): ?LotFieldConfig
    {
        $index = "fc{$customFieldId}";
        return $this->getConfigByIndex($index, $accountId);
    }

    protected function getConfigByIndex(string $index, int $accountId): ?LotFieldConfig
    {
        $fieldConfigs = $this->loadActualLotFieldConfigs($accountId);
        return $fieldConfigs[$index] ?? null;
    }

    protected function getFieldMap(): LotFieldMapInterface
    {
        if (!$this->fieldMap) {
            $this->fieldMap = DummyFieldMap::new();
        }
        return $this->fieldMap;
    }
}
