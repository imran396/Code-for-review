<?php
/**
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 * SAM-1020: Front End - Search Page - Keyword Search Improvements
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SearchIndex\Helper;


use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class SearchIndexNormalizationHelper
 * @package Sam\SearchIndex\Helper
 */
class SearchIndexNormalizationHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Extract and return possible lot# and remaining data
     * We search for the next lot# patterns:
     * 1) Prefix + Number + Extension
     * 2) Prefix + Number
     * 3) Number + Extension
     * 4) Number
     * We don't search for patterns:
     * 5) Prefix + Extension
     * 6) Prefix
     * 7) Extension
     *
     * @param string $text
     * @return array                    Keys: 'lot_numbers', 'remain_data'
     */
    public function extractLotNumbers(string $text): array
    {
        $quotedLotPrefixSeparator = preg_quote($this->cfg()->get('core->lot->lotNo->prefixSeparator'), '/');
        $quotedLotExtensionSeparator = preg_quote($this->cfg()->get('core->lot->lotNo->extensionSeparator'), '/');
        $patterns = [
            '/([^a-zA-Z0-9]{1})([a-zA-Z0-9]{1,20}' . $quotedLotPrefixSeparator . '\d+' . $quotedLotExtensionSeparator . '[a-zA-Z0-9]{1,3})([^a-zA-Z0-9]{1})/',
            '/([^a-zA-Z0-9]{1})([a-zA-Z0-9]{1,20}' . $quotedLotPrefixSeparator . '\d+)([^a-zA-Z0-9' . $quotedLotExtensionSeparator . ']{1})/',
            '/([^a-zA-Z0-9' . $quotedLotPrefixSeparator . ']{1})(\d+' . $quotedLotExtensionSeparator . '[a-zA-Z0-9]{1,3})([^a-zA-Z0-9]{1})/',
            '/([^a-zA-Z0-9' . $quotedLotPrefixSeparator . ']{1})(\d+)([^a-zA-Z0-9' . $quotedLotExtensionSeparator . ']{1})/',
        ];
        $lotNumbers = [];
        $text = ' ' . $text . ' ';
        foreach ($patterns as $pattern) {
            while (preg_match_all($pattern, $text, $matches)) {
                $lotNumbers = array_merge($lotNumbers, $matches[2]);
                $text = preg_replace($pattern, '$1$3', $text);
            }
        }
        $lotNumbers = array_unique($lotNumbers);
        $text = trim($text);
        return ['lot_numbers' => $lotNumbers, 'remain_data' => $text];
    }

    /**
     * Extract and return possible item# in array
     *
     * @param string $text
     * @return array
     */
    public function extractItemNumbers(string $text): array
    {
        $regExp = '/(\D{1})(\d+)(\D{1})/';
        $itemNumbers = [];
        $text = ' ' . $text . ' ';
        while (preg_match_all($regExp, $text, $matches)) {
            $itemNumbers = array_merge($itemNumbers, $matches[2]);
            $text = preg_replace($regExp, '$1$3', $text);
        }
        $itemNumbers = array_unique($itemNumbers);
        return $itemNumbers;
    }

    /**
     * Remove repeated words and numbers, and sort them
     *
     * @param string $text
     * @return string
     */
    public function filterToUniqueTokenData(string $text): string
    {
        $tokens = $this->splitToUniqueTokenArray($text);
        sort($tokens);
        return implode(' ', $tokens);
    }

    /**
     * Split input data to array of words and numbers
     *
     * @param string $text
     * @return array
     */
    protected function splitToUniqueTokenArray(string $text): array
    {
        $text = mb_strtolower($text);
        preg_match_all('/[\p{L}\d]+/mu', $text, $matches);
        $matches[0] = array_unique($matches[0]);
        return array_values($matches[0]);
    }

    /**
     * Remove tokens, which length is less than $minLength
     *
     * @param string $text
     * @param int $minLength
     * @return string
     */
    public function filterByLength(string $text, int $minLength): string
    {
        preg_match_all('/\S+/mu', $text, $matches);
        $text = '';
        foreach ($matches[0] as $token) {
            if (mb_strlen($token) >= $minLength) {
                $text .= $token . ' ';
            }
        }
        return trim($text);
    }

    /**
     * Perform some standard filtering: remove tags, lowercase, change html entities to utf-8
     *
     * @param string $text
     * @return string
     */
    public function filter(string $text): string
    {
        $text = $this->filterAccent($text);
        $text = $this->filterTags($text);
        // some data (passed via wysiwyg), were saved with html-entities, like &auml;
        $text = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
        // To be able to search for hyphenated words
        $text = preg_replace('/[_-]/mu', '', $text);
        $text = preg_replace('/[^\p{L}\d.,]/mu', ' ', $text);
        $text = preg_replace('/[,.](\d+)/mu', '$1', $text);   // filter fractional numbers
        $text = preg_replace('/[\s,.]+/mu', ' ', $text);
        $text = mb_strtolower($text);
        if ($this->cfg()->get('core->search->index->lang') !== '') {
            $text = SearchStopWords::new()->filter($text, $this->cfg()->get('core->search->index->lang'));
        }
        $text = preg_replace('/\s+/mu', ' ', $text);
        $text = trim($text);
        return $text;
    }

    /**
     * Replace all starting and closing tags with ' ' space
     *
     * @param string $text
     * @return string
     */
    protected function filterTags(string $text): string
    {
        $text = preg_replace('/<\s*\w.*?>/mu', ' ', $text);                       // match all start tags
        $text = preg_replace('/<\s*\/\s*\w\s*.*?>|<\s*br\s*>/mu', ' ', $text);    // match all close tags
        $text = preg_replace('/\s+/mu', ' ', $text);
        return $text;
    }

    /**
     * Replace letters with accent with normal letters
     *
     * @param string $text
     * @return string
     */
    protected function filterAccent(string $text): string
    {
        // @formatter:off
        $trans = [
            'À' => 'a',
            'Á' => 'a',
            'Â' => 'a',
            'Ä' => 'a',
            'Ã' => 'a',
            'Å' => 'a',
            'Ą' => 'a',
            'Ā' => 'a',
            'Æ' => 'a',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ä' => 'a',
            'ã' => 'a',
            'å' => 'a',
            'æ' => 'a',
            'ā' => 'a',
            'ą' => 'a',
            'È' => 'e',
            'É' => 'e',
            'Ê' => 'e',
            'Ë' => 'e',
            'Ē' => 'e',
            'Ę' => 'e',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ð' => 'e',
            'ē' => 'e',
            'ę' => 'e',
            'Ì' => 'i',
            'Í' => 'i',
            'Î' => 'i',
            'Ï' => 'i',
            'Ī' => 'i',
            'İ' => 'i',
            'Þ' => 'i',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'þ' => 'i',
            'ī' => 'i',
            'Ò' => 'o',
            'Ó' => 'o',
            'Ô' => 'o',
            'Ö' => 'o',
            'Õ' => 'o',
            'Ø' => 'o',
            'Ő' => 'o',
            'Ō' => 'o',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'ö' => 'o',
            'õ' => 'o',
            'ø' => 'o',
            'œ' => 'o',
            'ő' => 'o',
            'ō' => 'o',
            'Ù' => 'u',
            'Ú' => 'u',
            'Û' => 'u',
            'Ü' => 'u',
            'Ŭ' => 'u',
            'Ů' => 'u',
            'Ű' => 'u',
            'Ū' => 'u',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ŭ' => 'u',
            'ů' => 'u',
            'ű' => 'u',
            'ū' => 'u',
            'Ç' => 'c',
            'Ĉ' => 'c',
            'Č' => 'c',
            'ç' => 'c',
            'ĉ' => 'c',
            'č' => 'c',
            'Ĝ' => 'g',
            'ĝ' => 'g',
            'Ɠ' => 'g',
            'ɠ' => 'g',
            'Ğ' => 'g',
            'ğ' => 'g',
            'Ĥ' => 'h',
            'ĥ' => 'h',
            'Ð' => 'd',
            'Ď' => 'd',
            'ď' => 'd',
            'Ĵ' => 'j',
            'ĵ' => 'j',
            'Ñ' => 'n',
            'ñ' => 'n',
            'Ř' => 'r',
            'ř' => 'r',
            'Š' => 's',
            'š' => 's',
            'Ŝ' => 's',
            'ŝ' => 's',
            'Ş' => 's',
            'ş' => 's',
            'Ť' => 't',
            'ť' => 't',
            'Ţ' => 't',
            'ţ' => 't',
            'Ț' => 't',
            'ț' => 't',
            'Ý' => 'y',
            'ý' => 'y',
            'Ỳ' => 'y',
            'ỳ' => 'y',
            'Ÿ' => 'y',
            'ÿ' => 'y',
            'Ŷ' => 'y',
            'ŷ' => 'y',
            'Ŵ' => 'w',
            'Ẅ' => 'w',
            'Ẃ' => 'w',
            'Ẁ' => 'w',
            'ŵ' => 'w',
            'ẅ' => 'w',
            'ẃ' => 'w',
            'ẁ' => 'w',
            'Ž' => 'z',
            'ž' => 'z',
        ];
        // @formatter:on

        return strtr($text, $trans);
    }
}
