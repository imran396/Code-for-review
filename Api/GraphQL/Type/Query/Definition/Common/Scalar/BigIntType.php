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

namespace Sam\Api\GraphQL\Type\Query\Definition\Common\Scalar;

use GraphQL\Error\Error;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\Type;
use GraphQL\Utils\Utils;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BigIntType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Common\Scalar
 */
class BigIntType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'BigInt';

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
        return new CustomScalarType([
            'name' => self::NAME,
            'serialize' => $this->serialize(...),
            'parseValue' => $this->parseValue(...),
            'parseLiteral' => $this->parseLiteral(...),
        ]);
    }

    protected function serialize(mixed $value): ?string
    {
        if ($value === '' || $value === null) {
            return null;
        }

        if (!ctype_digit($value)) {
            throw new Error('Invalid bigint value: ' . Utils::printSafe($value));
        }

        return (string)$value;
    }

    protected function parseValue(mixed $value): string
    {
        if (!ctype_digit($value)) {
            throw new Error('BigInt cannot represent non-integer value: ' . Utils::printSafe($value));
        }
        return (string)$value;
    }

    protected function parseLiteral(Node $valueNode, ?array $variables = null): string
    {
        if (
            $valueNode instanceof IntValueNode
            || $valueNode instanceof StringValueNode
        ) {
            $value = (string)$valueNode->value;
            if (ctype_digit($value)) {
                return $value;
            }
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new Error();
    }
}
