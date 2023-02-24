<?php

namespace Sam\Core\Constants;

/**
 * Class CustomField
 * @package Sam\Core\Constants
 */
class CustomField
{
    public const TYPE_INTEGER = 1;
    public const TYPE_DECIMAL = 2;
    public const TYPE_TEXT = 3;
    public const TYPE_SELECT = 4;
    public const TYPE_DATE = 5;
    public const TYPE_FULLTEXT = 6;
    public const TYPE_FILE = 7;
    public const TYPE_POSTALCODE = 8;
    public const TYPE_LABEL = 9;
    public const TYPE_CHECKBOX = 10;
    public const TYPE_PASSWORD = 11;
    public const TYPE_RICHTEXT = 12;
    public const TYPE_YOUTUBELINK = 13;
    /** @var int[] */
    public static array $numericTypes = [self::TYPE_INTEGER, self::TYPE_DECIMAL, self::TYPE_DATE, self::TYPE_CHECKBOX];
    /** @var string[] */
    public static array $typeNames = [
        self::TYPE_INTEGER => "Integer",
        self::TYPE_DECIMAL => "Decimal",
        self::TYPE_TEXT => "Input line",
        self::TYPE_SELECT => "Dropdown",
        self::TYPE_DATE => "Date/Time",
        self::TYPE_FULLTEXT => "Text area",
        self::TYPE_RICHTEXT => "Rich text",
        self::TYPE_CHECKBOX => "Checkbox",
        self::TYPE_PASSWORD => "Password",
        self::TYPE_FILE => "File",
        self::TYPE_POSTALCODE => "ZIP/Postal code",
        self::TYPE_LABEL => "Label",
        self::TYPE_YOUTUBELINK => "Youtube url",
    ];
    /**
     * Array of auction custom field types, which can be encrypted/decrypted
     * @var int[]
     */
    public static array $encryptedTypes = [
        self::TYPE_TEXT,
        self::TYPE_FULLTEXT,
        self::TYPE_PASSWORD,
    ];
    /**
     * Array of auction custom field types, which are predefined by default value (of Parameters)
     * @var int[]
     */
    public static array $defaultsPredefinedTypes = [
        self::TYPE_INTEGER,
        self::TYPE_CHECKBOX,
        self::TYPE_TEXT,
        self::TYPE_FULLTEXT,
        self::TYPE_RICHTEXT,
        self::TYPE_PASSWORD,
        self::TYPE_FILE,
        self::TYPE_POSTALCODE,
    ];

    public const BARCODE_TYPE_CODE_39 = 1;
    public const BARCODE_TYPE_CODE_128 = 2;
    public const BARCODE_TYPE_EAN_13 = 3;
    public const BARCODE_TYPE_INT_25 = 4;
    public const BARCODE_TYPE_POST_NET = 5;
    public const BARCODE_TYPE_UPCA = 6;

    public const BARCODE_TYPE_DEFAULT = self::BARCODE_TYPE_CODE_39;
    /** @var string[] */
    public static array $barcodeTypeNames = [
        self::BARCODE_TYPE_CODE_39 => 'code39',
        self::BARCODE_TYPE_CODE_128 => 'code128',
        self::BARCODE_TYPE_EAN_13 => 'ean13',
        self::BARCODE_TYPE_INT_25 => 'int25',
        self::BARCODE_TYPE_POST_NET => 'postnet',
        self::BARCODE_TYPE_UPCA => 'upca',
    ];
    /** @var int[] */
    public static array $barcodeNumericTypes = [self::BARCODE_TYPE_EAN_13, self::BARCODE_TYPE_INT_25, self::BARCODE_TYPE_POST_NET, self::BARCODE_TYPE_UPCA];
    /** @var int[] */
    public static array $barcodeAlphanumericTypes = [self::BARCODE_TYPE_CODE_39, self::BARCODE_TYPE_CODE_128];
    /** @var int[] */
    public static array $barcodeLength = [self::BARCODE_TYPE_UPCA => 12];
    /** @var int[] */
    public static array $postalCodeRadiuses = [5, 10, 20, 50, 100, 250];

    public const CHECKBOX_ON_TEXT = 'Yes';
    public const CHECKBOX_OFF_TEXT = 'No';

    /** @var string - Default value for file-type custom field Parameters field */
    public const FILE_PARAMETERS_DEFAULT = 'pdf;3';

    public const AUCTION_CUSTOM_FIELD_TRANSLATION_FILE = 'auctioncustomfields.csv';
    public const LOT_CUSTOM_FIELD_TRANSLATION_FILE = 'customfields.csv';
    public const USER_CUSTOM_FIELD_TRANSLATION_FILE = 'usercustomfields.csv';

    /** @var string[] */
    public static array $masterTranslationFiles = [
        self::AUCTION_CUSTOM_FIELD_TRANSLATION_FILE,
        self::LOT_CUSTOM_FIELD_TRANSLATION_FILE,
        self::USER_CUSTOM_FIELD_TRANSLATION_FILE,
    ];
}
