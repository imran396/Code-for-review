<?php
/**
 * SAM-11722: Public main menu management
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Item\Produce\Single;

use PublicMainMenuItem;
use Sam\Core\Service\CustomizableClass;
use Sam\PublicMainMenu\Item\Create\PublicMainMenuItemCreator;
use Sam\PublicMainMenu\Item\Load\PublicMainMenuItemLoaderCreateTrait;
use Sam\PublicMainMenu\Item\Produce\Multiple\PublicMainMenuItemDto;
use Sam\Storage\WriteRepository\Entity\PublicMainMenuItem\PublicMainMenuItemWriteRepositoryAwareTrait;

/**
 * Class SinglePublicMainMenuItemProducer
 * @package Sam\PublicMainMenu\Item\Produce\Single
 */
class SinglePublicMainMenuItemProducer extends CustomizableClass
{
    use PublicMainMenuItemWriteRepositoryAwareTrait;
    use PublicMainMenuItemLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(PublicMainMenuItemDto $publicMainMenuDto, int $accountId, int $editorUserid): PublicMainMenuItem
    {
        $menuItem = $this->createPublicMainMenuItemLoader()->loadByPage($publicMainMenuDto->key, $accountId);
        if (!$menuItem) {
            $menuItem = PublicMainMenuItemCreator::new()->create($publicMainMenuDto->key, $publicMainMenuDto->order, $accountId);
        }
        $menuItem->Visible = $publicMainMenuDto->visible;
        $menuItem->Order = $publicMainMenuDto->order;
        $this->getPublicMainMenuItemWriteRepository()->saveWithModifier($menuItem, $editorUserid);
        return $menuItem;
    }
}
