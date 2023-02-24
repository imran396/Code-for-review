<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\AuctionType;

use GraphQL\Executor\Promise\Promise;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Api\GraphQL\Type\Query\Resolve\Internal\FieldDataMapperCreateTrait;

/**
 * Class AccountFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\LotType\Internal
 */
class AccountFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use FieldDataMapperCreateTrait;
    use FieldResolverHelperCreateTrait;

    protected const ACCOUNT_TO_DATA_FIELD_MAP = [
        'id' => 'account_id',
        'name' => 'account_name',
        'company_name' => 'account_company_name',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        $dependentOnFields = ['account_id'];
        $referencedFields = array_keys($referencedFieldNodes);
        $dataMapper = $this->createFieldDataMapper();
        if ($dataMapper->hasMappingForAllReferencedFields($referencedFields, self::ACCOUNT_TO_DATA_FIELD_MAP)) {
            $dependentOnFields = array_merge(
                $dependentOnFields,
                $dataMapper->collectDataFields($referencedFields, self::ACCOUNT_TO_DATA_FIELD_MAP)
            );
            $dependentOnFields = array_unique($dependentOnFields);
        }
        return $dependentOnFields;
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array|Promise|null
    {
        if ($objectValue['account_id'] === null) {
            return null;
        }

        $accountData = $this->createFieldDataMapper()->mapDataToType($objectValue, self::ACCOUNT_TO_DATA_FIELD_MAP);
        if ($this->createFieldResolverHelper()->hasEnoughDataToResolve($info, $accountData)) {
            return $accountData;
        }
        return $appContext->dataLoader->loadAccount((int)$objectValue['account_id']);
    }
}
