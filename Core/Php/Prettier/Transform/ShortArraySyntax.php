<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июнь 30, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Php\Prettier\Transform;

/**
 * Class ShortArraySyntax
 * @package Sam\Core\Php\Prettier\Transform
 */
class ShortArraySyntax
{
    /**
     * Replace old (long) array syntax to modern (short) array syntax.
     * replace all 'array (' to '[', all '),' to '],' and all ');' to '];'
     * @param string $input
     * @return string
     */
    public function transform(string $input): string
    {
        $patterns = [
            '/^(\s*)array\s*\((\s*)$/m',
            '/^(\s*)\)$/m',
            '/^(\s*)\),(\s*)$/m',
            '/^(\s*)\)\;(\s*)$/m',
        ];
        $replacements = [
            '$1[$2',
            '$1]',
            '$1],$2',
            '$1];$2',
        ];
        $output = preg_replace($patterns, $replacements, $input);
        return $output;
    }
}
