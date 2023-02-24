<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-28, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Transform;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ParsingHelper
 * @package Sam\Installation\Config
 */
class ParsingHelper extends CustomizableClass
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
     * Build array from string, exploding it by quoted string, integer/float number
     * and remove quotes from each quoted string.
     * @param string $value
     * @return array
     */
    public function buildArrayFromString(string $value): array
    {
        $output = [];
        if (!empty($value)) {
            $tokens = token_get_all('<?php ' . $value);
            $output = $this->buildArrayFromTokens($tokens);
        }
        return $output;
    }

    /**
     * Add to output array only tokens, that matches following conditions:
     * token must be an array and has type: T_CONSTANT_ENCAPSED_STRING, T_LNUMBER, T_DNUMBER
     *
     * @param array $tokens array from token_get_all() output.
     * @return array
     */
    public function buildArrayFromTokens(array $tokens): array
    {
        return ArrayFromTokensBuilder::new()->build($tokens);
    }

    /**
     * Counts how many coma chars in tokens.
     * @param array $tokens array from token_get_all() output.
     * @return int
     */
    public function countComaCharInTokens(array $tokens): int
    {
        $count = 0;
        foreach ($tokens as $token) {
            if ($token === ',') {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Counts how many unquoted strings in tokens.
     * @param array $tokens array from token_get_all() output.
     * @return int
     */
    public function countUnquotedStringsInTokens(array $tokens): int
    {
        $count = 0;
        foreach ($tokens as $token) {
            if (
                is_array($token)
                && $token[0] === T_STRING
            ) {
                $count++;
            }
        }
        return $count;
    }
}
