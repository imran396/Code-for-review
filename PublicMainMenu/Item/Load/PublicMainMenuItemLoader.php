<?php
/**
 * SAM-11722: Public main menu management
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Item\Load;

use PublicMainMenuItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\PublicMainMenu\Item\Create\PublicMainMenuItemCreator;
use Sam\Storage\ReadRepository\Entity\PublicMainMenuItem\PublicMainMenuItemReadRepository;
use Sam\Storage\ReadRepository\Entity\PublicMainMenuItem\PublicMainMenuItemReadRepositoryCreateTrait;

/**
 * Class PublicMainMenuItemLoader
 * @package Sam\PublicMainMenu\Item\Load
 */
class PublicMainMenuItemLoader extends CustomizableClass
{
    use PublicMainMenuItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId null - means to load all records from all accounts
     * @param bool $isReadOnlyDb
     * @return PublicMainMenuItem[]
     */
    public function loadAll(?int $accountId = null, bool $isReadOnlyDb = false): array
    {
        $menuItems = $this
            ->prepareRepository($accountId, $isReadOnlyDb)
            ->orderByOrder()
            ->loadEntities();
        return $menuItems;
    }

    public function loadByPage(int $page, int $accountId, bool $isReadOnlyDb = false): ?PublicMainMenuItem
    {
        $menuItem = $this
            ->prepareRepository($accountId, $isReadOnlyDb)
            ->filterPage($page)
            ->loadEntity();
        return $menuItem;
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return PublicMainMenuItem[]
     */
    public function loadOrCreatePublicMainMenuItems(int $accountId, bool $isReadOnlyDb = false): array
    {
        $publicMainMenu = $this->loadAll($accountId, $isReadOnlyDb);
        if (!$publicMainMenu) {
            // Init if not exist
            foreach (Constants\PublicMainMenu::PAGES as $key => $value) {
                $publicMainMenu[] = PublicMainMenuItemCreator::new()->create($key, $key, $accountId);
            }
        }
        return $publicMainMenu;
    }

    protected function prepareRepository(?int $accountId = null, bool $isReadOnlyDb = false): PublicMainMenuItemReadRepository
    {
        $repository = $this->createPublicMainMenuItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        if ($accountId) {
            $repository->filterAccountId($accountId);
        }
        return $repository;
    }
}
