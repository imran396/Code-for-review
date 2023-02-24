<?php

namespace Sam\Core\Constants;

/**
 * Class Mysql
 * @package Sam\Core\Constants
 */
class Mysql
{
    public const CH_BIG5 = 'big5';
    public const CH_DEC8 = 'dec8';
    public const CH_CP850 = 'cp850';
    public const CH_HP8 = 'hp8';
    public const CH_KOI8R = 'koi8r';
    public const CH_LATIN1 = 'latin1';
    public const CH_LATIN2 = 'latin2';
    public const CH_SWE7 = 'swe7';
    public const CH_ASCII = 'ascii';
    public const CH_UJIS = 'ujis';
    public const CH_SJIS = 'sjis';
    public const CH_HEBREW = 'hebrew';
    public const CH_TIS620 = 'tis620';
    public const CH_EUCKR = 'euckr';
    public const CH_KOI8U = 'koi8u';
    public const CH_GB2312 = 'gb2312';
    public const CH_GREEK = 'greek';
    public const CH_CP1250 = 'cp1250';
    public const CH_GBK = 'gbk';
    public const CH_LATIN5 = 'latin5';
    public const CH_ARMSCII8 = 'armscii8';
    public const CH_UTF8 = 'utf8';
    public const CH_UCS2 = 'ucs2';
    public const CH_CP866 = 'cp866';
    public const CH_KEYBCS2 = 'keybcs2';
    public const CH_MACCE = 'macce';
    public const CH_MACROMAN = 'macroman';
    public const CH_CP852 = 'cp852';
    public const CH_LATIN7 = 'latin7';
    public const CH_UTF8MB4 = 'utf8mb4';
    public const CH_CP1251 = 'cp1251';
    public const CH_UTF16 = 'utf16';
    public const CH_UTF16LE = 'utf16le';
    public const CH_CP1256 = 'cp1256';
    public const CH_CP1257 = 'cp1257';
    public const CH_UTF32 = 'utf32';
    public const CH_BINARY = 'binary';
    public const CH_GEOSTD8 = 'geostd8';
    public const CH_CP932 = 'cp932';
    public const CH_EUCJPMS = 'eucjpms';

    /**
     * @var string[]
     */
    public static array $charsets = [
        self::CH_BIG5,
        self::CH_DEC8,
        self::CH_CP850,
        self::CH_HP8,
        self::CH_KOI8R,
        self::CH_LATIN1,
        self::CH_LATIN2,
        self::CH_SWE7,
        self::CH_ASCII,
        self::CH_UJIS,
        self::CH_SJIS,
        self::CH_HEBREW,
        self::CH_TIS620,
        self::CH_EUCKR,
        self::CH_KOI8U,
        self::CH_GB2312,
        self::CH_GREEK,
        self::CH_CP1250,
        self::CH_GBK,
        self::CH_LATIN5,
        self::CH_ARMSCII8,
        self::CH_UTF8,
        self::CH_UCS2,
        self::CH_CP866,
        self::CH_KEYBCS2,
        self::CH_MACCE,
        self::CH_MACROMAN,
        self::CH_CP852,
        self::CH_LATIN7,
        self::CH_UTF8MB4,
        self::CH_CP1251,
        self::CH_UTF16,
        self::CH_UTF16LE,
        self::CH_CP1256,
        self::CH_CP1257,
        self::CH_UTF32,
        self::CH_BINARY,
        self::CH_GEOSTD8,
        self::CH_CP932,
        self::CH_EUCJPMS,
    ];
    /**
     * @var string[]
     */
    public static array $charsetsNames = [
        self::CH_BIG5 => 'Big5 Traditional Chinese',
        self::CH_DEC8 => 'DEC West European',
        self::CH_CP850 => 'DOS West European',
        self::CH_HP8 => 'HP West European',
        self::CH_KOI8R => 'KOI8-R Relcom Russian',
        self::CH_LATIN1 => 'cp1252 West European',
        self::CH_LATIN2 => 'ISO 8859-2 Central European',
        self::CH_SWE7 => '7bit Swedish',
        self::CH_ASCII => 'US ASCII',
        self::CH_UJIS => 'EUC-JP Japanese',
        self::CH_SJIS => 'Shift-JIS Japanese',
        self::CH_HEBREW => 'ISO 8859-8 Hebrew',
        self::CH_TIS620 => 'TIS620 Thai',
        self::CH_EUCKR => 'EUC-KR Korean',
        self::CH_KOI8U => 'KOI8-U Ukrainian',
        self::CH_GB2312 => 'GB2312 Simplified Chinese',
        self::CH_GREEK => 'ISO 8859-7 Greek',
        self::CH_CP1250 => 'Windows Central European',
        self::CH_GBK => 'GBK Simplified Chinese',
        self::CH_LATIN5 => 'ISO 8859-9 Turkish',
        self::CH_ARMSCII8 => 'ARMSCII-8 Armenian',
        self::CH_UTF8 => 'UTF-8 Unicode',
        self::CH_UCS2 => 'UCS-2 Unicode',
        self::CH_CP866 => 'DOS Russian',
        self::CH_KEYBCS2 => 'DOS Kamenicky Czech-Slovak',
        self::CH_MACCE => 'Mac Central European',
        self::CH_MACROMAN => 'Mac West European',
        self::CH_CP852 => 'DOS Central European',
        self::CH_LATIN7 => 'ISO 8859-13 Baltic',
        self::CH_UTF8MB4 => 'UTF-8 Unicode',
        self::CH_CP1251 => 'Windows Cyrillic',
        self::CH_UTF16 => 'UTF-16 Unicode',
        self::CH_UTF16LE => 'UTF-16LE Unicode',
        self::CH_CP1256 => 'Windows Arabic',
        self::CH_CP1257 => 'Windows Baltic',
        self::CH_UTF32 => 'UTF-32 Unicode',
        self::CH_BINARY => 'Binary pseudo charset',
        self::CH_GEOSTD8 => 'GEOSTD8 Georgian',
        self::CH_CP932 => 'SJIS for Windows Japanese',
        self::CH_EUCJPMS => 'UJIS for Windows Japanese',
    ];
}
