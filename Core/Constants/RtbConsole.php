<?php
/**
 * General rendering constants for Rtb Console controls
 * SAM-5201: Apply constants for rtbd request/response keys and data: Applied constants
 */

namespace Sam\Core\Constants;

/**
 * Class Rtb
 * @package Sam\Core\Constants
 */
class RtbConsole
{
    // Control Types
    public const CT_BUTTON = 'button';
    public const CT_CHECKBOX = 'checkbox';
    public const CT_DIV = 'div';
    public const CT_HIDDEN = 'hidden';
    public const CT_IFRAME = 'iframe';
    public const CT_IMG = 'img';
    public const CT_LINK = 'link';
    public const CT_RADIO = 'radio';
    public const CT_SCRIPT = 'script';
    public const CT_SELECT = 'select';
    public const CT_SPAN = 'span';
    public const CT_TABLE = 'table';
    public const CT_TEXT = 'text';
    public const CT_TEXTAREA = 'textarea';
    public const CT_UL = 'ul';

    /** @var string[] */
    public const CONTROL_TYPES = [
        self::CT_BUTTON,
        self::CT_CHECKBOX,
        self::CT_DIV,
        self::CT_HIDDEN,
        self::CT_IFRAME,
        self::CT_IMG,
        self::CT_LINK,
        self::CT_RADIO,
        self::CT_SCRIPT,
        self::CT_SELECT,
        self::CT_SPAN,
        self::CT_TABLE,
        self::CT_TEXT,
        self::CT_TEXTAREA,
        self::CT_UL,
    ];
}
