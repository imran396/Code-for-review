<?php
/**
 * SAM-10719: SAM 3.7 Taxes. Add Search/Filter panel at Account Location List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Common\Filter;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class StringFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Common\Filter
 */
class StringFilterType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'StringFilter';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): InputObjectType
    {
        return new InputObjectType(
            [
                'name' => self::NAME,
                'fields' => [
                    'contain' => Type::string(),
                    'in' => new ListOfType(Type::string()),
                    'notIn' => new ListOfType(Type::string()),
                ]
            ]
        );
    }
}
