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

namespace Sam\View\Responsive\Form\AdvancedSearch\ViewMode\Url;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Core\Constants;

/**
 * Class ViewModeUrlBuilder
 * @package
 */
class ViewModeUrlBuilder extends CustomizableClass
{
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function buildCurrentUrlForGrid(): string
    {
        return $this->buildCurrentUrlForViewMode(Constants\Page::VM_GRID);
    }

    public function buildCurrentUrlForList(): string
    {
        return $this->buildCurrentUrlForViewMode(Constants\Page::VM_LIST);
    }

    public function buildCurrentUrlForCompact(): string
    {
        return $this->buildCurrentUrlForViewMode(Constants\Page::VM_COMPACT);
    }

    protected function buildCurrentUrlForViewMode(string $viewMode): string
    {
        return UrlParser::new()->replaceParams(
            $this->getServerRequestReader()->currentUrl(),
            ['view' => $viewMode]
        );
    }

}
