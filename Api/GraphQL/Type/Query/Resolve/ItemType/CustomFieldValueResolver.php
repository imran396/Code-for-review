<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\ItemType;

use GraphQL\Type\Definition\ResolveInfo;
use LotItemCustField;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Application\Url\Build\Config\CustomField\LotCustomFieldFileUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;

/**
 * Class CustomFieldValueResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\ItemType
 */
class CustomFieldValueResolver extends CustomizableClass implements FieldResolverInterface
{
    use UrlBuilderAwareTrait;

    protected LotItemCustField $customField;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(LotItemCustField $customField): static
    {
        $this->customField = $customField;
        return $this;
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        $dependentOnFields = [$this->detectDataSourceFieldName()];
        if ($this->customField->Type === Constants\CustomField::TYPE_FILE) {
            $dependentOnFields[] = 'id';
        }
        return $dependentOnFields;
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array
    {
        return [
            'value' => $this->renderValue($objectValue),
            'field' => $this->customField
        ];
    }

    protected function renderValue(array $objectValue): int|float|bool|string|null
    {
        $value = $objectValue[$this->detectDataSourceFieldName()];
        $value = match ($this->customField->Type) {
            Constants\CustomField::TYPE_DECIMAL => ((string)$value !== '')
                ? CustomDataDecimalPureCalculator::new()->calcRealValue(
                    (int)$value,
                    (int)$this->customField->Parameters
                )
                : null,
            Constants\CustomField::TYPE_FILE => $this->renderFile($value, $this->customField->Id, (int)$objectValue['id']),
            Constants\CustomField::TYPE_CHECKBOX => (bool)$value,
            default => $value,
        };
        return $value;
    }

    protected function renderFile(?string $value, int $customFieldId, int $lotItemId): ?string
    {
        if ($value === null) {
            return null;
        }
        $rendered = [];
        $files = explode('|', $value);
        foreach ($files as $fileName) {
            if ($fileName) {
                $rendered[] = $this->getUrlBuilder()->build(
                    LotCustomFieldFileUrlConfig::new()->forWeb($lotItemId, $customFieldId, $fileName)
                );
            }
        }
        return implode('|', $rendered);
    }

    protected function detectDataSourceFieldName(): string
    {
        return 'c' . DbTextTransformer::new()->toDbColumn($this->customField->Name);
    }
}
