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

namespace Sam\PublicMainMenu\Item\Produce\Multiple;

use Sam\Core\Service\CustomizableClass;
use Sam\PublicMainMenu\Item\Produce\Single\SinglePublicMainMenuItemProducerCreateTrait;

/**
 * Class MultiplePublicMainMenuItemProducer
 * @package Sam\PublicMainMenu\Item\Produce\Multiple
 */
class MultiplePublicMainMenuItemProducer extends CustomizableClass
{
    use SinglePublicMainMenuItemProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Actualize and persist public main menu items in DB
     *
     * @param PublicMainMenuItemDto[] $publicMainMenuDtos
     * @param int $accountId
     * @param int $editorUserid
     */
    public function produce(array $publicMainMenuDtos, int $accountId, int $editorUserid): void
    {
        foreach ($publicMainMenuDtos as $publicMainMenuDto) {
            $this->createSinglePublicMainMenuItemProducer()->produce($publicMainMenuDto, $accountId, $editorUserid);
        }
    }
}
