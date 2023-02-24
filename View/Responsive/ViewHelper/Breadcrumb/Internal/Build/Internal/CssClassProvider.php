<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal;

use Sam\Core\Service\CustomizableClass;

/**
 * Class CssClassProvider
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb\Build\PathSettingsBuild\Internal
 */
class CssClassProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generates css class name
     * @param string $string
     * @return string
     */
    public function get(string $string): string
    {
        return preg_replace('/\W+/', '', strtolower(strip_tags($string)));
    }
}
