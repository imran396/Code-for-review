<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use RuntimeException;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\LotAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\TaxDefinitionAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\TaxSchemaAggregateType;
use Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter\AuctionFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Location\LocationFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Location\LocationOrderType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\LotListCatalogFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\LotListMyItemsFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\LotListSearchFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\MyItemsPageType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\LotListCatalogSortType;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\LotListSearchSortType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxDefinition\TaxDefinitionFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxDefinition\TaxDefinitionOrderType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchema\TaxSchemaFilterType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchema\TaxSchemaOrderType;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryType
 * @package Sam\Api\GraphQL\Type
 */
class QueryType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'Query';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): ObjectType
    {
        return new ObjectType(
            [
                'name' => self::NAME,
                'fields' => [
                    'auction' => [
                        'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionType::NAME),
                        'args' => [
                            'id' => new NonNull(Type::int())
                        ],
                        'resolver' => Resolve\QueryType\SingleAuctionFieldResolver::new(),
                    ],
                    'auctions' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(AuctionType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(AuctionFilterType::NAME),
                        ],
                        'resolver' => Resolve\QueryType\AuctionsFieldResolver::new(),
                    ],
                    'lot' => [
                        'type' => $this->getTypeRegistry()->getTypeDefinition(LotType::NAME),
                        'args' => [
                            'lotItemId' => new NonNull(Type::int()),
                            'auctionId' => new NonNull(Type::int()),
                        ],
                        'resolver' => Resolve\QueryType\LotFieldResolver::new(),
                    ],
                    'item' => [
                        'type' => $this->getTypeRegistry()->getTypeDefinition(ItemType::NAME),
                        'args' => [
                            'id' => new NonNull(Type::int()),
                        ],
                        'resolver' => Resolve\QueryType\ItemFieldResolver::new(),
                    ],
                    'catalog_lots' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LotType::NAME)),
                        'args' => [
                            'auctionId' => new NonNull(Type::int()),
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(LotListCatalogFilterType::NAME),
                            'sort' => $this->getTypeRegistry()->getTypeDefinition(LotListCatalogSortType::NAME),
                        ],
                        'resolver' => Resolve\QueryType\CatalogLotsFieldResolver::new(),
                    ],
                    'catalog_lots_aggregate' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LotAggregateType::NAME)),
                        'args' => [
                            'auctionId' => new NonNull(Type::int()),
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(LotListCatalogFilterType::NAME),
                        ],
                        'resolver' => Resolve\Aggregate\QueryType\CatalogLotsAggregateFieldResolver::new(),
                    ],
                    'search_lots' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LotType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(LotListSearchFilterType::NAME),
                            'sort' => $this->getTypeRegistry()->getTypeDefinition(LotListSearchSortType::NAME),
                        ],
                        'resolver' => Resolve\QueryType\SearchLotsFieldResolver::new(),
                    ],
                    'search_lots_aggregate' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LotAggregateType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(LotListSearchFilterType::NAME),
                        ],
                        'resolver' => Resolve\Aggregate\QueryType\SearchLotsAggregateFieldResolver::new(),
                    ],
                    'my_items' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LotType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'pageType' => new NonNull($this->getTypeRegistry()->getTypeDefinition(MyItemsPageType::NAME)),
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(LotListMyItemsFilterType::NAME),
                            'sort' => $this->getTypeRegistry()->getTypeDefinition(LotListSearchSortType::NAME),
                        ],
                        'resolver' => Resolve\QueryType\MyItemsFieldResolver::new(),
                    ],
                    'my_items_aggregate' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LotAggregateType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'pageType' => new NonNull($this->getTypeRegistry()->getTypeDefinition(MyItemsPageType::NAME)),
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(LotListMyItemsFilterType::NAME),
                        ],
                        'resolver' => Resolve\Aggregate\QueryType\MyItemsAggregateFieldResolver::new(),
                    ],
                    'accounts' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(AccountType::NAME)),
                        'resolver' => Resolve\QueryType\AccountsFieldResolver::new(),
                    ],
                    'auctioneers' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(AuctioneerType::NAME)),
                        'resolver' => Resolve\QueryType\AuctioneersFieldResolver::new(),
                    ],
                    'locations' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LocationType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(LocationFilterType::NAME),
                            'order' => $this->getTypeRegistry()->getTypeDefinition(LocationOrderType::NAME),
                            'unique' => [
                                'type' => Type::boolean(),
                                'defaultValue' => false,
                            ],
                        ],
                        'resolver' => Resolve\QueryType\LocationsFieldResolver::new(),
                    ],
                    'tax_definitions' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(TaxDefinitionType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(TaxDefinitionFilterType::NAME),
                            'order' => $this->getTypeRegistry()->getTypeDefinition(TaxDefinitionOrderType::NAME),
                        ],
                        'resolver' => Resolve\QueryType\TaxDefinitionsFieldResolver::new(),
                    ],
                    'tax_definitions_aggregate' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(TaxDefinitionAggregateType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(TaxDefinitionFilterType::NAME),
                        ],
                        'resolver' => Resolve\Aggregate\QueryType\TaxDefinitionsAggregateFieldResolver::new(),
                    ],
                    'tax_schemas' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(TaxSchemaType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(TaxSchemaFilterType::NAME),
                            'order' => $this->getTypeRegistry()->getTypeDefinition(TaxSchemaOrderType::NAME),
                        ],
                        'resolver' => Resolve\QueryType\TaxSchemasFieldResolver::new(),
                    ],
                    'tax_schemas_aggregate' => [
                        'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(TaxSchemaAggregateType::NAME)),
                        'args' => [
                            'offset' => [
                                'type' => Type::int(),
                                'defaultValue' => 0,
                            ],
                            'limit' => [
                                'type' => Type::int(),
                                'defaultValue' => 10,
                            ],
                            'filter' => $this->getTypeRegistry()->getTypeDefinition(TaxSchemaFilterType::NAME),
                        ],
                        'resolver' => Resolve\Aggregate\QueryType\TaxSchemaAggregateFieldResolver::new(),
                    ],
                ],
                'resolveField' => function (array $rootValue, array $args, AppContext $context, ResolveInfo $info) {
                    $context->dataLoader->applyQueryPlan($info->fieldDefinition, $info->lookAhead());

                    $resolver = $info->fieldDefinition->config['resolver'] ?? null;
                    if ($resolver) {
                        if (
                            !is_a($resolver, Resolve\FieldResolverInterface::class)
                            && !is_a($resolver, Resolve\Aggregate\AggregateFieldResolverInterface::class)
                        ) {
                            throw new RuntimeException('Field resolver must be of type FieldResolverInterface got: ' . get_debug_type($resolver));
                        }
                        return $resolver->resolve($rootValue, $args, $context, $info);
                    }
                    return null;
                },
            ]
        );
    }
}
