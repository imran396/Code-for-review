<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Internal;

use GraphQL\Type\Definition\Type;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CustomFieldHelper
 * @package Sam\Api\GraphQL\Type\Query\Definition\Internal
 */
class CustomFieldHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectGraphqlType(int $customFieldType): Type
    {
        $type = match ($customFieldType) {
            Constants\CustomField::TYPE_DECIMAL => Type::float(),
            Constants\CustomField::TYPE_CHECKBOX => Type::boolean(),
            default => Type::string()
        };
        return $type;
    }
}
