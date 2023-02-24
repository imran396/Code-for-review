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

/**
 * Contains public menu input data intended for updating public menu
 *
 * Class PublicMainMenuItemDto
 * @package Sam\PublicMainMenu\Item\Produce\Multiple
 */
class PublicMainMenuItemDto extends CustomizableClass
{
    /**
     * @var int
     */
    public int $key;
    /**
     * @var int
     */
    public int $order;
    /**
     * @var bool
     */
    public bool $visible;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $key, int $order, bool $visible): static
    {
        $this->key = $key;
        $this->order = $order;
        $this->visible = $visible;
        return $this;
    }
}
