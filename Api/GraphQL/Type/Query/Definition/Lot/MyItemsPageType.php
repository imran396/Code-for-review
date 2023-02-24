<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot;

use GraphQL\Type\Definition\EnumType;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\TypeInterface;

/**
 * Class MyItemsPageType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot
 */
class MyItemsPageType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'MyItemsPage';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): EnumType
    {
        return new EnumType([
            'name' => self::NAME,
            'values' => [
                Constants\GraphQL::MY_ITEMS_PAGE_ALL => ['value' => Constants\Page::ALL],
                Constants\GraphQL::MY_ITEMS_PAGE_WON => ['value' => Constants\Page::WON],
                Constants\GraphQL::MY_ITEMS_PAGE_NOTWON => ['value' => Constants\Page::NOTWON],
                Constants\GraphQL::MY_ITEMS_PAGE_BIDDING => ['value' => Constants\Page::BIDDING],
                Constants\GraphQL::MY_ITEMS_PAGE_WATCHLIST => ['value' => Constants\Page::WATCHLIST],
                Constants\GraphQL::MY_ITEMS_PAGE_CONSIGNED => ['value' => Constants\Page::CONSIGNED],
            ]
        ]);
    }
}
