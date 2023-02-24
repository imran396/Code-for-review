<?php
/**
 * SAM-10844: GraphQL extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\QueryType\Internal;

use GraphQL\Type\Definition\FieldDefinition;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Quick workaround to make parseValue work at webonyx/graphql-php:14.11.6
 * https://github.com/webonyx/graphql-php/issues/1178
 *
 * TODO: Remove after releasing webonyx/graphql-php greater than 14.11.6
 *
 * Class LotListCustomFieldFilterResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\QueryType\Internal
 */
class LotListCustomFieldFilterResolver extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function resolve(array $filterArgs, FieldDefinition $fieldDefinition): array
    {
        $customFieldFilterData = $filterArgs[Constants\GraphQL::LOT_LIST_CUSTOM_FIELDS_FILTER] ?? null;
        if (
            $customFieldFilterData
            && $this->isAssocArray($customFieldFilterData)
        ) {
            $parseValueClosure = $fieldDefinition
                ->getArg('filter')
                ->getType()
                ->getField(Constants\GraphQL::LOT_LIST_CUSTOM_FIELDS_FILTER)
                ->getType()
                ->config['parseValue'];
            $filterArgs[Constants\GraphQL::LOT_LIST_CUSTOM_FIELDS_FILTER] = $parseValueClosure($customFieldFilterData);
        }
        return $filterArgs;
    }

    protected function isAssocArray(array $array): bool
    {
        foreach (array_keys($array) as $key) {
            if (!is_int($key)) {
                return true;
            }
        }
        return false;
    }
}
