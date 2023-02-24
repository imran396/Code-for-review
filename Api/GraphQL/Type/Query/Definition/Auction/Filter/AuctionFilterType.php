<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Account\CrossAccountTransparency\CrossAccountTransparencyCheckerCreateTrait;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Auction\AuctionList\DataSourceMysql;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class AuctionFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter
 */
class AuctionFilterType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface, AppContextAwareInterface
{
    use AppContextAwareTrait;
    use CrossAccountTransparencyCheckerCreateTrait;
    use SettingsManagerAwareTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'AuctionFilter';

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
        $filters = [
            Constants\GraphQL::AUCTION_LIST_STATUS_FILTER => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionStatusFilterType::NAME),
                'defaultValue' => DataSourceMysql::DEF,
            ],
            Constants\GraphQL::AUCTION_LIST_AUCTION_TYPE_FILTER => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionTypeFilterType::NAME)
            ],
        ];
        if ($this->isAccountFilterAvailable($this->getAppContext()->systemAccountId)) {
            $filters[Constants\GraphQL::AUCTION_LIST_ACCOUNT_FILTER] = [
                'type' => Type::int()
            ];
        }
        if ($this->isAuctioneerFilterAvailable($this->getAppContext()->systemAccountId)) {
            $filters[Constants\GraphQL::AUCTION_LIST_AUCTIONEER_FILTER] = [
                'type' => Type::int(),
            ];
        }
        if ($this->isOnlyRegisteredFilterAvailable($this->getAppContext()->editorUserId)) {
            $filters[Constants\GraphQL::AUCTION_LIST_ONLY_REGISTERED_IN_FILTER] = [
                'type' => Type::boolean(),
                'defaultValue' => false,
            ];
        }

        return new InputObjectType(
            [
                'name' => self::NAME,
                'fields' => $filters
            ]
        );
    }

    protected function isAccountFilterAvailable(int $accountId): bool
    {
        return $this->createCrossAccountTransparencyChecker()->isAvailableByAccountId($accountId);
    }

    protected function isAuctioneerFilterAvailable(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::AUCTIONEER_FILTER, $accountId);
    }

    protected function isOnlyRegisteredFilterAvailable(?int $editorUserId): bool
    {
        return $editorUserId !== null;
    }
}
