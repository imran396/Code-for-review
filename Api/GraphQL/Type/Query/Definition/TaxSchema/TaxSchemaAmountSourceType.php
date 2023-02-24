<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\TaxSchema;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaAmountSourceType
 * @package Sam\Api\GraphQL\Type\Query\Definition\TaxSchema
 */
class TaxSchemaAmountSourceType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'TaxSchemaAmountSource';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): Type
    {
        $values = [];
        foreach (Constants\GraphQL::TAX_SCHEMA_AMOUNT_SOURCE_ENUM_VALUES as $value => $name) {
            $values[$name] = ['value' => $value];
        }
        return new EnumType([
            'name' => self::NAME,
            'values' => $values
        ]);
    }
}
