<?php
/**
 * SAM-5708: Local configuration management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Transform;

use Sam\Core\Service\CustomizableClass;

/**
 * This class contain methods for building PHP array from an array of tokens
 *
 * Class ArrayFromTokensBuilder
 * @package Sam\Installation\Config
 */
class ArrayFromTokensBuilder extends CustomizableClass
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
     * Add to output array only tokens, that matches following conditions:
     * token must be an array and has type: T_CONSTANT_ENCAPSED_STRING, T_LNUMBER, T_DNUMBER
     *
     * @param array $tokens array from token_get_all() output.
     * @return array
     */
    public function build(array $tokens): array
    {
        $output = [];

        while (($token = $this->getNextApplicableToken($tokens)) !== null) {
            $token = current($tokens);
            if (is_array($token)) {
                if ($token[0] === T_DOUBLE_ARROW) {
                    $key = array_pop($output);
                    $valueToken = $this->getNextApplicableToken($tokens);
                    $output[$key] = $this->getTokenValue($valueToken);
                } else {
                    $output[] = $this->getTokenValue($token);
                }
            }
        }
        return $output;
    }

    /**
     * @param array $token
     * @return float|int|string|null
     */
    private function getTokenValue(array $token): float|int|string|null
    {
        if ($token[0] === T_CONSTANT_ENCAPSED_STRING) {
            $output = $this->stripQuotes($token[1]);
            $output = preg_replace(['/\\\\\'/'], ['\''], $output);
            return $output;
        }
        if ($token[0] === T_LNUMBER) {
            return (int)$token[1];
        }
        if ($token[0] === T_DNUMBER) {
            return (double)$token[1];
        }

        return null;
    }

    /**
     * @param array $tokens
     * @return mixed
     */
    private function getNextApplicableToken(array &$tokens): mixed
    {
        do {
            $currentToken = next($tokens);
        } while ($currentToken !== false && !$this->isApplicableToken($currentToken));

        return $currentToken ?: null;
    }

    /**
     * @param $token
     * @return bool
     */
    private function isApplicableToken($token): bool
    {
        $applicableTypes = [T_CONSTANT_ENCAPSED_STRING, T_LNUMBER, T_DNUMBER, T_DOUBLE_ARROW];
        return is_array($token) && in_array($token[0], $applicableTypes, true);
    }

    /**
     * Remove first and last quote characters from a quoted string
     *
     * @param string $input
     * @return string
     */
    private function stripQuotes(string $input): string
    {
        if (mb_strlen($input) > 1) {
            $validQuotes = ['"', "'"];
            $firstChar = $input[0];
            $lastChar = $input[mb_strlen($input) - 1];
            if (
                $firstChar === $lastChar
                && in_array($firstChar, $validQuotes, true)
            ) {
                return mb_substr($input, 1, -1);
            }
        }
        return $input;
    }
}
