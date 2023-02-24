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

/**
 * Trait PublicMainMenuItemLoaderCreateTrait
 * @package Sam\PublicMainMenu\Item\Load
 */
trait PublicMainMenuItemLoaderCreateTrait
{
    protected ?PublicMainMenuItemLoader $publicMainMenuItemLoader = null;

    /**
     * @return PublicMainMenuItemLoader
     */
    protected function createPublicMainMenuItemLoader(): PublicMainMenuItemLoader
    {
        return $this->publicMainMenuItemLoader ?: PublicMainMenuItemLoader::new();
    }

    /**
     * @param PublicMainMenuItemLoader $publicMainMenuItemLoader
     * @return static
     * @internal
     */
    public function setPublicMainMenuItemLoader(PublicMainMenuItemLoader $publicMainMenuItemLoader): static
    {
        $this->publicMainMenuItemLoader = $publicMainMenuItemLoader;
        return $this;
    }
}
