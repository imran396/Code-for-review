<?php
/**
 * SAM-6260: PDF catalog configuration option for Lot name, lot description, font size
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-06, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants;


/**
 * Class Pdf
 * @package Sam\Core\Constants
 */
class Pdf
{
    public const FONT_ARIAL = 'arial';
    public const FONT_COURIER = 'courier';
    public const FONT_HELVETICA = 'helvetica';
    public const FONT_TIMES = 'times';
    public const FONT_SYMBOL = 'symbol';
    public const FONT_ZAPFD = 'zapfd';
    public const FONT_DEFAULT = self::FONT_ARIAL;

    /**
     * Available fonts for PDF documents.
     * @var string[]
     */
    public static array $fonts = [
        self::FONT_ARIAL,
        self::FONT_COURIER,
        self::FONT_HELVETICA,
        self::FONT_SYMBOL,
        self::FONT_TIMES,
        self::FONT_ZAPFD,
    ];

    public const FONT_STYLE_BOLD = 'bold';
    public const FONT_STYLE_ITALIC = 'italic';
    public const FONT_STYLE_UNDERLINE = 'underline';
    public const FONT_STYLE_NORMAL = 'normal';

    /**
     * @var string[]
     */
    public static array $fontStyles = [
        self::FONT_STYLE_BOLD,
        self::FONT_STYLE_ITALIC,
        self::FONT_STYLE_NORMAL,
        self::FONT_STYLE_UNDERLINE,
    ];
}
