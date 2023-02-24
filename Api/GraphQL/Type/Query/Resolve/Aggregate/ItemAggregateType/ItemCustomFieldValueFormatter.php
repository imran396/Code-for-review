<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\ItemAggregateType;

use GraphQL\Type\Definition\ResolveInfo;
use LotItemCustField;
use Sam\Api\GraphQL\AppContext;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ItemCustomFieldValueFormatter
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\ItemAggregateType
 */
class ItemCustomFieldValueFormatter extends CustomizableClass
{
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

    public function format(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): float|bool|string|null
    {
        $value = $objectValue ? reset($objectValue) : null;
        if ($value === null) {
            return null;
        }

        $value = match ($this->customField->Type) {
            Constants\CustomField::TYPE_DECIMAL => ((string)$value !== '')
                ? CustomDataDecimalPureCalculator::new()->calcRealValue(
                    (int)$value,
                    (int)$this->customField->Parameters
                )
                : null,
            Constants\CustomField::TYPE_CHECKBOX => (bool)$value,
            default => (string)$value,
        };
        return $value;
    }
}
