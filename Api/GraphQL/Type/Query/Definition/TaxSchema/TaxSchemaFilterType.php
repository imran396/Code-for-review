<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\TaxSchema;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Filter\StringFilterType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\TaxSchema
 */
class TaxSchemaFilterType extends CustomizableClass implements TypeInterface, AppContextAwareInterface, TypeRegistryAwareInterface
{
    use AppContextAwareTrait;
    use ApplicationAccessCheckerCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'TaxSchemaFilter';

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
                'fields' => $this->makeFieldsConfiguration(),
            ]
        );
    }

    protected function makeFieldsConfiguration(): array
    {
        $stringFilterType = $this->getTypeRegistry()->getTypeDefinition(StringFilterType::NAME);
        $fields = [
            'name' => $stringFilterType,
            'geoType' => new ListOfType(Type::int()),
            'country' => $stringFilterType,
            'city' => $stringFilterType,
            'county' => $stringFilterType,
            'state' => $stringFilterType,
            'forInvoice' => Type::boolean(),
            'forSettlement' => Type::boolean(),
            'amountSource' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(TaxSchemaAmountSourceType::NAME)),
        ];
        if ($this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->getAppContext()->editorUserId,
            $this->getAppContext()->systemAccountId,
            true
        )) {
            $fields['accountId'] = new ListOfType(Type::int());
        }
        return $fields;
    }
}
