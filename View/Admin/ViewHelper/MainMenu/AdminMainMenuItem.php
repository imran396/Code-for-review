<?php
/**
 * SAM-7717: Refactor admin menu tabs rendering module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\MainMenu;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AdminMainMenuItem
 * @package Sam\View\Admin\ViewHelper\MainMenu
 */
class AdminMainMenuItem extends CustomizableClass
{
    public string $id;
    public string $name;
    public string $title;
    public string $url;
    public bool $isCurrent;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $title
     * @param string $url
     * @param bool $isCurrent
     * @return $this
     */
    public function construct(string $id, string $name, string $title, string $url, bool $isCurrent = false): static
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->url = $url;
        $this->isCurrent = $isCurrent;
        return $this;
    }
}
