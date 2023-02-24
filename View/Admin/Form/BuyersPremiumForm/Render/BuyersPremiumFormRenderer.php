<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyersPremiumFormRenderer
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Render
 */
class BuyersPremiumFormRenderer extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function makeDropDownLine(string $name, string $shortName): string
    {
        return sprintf("%s(%s)", $name, $shortName);
    }
}
