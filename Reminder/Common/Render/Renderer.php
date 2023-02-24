<?php
/**
 * SAM-9734 : Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Common\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class Renderer
 * @package
 */
class Renderer extends CustomizableClass
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
     * @param string $templateName
     * @return string
     * #[Pure]
     */
    public function makeNameFromEmailKey(string $templateName): string
    {
        if (!$templateName) {
            return '';
        }

        $names = explode('_', $templateName);
        $name = strtolower($names[0]);
        return $name;
    }
}
