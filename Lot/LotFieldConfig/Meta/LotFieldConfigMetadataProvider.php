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

namespace Sam\Lot\LotFieldConfig\Meta;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\LotFieldConfig\Meta\Internal\Load\DataProviderCreateTrait;

/**
 * Class LotFieldConfigMetadataProvider
 * @package Sam\Lot\LotFieldConfig\Meta
 */
class LotFieldConfigMetadataProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;

    /**
     * @var LotFieldConfigMetadata[][]
     */
    protected array $metadataCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getByIndex(string $index, int $accountId): LotFieldConfigMetadata
    {
        $fieldsConfigMetadata = $this->getAll($accountId);
        if (!isset($fieldsConfigMetadata[$index])) {
            throw new \InvalidArgumentException("Metadata for index '{$index}' not found");
        }
        return $fieldsConfigMetadata[$index];
    }

    public function getAll(int $accountId): array
    {
        if (!array_key_exists($accountId, $this->metadataCache)) {
            $this->metadataCache[$accountId] = $this->buildMetadata($accountId);
        }
        return $this->metadataCache[$accountId];
    }

    /**
     * @return LotFieldConfigMetadata[]
     */
    protected function buildMetadata(int $accountId): array
    {
        $fieldsConfigMetadataDefinition = $this->cfg()->get('fieldConfig->inventory')->toArray();
        $fieldsConfigMetadata = [];
        $indexPrev = '';
        foreach ($fieldsConfigMetadataDefinition as $index => $metadataDefinition) {
            if ($index === 'CustomFields') {
                $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll(true);
                foreach ($lotCustomFields as $lotCustomField) {
                    $customFieldIndex = 'fc' . $lotCustomField->Id;
                    $fieldsConfigMetadata[$customFieldIndex] = LotFieldConfigMetadata::new()->construct(
                        $metadataDefinition['relIndex'] ?? $indexPrev,
                        $metadataDefinition['relDir'] ?? Constants\LotFieldConfig::REL_DIR_AFTER,
                        $metadataDefinition['required'] ?? false,
                        $metadataDefinition['requirable'] ?? false,
                        $metadataDefinition['alwaysRequired'] ?? false,
                        false
                    );
                    $indexPrev = $customFieldIndex;
                }
            } else {
                $fieldsConfigMetadata[$index] = LotFieldConfigMetadata::new()->construct(
                    $metadataDefinition['relIndex'] ?? $indexPrev,
                    $metadataDefinition['relDir'] ?? Constants\LotFieldConfig::REL_DIR_AFTER,
                    $metadataDefinition['required'] ?? false,
                    $metadataDefinition['requirable'] ?? false,
                    $metadataDefinition['alwaysRequired'] ?? false,
                    $this->isAlwaysVisible($index, $accountId),
                );
                $indexPrev = $index;
            }
        }
        return $fieldsConfigMetadata;
    }

    protected function isAlwaysVisible(string $fieldIndex, int $accountId): bool
    {
        return $fieldIndex === Constants\LotFieldConfig::BUY_NOW
            && $this->createDataProvider()->isBuyNowSelectQuantityEnabledForAccount($accountId);
    }
}
