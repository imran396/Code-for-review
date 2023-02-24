<?php
/**
 * SAM-9416: Decouple logic of AdvancedSearch class for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\ViewMode\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class ViewModeChecker
 * @package Sam\View\Responsive\Form\AdvancedSearch
 */
class ViewModeChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isCompact(string $viewMode): bool
    {
        return $viewMode === Constants\Page::VM_COMPACT;
    }

    public function isGrid(string $viewMode): bool
    {
        return $viewMode === Constants\Page::VM_GRID;
    }

    public function isList(string $viewMode): bool
    {
        return $viewMode === Constants\Page::VM_LIST;
    }

    public function isListOrGrid(string $viewMode): bool
    {
        return $this->isGrid($viewMode) || $this->isList($viewMode);
    }
}
