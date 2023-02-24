<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Sam\Account\CrossAccountTransparency\CrossAccountTransparencyCheckerCreateTrait;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListAuctionTypeFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListCategoryMatchFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListCustomFieldsFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type\LotListTimedOptionFilterType;
use Sam\Api\GraphQL\Type\TypeRegistry;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class FilterFieldConfigurationFactory
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal
 */
class FilterFieldConfigurationFactory extends CustomizableClass
{
    use CrossAccountTransparencyCheckerCreateTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array<Constants\GraphQL::LOT_LIST_*_FILTER> $filterFields
     * @param int $systemAccountId
     * @param TypeRegistry $typeRegistry
     * @return array
     */
    public function create(array $filterFields, int $systemAccountId, TypeRegistry $typeRegistry): array
    {
        $filters = [];
        foreach ($filterFields as $filterField) {
            $configuration = $this->createForField($filterField, $systemAccountId, $typeRegistry);
            if ($configuration) {
                $filters[$filterField] = $configuration;
            }
        }

        return $filters;
    }

    /**
     * @param Constants\GraphQL::LOT_LIST_*_FILTER $filterField
     * @param int $systemAccountId
     * @param TypeRegistry $typeRegistry
     * @return Type[]|null
     */
    protected function createForField(string $filterField, int $systemAccountId, TypeRegistry $typeRegistry): ?array
    {
        return match ($filterField) {
            Constants\GraphQL::LOT_LIST_CATEGORY_ID_FILTER => [
                'type' => new ListOfType(Type::int())
            ],
            Constants\GraphQL::LOT_LIST_CATEGORY_MATCH_FILTER => [
                'type' => $typeRegistry->getTypeDefinition(LotListCategoryMatchFilterType::NAME)
            ],
            Constants\GraphQL::LOT_LIST_ONLY_FEATURED_FILTER,
            Constants\GraphQL::LOT_LIST_EXCLUDE_CLOSED_LOTS_FILTER,
            Constants\GraphQL::LOT_LIST_ONLY_UNASSIGNED_FILTER => [
                'type' => Type::boolean(),
                'defaultValue' => false
            ],
            Constants\GraphQL::LOT_LIST_AUCTION_FILTER => [
                'type' => Type::int(),
            ],
            Constants\GraphQL::LOT_LIST_AUCTION_TYPE_FILTER => [
                'type' => new ListOfType($typeRegistry->getTypeDefinition(LotListAuctionTypeFilterType::NAME))
            ],
            Constants\GraphQL::LOT_LIST_LOT_NO_FILTER,
            Constants\GraphQL::LOT_LIST_SEARCH_KEY_FILTER => [
                'type' => Type::string(),
            ],
            Constants\GraphQL::LOT_LIST_MIN_PRICE_FILTER,
            Constants\GraphQL::LOT_LIST_MAX_PRICE_FILTER => [
                'type' => Type::float(),
            ],
            Constants\GraphQL::LOT_LIST_TIMED_OPTION_FILTER => [
                'type' => new ListOfType($typeRegistry->getTypeDefinition(LotListTimedOptionFilterType::NAME))
            ],
            Constants\GraphQL::LOT_LIST_ACCOUNT_FILTER => $this->createAccountFilterConfiguration($systemAccountId),
            Constants\GraphQL::LOT_LIST_AUCTIONEER_FILTER => $this->createAuctioneerFilterConfiguration($systemAccountId),
            Constants\GraphQL::LOT_LIST_CUSTOM_FIELDS_FILTER => $this->createCustomFieldsFilterConfiguration($typeRegistry),
        };
    }

    protected function createAccountFilterConfiguration(int $accountId): ?array
    {
        $isAvailable = $this->createCrossAccountTransparencyChecker()->isAvailableByAccountId($accountId);
        if ($isAvailable) {
            return [
                'type' => new ListOfType(Type::int()),
            ];
        }
        return null;
    }

    protected function createAuctioneerFilterConfiguration(int $accountId): ?array
    {
        $isAvailable = (bool)$this->getSettingsManager()->get(Constants\Setting::AUCTIONEER_FILTER, $accountId);
        if ($isAvailable) {
            return [
                'type' => Type::int(),
            ];
        }
        return null;
    }

    protected function createCustomFieldsFilterConfiguration(TypeRegistry $typeRegistry): ?array
    {
        $customFieldsFilterTypeDefinition = $typeRegistry->getTypeDefinition(LotListCustomFieldsFilterType::NAME)();
        if ($customFieldsFilterTypeDefinition->getFields()) {
            return [
                'type' => $customFieldsFilterTypeDefinition
            ];
        }
        return null;
    }
}
