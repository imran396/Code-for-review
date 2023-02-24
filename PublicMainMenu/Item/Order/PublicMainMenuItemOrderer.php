<?php
/**
 * SAM-11722: Public main menu management
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Item\Order;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\PublicMainMenu\Item\Load\PublicMainMenuItemLoaderCreateTrait;
use Sam\PublicMainMenu\Item\Produce\Multiple\PublicMainMenuItemDto;
use Sam\PublicMainMenu\Item\Produce\Multiple\MultiplePublicMainMenuItemProducerCreateTrait;
use Sam\PublicMainMenu\Render\MainMenuItem;

/**
 * Class PublicMainMenuItemOrderer
 * @package Sam\PublicMainMenu\Item\Order
 */
class PublicMainMenuItemOrderer extends CustomizableClass
{
    use PublicMainMenuItemLoaderCreateTrait;
    use MultiplePublicMainMenuItemProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Reset ordering or public menu items to default state and persist changes in DB.
     * @param int $accountId
     * @param int $editorUserId
     */
    public function resetAndSave(int $accountId, int $editorUserId): void
    {
        $publicMainMenuDtos = $this->prepareResetDtos($accountId);
        $this->createMultiplePublicMainMenuItemProducer()->produce($publicMainMenuDtos, $accountId, $editorUserId);
    }

    /**
     * Prepare the data of public menu items for saving in DB:
     * - collect their data in array of DTOs;
     * - reset order number to default state defined by constants.
     * @param int $accountId
     * @return PublicMainMenuItemDto[]
     */
    protected function prepareResetDtos(int $accountId): array
    {
        $menuItemsMetadata = $this->createPublicMainMenuItemLoader()->loadAll($accountId);

        $order = 1;
        $publicMainMenuItemDtos = [];
        foreach (Constants\PublicMainMenu::PAGES as $page => $name) {
            $existedPage = array_filter(
                $menuItemsMetadata,
                static function ($e) use ($page) {
                    return $e->Page === $page;
                }
            );

            $publicMainMenuItemDtos[] = PublicMainMenuItemDto::new()->construct(
                $page,
                $order++,
                $existedPage ? current($existedPage)->Visible : true
            );
        }
        return $publicMainMenuItemDtos;
    }

    /**
     * @param MainMenuItem[] $menuItems
     * @return MainMenuItem[]
     */
    public function sort(array $menuItems, int $accountId): array
    {
        $menuItemsSorted = [];
        $menuItemsMetadata = $this->createPublicMainMenuItemLoader()->loadAll($accountId);
        if (!$menuItemsMetadata) {
            return $menuItems;
        }

        foreach ($menuItemsMetadata as $value) {
            $page = Constants\PublicMainMenu::PAGES[$value->Page];
            $key = array_search($page, array_column($menuItems, 'name'), true);
            if (
                is_numeric($key)
                && $value->Visible
            ) {
                $menuItemsSorted[] = $menuItems[$key];
            }
        }
        return $menuItemsSorted;
    }
}
