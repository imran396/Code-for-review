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

namespace Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AdminMenuItemTranslator
 * @package Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem
 * @internal
 */
class AdminMenuItemTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    private const TRANSLATION_PREFIX = 'main_menu.item';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $section
     * @return string
     */
    public function translateName(string $section): string
    {
        $name = $this->getAdminTranslator()->trans(sprintf('%s.%s.name', self::TRANSLATION_PREFIX, $section));
        return $name;
    }

    /**
     * @param string $section
     * @return string
     */
    public function translateTitle(string $section): string
    {
        $title = $this->getAdminTranslator()->trans(sprintf('%s.%s.title', self::TRANSLATION_PREFIX, $section));
        return $title;
    }
}
