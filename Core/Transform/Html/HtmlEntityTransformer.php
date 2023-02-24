<?php
/**
 * SAM-6427: Extract logic of TextTransformer::toEntity() to EntityHtmlTransformer class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Html;

use Sam\Core\Service\CustomizableClass;

/**
 * Class HtmlEntityTransformer
 * @package ${NAMESPACE}
 */
class HtmlEntityTransformer extends CustomizableClass
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
     * @param string $input
     * @param bool $isEscDoubleQuote
     * @param bool $isNlToBr
     * @return string
     */
    public function fromHtmlEntity(string $input, bool $isEscDoubleQuote = false, bool $isNlToBr = true): string
    {
        $output = $input;
        $replacementMap = $this->getHtmlEntityReplacementMap($isEscDoubleQuote);
        $output = str_replace(array_keys($replacementMap), $replacementMap, $output);
        if ($isNlToBr) {
            // for older version
            $output = $this->brToNl($output);
        }

        return $output;
    }

    /**
     * @param string $input
     * @param bool $isEscDoubleQuote
     * @param bool $isNlToBr
     * @return string
     */
    public function toHtmlEntity(string $input, bool $isEscDoubleQuote = false, bool $isNlToBr = true): string
    {
        $output = $input;
        $chars = $this->str_splitUnicode($input, 1);
        if (!is_array($chars)) {
            $chars = [$output];
        }

        $output = '';
        $replacementMap = $this->getHtmlEntityReplacementMap($isEscDoubleQuote);
        foreach ($chars as $char) {
            $char = in_array($char, $replacementMap, true)
                ? array_search($char, $replacementMap, true)
                : $char;
            $output .= $char;
        }

        if ($isNlToBr) {
            $output = nl2br($output);
        }

        return $output;
    }

    /**
     * Returns custom HTML entities replacement map
     * See full list of html entities: https://www.freeformatter.com/html-entities.html
     *
     * @param bool $isEscDoubleQuote
     * @return string[]
     */
    private function getHtmlEntityReplacementMap(bool $isEscDoubleQuote): array
    {
        $dblQuot = $isEscDoubleQuote ? '"' : '&quot;';

        $replacementMap = [
            //'&#33;' => '!', //exclamation mark  - NOTE: encoded exclamation mark shows as #33
            //'&#35;' => '#', //number sign - NOTE: no need
            '&#36;' => '$', //dollar sign
            '&#37;' => '%', //percent sign
            '&#39;' => "'", //apostrophe
            '&#40;' => '(', //left parenthesis
            '&#41;' => ')', //right parenthesis
            '&#42;' => '*', //asterisk
            '&#43;' => '+', //plus sign
            '&#44;' => ',', //comma
            '&#45;' => '-', //hyphen
            '&#46;' => '.', //period
            '&#123;' => '{', //left curly brace
            '&#124;' => '|', //vertical bar
            '&#125;' => '}', //right curly brace
            '&#126;' => '~', //tilde

            //ISO 8859-1 Symbols
            '&#161;' => '¡', //inverted exclamation mark
            '&#162;' => '¢', //cent
            '&#163;' => '£', //pound
            '&#164;' => '¤', //currency
            '&#165;' => '¥', //yen
            '&#166;' => '¦', //broken vertical bar
            '&#167;' => '§', //section
            '&#168;' => '¨', //spacing diaeresis
            '&#169;' => '©', //copyright
            '&#170;' => 'ª', //feminine ordinal indicator
            '&#171;' => '«', //angle quotation mark (left)
            '&#172;' => '¬', //negation
            '&#173;' => chr(173), //soft hyphen
            '&#174;' => '®', //registered trademark
            '&#175;' => '¯', //spacing macron
            '&#176;' => '°', //degree
            '&#177;' => '±', //plus-or-minus
            '&#178;' => '²', //superscript 2
            '&#179;' => '³', //superscript 3
            '&#180;' => '´', //spacing acute
            '&#181;' => 'µ', //micro
            '&#182;' => '¶', //paragraph
            '&#183;' => chr(183), //middle dot -- YV, SAM-4445, 29.05.2021: Unable to find it. Does it exists?
            '&#184;' => '¸', //spacing cedilla
            '&#185;' => '¹', //superscript 1
            '&#186;' => 'º', //masculine ordinal indicator
            '&#187;' => '»', //angle quotation mark (right)
            '&#188;' => '¼', //fraction 1/4
            '&#189;' => '½', //fraction 1/2
            '&#190;' => '¾', //fraction 3/4
            '&#191;' => '¿', //inverted question mark
            '&#215;' => '×', //multiplication
            '&#247;' => '÷', //division

            //ISO 8859-1 Characters
            '&#192;' => 'À', //capital a, grave accent
            '&#193;' => 'Á', //capital a, acute accent
            '&#194;' => 'Â', //capital a, circumflex accent
            '&#195;' => 'Ã', //capital a, tilde
            '&#196;' => 'Ä', //capital a, umlaut mark
            '&#197;' => 'Å', //capital a, ring
            '&#198;' => 'Æ', //capital ae
            '&#199;' => 'Ç', //capital c, cedilla
            '&#200;' => 'È', //capital e, grave accent
            '&#201;' => 'É', //capital e, acute accent
            '&#202;' => 'Ê', //capital e, circumflex accent
            '&#203;' => 'Ë', //capital e, umlaut mark
            '&#204;' => 'Ì', //capital i, grave accent
            '&#205;' => 'Í', //capital i, acute accent
            '&#206;' => 'Î', //capital i, circumflex accent
            '&#207;' => 'Ï', //capital i, umlaut mark
            '&#208;' => 'Ð', //capital eth, Icelandic
            '&#209;' => 'Ñ', //capital n, tilde
            '&#210;' => 'Ò', //capital o, grave accent
            '&#211;' => 'Ó', //capital o, acute accent
            '&#212;' => 'Ô', //capital o, circumflex accent
            '&#213;' => 'Õ', //capital o, tilde
            '&#214;' => 'Ö', //capital o, umlaut mark
            '&#216;' => 'Ø', //capital o, slash
            '&#217;' => 'Ù', //capital u, grave accent
            '&#218;' => 'Ú', //capital u, acute accent
            '&#219;' => 'Û', //capital u, circumflex accent
            '&#220;' => 'Ü', //capital u, umlaut mark
            '&#221;' => 'Ý', //capital y, acute accent
            '&#222;' => 'Þ', //capital THORN, Icelandic
            '&#223;' => 'ß', //small sharp s, German
            '&#224;' => 'à', //small a, grave accent
            '&#225;' => 'á', //small a, acute accent
            '&#226;' => 'â', //small a, circumflex accent
            '&#227;' => 'ã', //small a, tilde
            '&#228;' => 'ä', //small a, umlaut mark
            '&#229;' => 'å', //small a, ring
            '&#230;' => 'æ', //small ae
            '&#231;' => 'ç', //small c, cedilla
            '&#232;' => 'è', //small e, grave accent
            '&#233;' => 'é', //small e, acute accent
            '&#234;' => 'ê', //small e, circumflex accent
            '&#235;' => 'ë', //small e, umlaut mark
            '&#236;' => 'ì', //small i, grave accent
            '&#237;' => 'í', //small i, acute accent
            '&#238;' => 'î', //small i, circumflex accent
            '&#239;' => 'ï', //small i, umlaut mark
            '&#240;' => 'ð', //small eth, Icelandic
            '&#241;' => 'ñ', //small n, tilde
            '&#242;' => 'ò', //small o, grave accent
            '&#243;' => 'ó', //small o, acute accent
            '&#244;' => 'ô', //small o, circumflex accent
            '&#245;' => 'õ', //small o, tilde
            '&#246;' => 'ö', //small o, umlaut mark
            '&#248;' => 'ø', //small o, slash
            '&#249;' => 'ù', //small u, grave accent
            '&#250;' => 'ú', //small u, acute accent
            '&#251;' => 'û', //small u, circumflex accent
            '&#252;' => 'ü', //small u, umlaut mark
            '&#253;' => 'ý', //small y, acute accent
            '&#254;' => 'þ', //small thorn, Icelandic
            '&#255;' => 'ÿ', //small y, umlaut mark

            '&#352;' => 'Š', //capital S with caron
            '&#353;' => 'š', //small s with caron
            '&#376;' => 'Ÿ', //capital Y with diaeres

            // YV, SAM-4445, 29.05.2021: I see codepoint discrepancy at
            // chr(<codepoint>) => '&#<codepoint> for below code'
            '&#381;' => chr(193), //capital Z with caron -- YV, SAM-4445, 29.05.2021: Unable to find it. Does it exists?
            '&#382;' => chr(193), //small z with caron -- YV, SAM-4445, 29.05.2021: Unable to find it. Does it exists?

            '&#8216;' => '‘', //left single quotation mark
            '&#8217;' => '’', //right single quotation mark
            '&#8218;' => '‚', //single low-9 quotation mark
            '&#8220;' => '“', //left double quotation mark
            '&#8221;' => '”', //right double quotation mark
            '&#8222;' => '„', //double low-9 quotation mark

            '&#8224;' => '†', //dagger
            '&#8225;' => '‡', //double dagger
            '&#8226;' => '•', //bullet
            '&#8230;' => '…', //horizontal ellipsis
            '&#8240;' => '‰', //per mille
            '&#8249;' => '‹', //single left angle quotation
            '&#8250;' => '›', //single right angle quotation

            '&#402;' => 'ƒ',   // small letter F with hook
            '&#710;' => 'ˆ',   // modifier letter circumflex acccent
            '&#338;' => 'Œ',   // capital ligature OE
            '&#8211;' => '–',  // en dash
            '&#8212;' => '—', // em dash
            '&#732;' => '˜',   // small tilde
            '&#153;' => chr(153),   // trade mark sign -- YV, SAM-4445, 29.05.2021: Unable to find it. Does it exists?
            '&#339;' => 'œ',   // small ligature OE

            '&#92;' => '\\',
            $dblQuot => '"', //quotation mark

            //'&#34;' => '"', //quotation mark
            //'\"' => '"', //quotation mark
            //'&#38;' => '&', //ampersand
            //'&#47;' => '/', //slash
            //'&#60;' => '<', //less-than
            //'&#62;' => '>', //greater-than
        ];

        return $replacementMap;
    }

    /**
     * Properly split input string with UTF-8 encoding characters to character array
     * See: https://www.php.net/manual/ru/function.str-split.php#107658
     *
     * @param string $input
     * @param int $length
     * @return array|false|string[]
     */
    private function str_splitUnicode(string $input, int $length = 0)
    {
        if ($length > 0) {
            $output = [];
            $len = mb_strlen($input, "UTF-8");
            for ($i = 0; $i < $len; $i += $length) {
                $output[] = mb_substr($input, $i, $length, "UTF-8");
            }
            return $output;
        }

        return preg_split("//u", $input, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param string $input
     * @return string
     */
    private function brToNl(string $input): string
    {
        return str_replace(['<br>', '<br />', '<br/>'], "\n", $input);
    }
}
