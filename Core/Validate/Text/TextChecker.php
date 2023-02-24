<?php
/**
 * SAM-8893: Relocate pure functions from GeneralValidator to \Sam\Core namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Validate\Text;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TextChecker
 * @package Sam\Core\Validate\Text
 */
class TextChecker extends CustomizableClass
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
     * @param string $string
     * @param string $string_encoding
     * @return bool
     */
    public function checkEncoding(string $string, string $string_encoding): bool
    {
        $fs = $string_encoding === 'UTF-8' ? 'UTF-32' : $string_encoding;
        $ts = $string_encoding === 'UTF-32' ? 'UTF-8' : $string_encoding;
        return $string === mb_convert_encoding(mb_convert_encoding($string, $fs, $ts), $ts, $fs);
    }

    /**
     * Returns whether or not $string is a valid utf8
     * @param string|null $string the date to test, null leads to true result
     * @param string $encoding
     * @return bool the result
     */
    public function hasValidEncoding(?string $string, string $encoding): bool
    {
        if (!$string) {
            return true;
        }
        //http://www.php.net/manual/en/function.mb-check-encoding.php#89286
        $test1 = (true === mb_check_encoding($string, $encoding));
        $test2 = (true === $this->checkEncoding($string, $encoding));
        return ($test1 && $test2);
    }

    /**
     * Returns whether or not $string is alphanumeric
     * @param string $string to test
     * @return bool the result
     */
    public function isAlphaNumeric(string $string): bool
    {
        return (bool)preg_match('/^[A-Za-z0-9]+\z/', $string);
    }

    /**
     * Returns whether or not $string contains only letters
     * @param string $string to test
     * @return bool the result
     */
    public function isAlpha(string $string): bool
    {
        return (bool)preg_match('/^[A-Za-z]+\z/', $string);
    }

    /**
     * Returns whether or not lot title has blacklisted phrase
     *
     * @param string $text (lot title)
     * @param string $phraseList (blacklisted phrases from auction or system parameters)
     * @return string comma delimited blacklisted phrases found in the lot title
     */
    public function isInBlacklistPhrase(string $text, string $phraseList): string
    {
        $phraseList = preg_replace('/^\n+|^[\t\s]*\n+/m', '', $phraseList); //Remove empty lines
        $phrases = preg_split('/((\r?\n)|(\r\n?))/', $phraseList); // Split every line
        if (count($phrases) > 0) {
            $pattern = '';
            $separator = '';
            foreach ($phrases as $phrase) {
                $pattern .= $separator . preg_quote($phrase, '/'); // Escape special character
                $separator = '|';
            }
            $pattern = "/{$pattern}/i";
            if (preg_match_all($pattern, $text, $matches)) { // return in order
                return implode(', ', array_filter($matches[0])); // Remove empty string
            }
        }
        return '';
    }
}
