<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\PublicMainMenu\Render\Internal\MenuItem\MainMenuItemConstants;

/**
 * Class MainMenuItem
 * @package Sam\PublicMainMenu\Render
 */
class MainMenuItem extends CustomizableClass
{
    public readonly string $name;
    public readonly string $title;
    public readonly string $url;
    public readonly bool $isCurrent;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $name
     * @param string $title
     * @param string $url
     * @param bool $isCurrent
     * @return $this
     */
    public function construct(string $name, string $title, string $url, bool $isCurrent = false): static
    {
        $this->name = $name;
        $this->title = $title;
        $this->url = $url;
        $this->isCurrent = $isCurrent;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLogout(): bool
    {
        return $this->name === MainMenuItemConstants::KEY_LOGOUT;
    }
}
