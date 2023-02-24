<?php

namespace Sam\Core\Constants;

/**
 * Class Image
 * @package Sam\Core\Constants
 */
class Image
{
    public const INVALID_IMAGE_URL = 1;
    public const FAILED_TO_FETCH = 2;
    public const INVALID_CONTENT_TYPE = 3;
    public const INVALID_HTTP_RESPONSE_CODE = 4;
    public const INVALID_IMAGE_SIZE = 5;

    public const IMAGE_GIF = 'GIF';
    public const IMAGE_JPEG = 'JPEG';
    public const IMAGE_PNG = 'PNG';

    public const SMALL = 's';
    public const MEDIUM = 'm';
    public const LARGE = 'l';
    public const EXTRA_LARGE = 'xl';

    public const AUCTION_IMAGE = 'auction';
    public const LOT_ITEM_IMAGE = 'lot';
    public const SETTINGS_IMAGE = 'settings';
    public const SETTLEMENT_IMAGE = 'settlement';
    public const INVOICE_IMAGE = 'invoice';
    public const ACCOUNT_IMAGE = 'account';
    public const LOCATION_LOGO_IMAGE = 'location-logo';

    // Link Prefix default key
    public const LP_DEFAULT = 'default';

    /**
     * @var string[]
     */
    public static array $imageTypes = [
        self::AUCTION_IMAGE,
        self::LOT_ITEM_IMAGE,
        self::SETTINGS_IMAGE,
        self::SETTLEMENT_IMAGE,
        self::INVOICE_IMAGE,
        self::ACCOUNT_IMAGE,
        self::LOCATION_LOGO_IMAGE,
    ];

    public const CID_ICO_FLA_IMAGE_UPLOAD_TPL = 'btnrimgup%s_icon';
    public const CID_DLG_FILE_ASSET_TPL = 'dlgFileAsset_%s';
    /** @var string[] */
    public static array $sizes = [self::SMALL, self::MEDIUM, self::LARGE, self::EXTRA_LARGE];

    // Logo image original source file names
    public const HEADER_LOGO_ORIGINAL_FILE_NAME = 'header_logo.jpg';
    public const INVOICE_LOGO_ORIGINAL_FILE_NAME = 'invoice_logo.jpg';
    public const SETTLEMENT_LOGO_ORIGINAL_FILE_NAME = 'settlement_logo.jpg';
    public const EMPTY_STUB_ORIGINAL_FILE_NAME = 'default_image.jpg';
    public const COMING_SOON_STUB_ORIGINAL_FILE_NAME = 'coming_soon_default_image.jpg';
    public const ACCOUNT_LOGO_ORIGINAL_FILE_NAME = 'account.jpg';
}
