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

namespace Sam\PublicMainMenu\Item\Create;

use PublicMainMenuItem;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class PublicMainMenuItemCreator
 * @package Sam\PublicMainMenu\Item\Create
 */
class PublicMainMenuItemCreator extends CustomizableClass
{
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(int $page, int $order, int $accountId, bool $active = true, bool $visible = true): PublicMainMenuItem
    {
        $item = $this->createEntityFactory()->publicMainMenuItem();
        $item->AccountId = $accountId;
        $item->Active = $active;
        $item->Order = $order;
        $item->Page = $page;
        $item->Visible = $visible;
        return $item;
    }
}
