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

/**
 * Trait PublicMainMenuItemOrdererCreateTrait
 * @package Sam\PublicMainMenu\Item\Order
 */
trait PublicMainMenuItemOrdererCreateTrait
{
    protected ?PublicMainMenuItemOrderer $publicMainMenuItemOrderer = null;

    /**
     * @return PublicMainMenuItemOrderer
     */
    protected function createPublicMainMenuItemOrderer(): PublicMainMenuItemOrderer
    {
        return $this->publicMainMenuItemOrderer ?: PublicMainMenuItemOrderer::new();
    }

    /**
     * @param PublicMainMenuItemOrderer $publicMainMenuItemOrderer
     * @return static
     * @internal
     */
    public function setPublicMainMenuItemOrderer(PublicMainMenuItemOrderer $publicMainMenuItemOrderer): static
    {
        $this->publicMainMenuItemOrderer = $publicMainMenuItemOrderer;
        return $this;
    }
}
