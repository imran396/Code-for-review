<?php
/**
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\Internal;

use Sam\Core\Service\CustomizableClass;

/**
 * Contains methods for working with class name and namespace
 *
 * Class Helper
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal\Generate
 * @internal
 */
class ClassGeneratorHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Extract namespace from the fully qualified class name
     *
     * @param string $name
     * @return string
     */
    public function extractNamespace(string $name): string
    {
        return ($pos = strrpos($name, '\\')) ? substr($name, 0, $pos) : '';
    }

    /**
     * Extract class name from the fully qualified class name
     *
     * @param string $name
     * @return string
     */
    public function extractClassName(string $name): string
    {
        return ($pos = strrpos($name, '\\')) === false
            ? $name
            : substr($name, $pos + 1);
    }
}
