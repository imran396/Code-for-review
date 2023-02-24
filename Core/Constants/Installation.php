<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/05/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

use Sam\Core\Constants;

/**
 * Class InstallationConfig
 * @package Sam\Core\Constants
 * @author: Yura Vakulenko
 */
class Installation
{
    // default config name
    public const DEFAULT_CONFIG = 'core';

    /**
     * available config names
     * @var string[]
     */
    public const AVAILABLE_CONFIG_NAMES = ['core', 'csv', 'breadcrumb', 'wavebid', 'cssLinks', 'jsScripts', 'runtime'];

    /*
     * Delimiter using for glue keys in one dimension config arrays
     * - helps search in code by the same access pattern, eg. core->portal->enabled
     * - should be complex enough to prevent false positive match for options from third-party libraries,
     * eg. in core->vendor
     */
    public const DELIMITER_META_OPTION_KEY = '->';
    // Option path delimiter that should be rendered for user
    public const DELIMITER_RENDER_OPTION_KEY = '->';
    // Delimiter used for option keys in logic and for request data keys
    public const DELIMITER_GENERAL_OPTION_KEY = '__';
    // Delimiter for url hash
    public const DELIMITER_HASH_OPTION_KEY = '-';

    // maximum items in set for using self::CTRL_RADIOBUTTON control elements for known set items.
    public const KNOWN_SET_CTRL_RADIOBUTTON_MAX_ITEMS = 6;

    public const MULTI_DELETE_POST_KEY = 'optionMultiDelete';
    public const MULTI_DELETE_INPUT_ID = 'hiddenInput' . self::MULTI_DELETE_POST_KEY;

    // Url hash templates
    public const URL_HASH_FOR_OPTION_KEY = 'option-%s';
    public const URL_HASH_FOR_LOCAL_CONFIG_AREAS = 'lconf-%s';
    public const URL_HASH_FOR_MISSED_LOCAL_CONFIG_AREAS = 'lconf-missed-%s';
    public const URL_HASH_FOR_NAVIGATION_MENU = '%s-options';

    public const LOCAL_CONFIG_LIST_TITLE = '%s (%d)';

    //Data type
    public const T_STRUCT_ARRAY = 'structArray';
    public const T_DEFAULT = Constants\Type::T_STRING;

    // input control types for web interface form.
    public const ECOM_CHOICE = 'choice'; // for edit component with both 'select' and 'radiobutton'
    public const ECOM_DEFAULT = self::ECOM_LINE;
    public const ECOM_DEFAULT_FOR_ARRAYS = self::ECOM_MULTILINE;
    public const ECOM_LINE = 'line'; // valid data types: string, integer, float
    public const ECOM_MULTILINE = 'multiline';
    public const ECOM_STRUCT_MULTILINE = 'structMultiline';
    public const ECOM_MULTISELECT = 'multiselect';
    public const ECOM_RADIO = 'radio';
    public const ECOM_SELECT = 'select';
    /**
     * valid input control types for web interface form.
     * @var string[]
     */
    public const AVAILABLE_EDIT_COMPONENTS = [
        self::ECOM_CHOICE,
        self::ECOM_LINE,
        self::ECOM_MULTILINE,
        self::ECOM_MULTISELECT,
        self::ECOM_RADIO,
        self::ECOM_SELECT,
        self::ECOM_STRUCT_MULTILINE,
    ];
    /**
     * Valid input control types for web interface form
     * if we need to render installation config option that contains value of array type
     * @see \Sam\Core\Constants\Installation::META_ATTR_TYPE === \Sam\Core\Constants\Type::T_ARRAY;
     * @see \Sam\Core\Constants\Admin\InstallationSettingEditConstants::ECOM_BUILD_TYPE_ARRAY
     */
    public const AVAILABLE_EDIT_COMPONENTS_FOR_ARRAYS = [
        self::ECOM_LINE,
        self::ECOM_MULTILINE,
    ];

    public const ECOM_SELECT_TYPE_SINGLE = 'single';
    public const ECOM_SELECT_TYPE_MULTIPLE = 'multiple';
    /**
     * Acceptable types for HTML <select> tag.
     * @var string[]
     */
    public const AVAILABLE_ECOM_SELECT_TYPES = [
        self::ECOM_SELECT_TYPE_SINGLE,
        self::ECOM_SELECT_TYPE_MULTIPLE
    ];

    public const ECOM_BUILDER_TYPE_HTML = 'html';
    public const ECOM_BUILDER_TYPE_ID = 'id';
    /** @var string[] */
    public const EDIT_COMPONENT_DATA_BUILD_TYPES = [
        Constants\Installation::ECOM_BUILDER_TYPE_HTML,
        Constants\Installation::ECOM_BUILDER_TYPE_ID
    ];

    // Meta file attributes
    public const META_ATTR_DESCRIPTION = 'description';
    public const META_ATTR_EDITABLE = 'editable';
    public const META_ATTR_DELETABLE = 'deletable';
    public const META_ATTR_EDIT_COMPONENT = 'editComponent';
    public const META_ATTR_KNOWN_SET = 'knownSet';
    public const META_ATTR_KNOWN_SET_NAMES = 'knownSetNames';
    public const META_ATTR_TYPE = 'type';
    public const META_ATTR_CONSTRAINTS = 'constraints';
    public const META_ATTR_VISIBLE = 'visible';
    /**
     * Available meta attributes
     * @var string[]
     */
    public const AVAILABLE_META_ATTRIBUTES = [
        self::META_ATTR_CONSTRAINTS,
        self::META_ATTR_DELETABLE,
        self::META_ATTR_DESCRIPTION,
        self::META_ATTR_EDITABLE,
        self::META_ATTR_EDIT_COMPONENT,
        self::META_ATTR_KNOWN_SET,
        self::META_ATTR_KNOWN_SET_NAMES,
        self::META_ATTR_TYPE,
        self::META_ATTR_VISIBLE,
    ];


    // Validation constraints
    public const C_FLOAT = 'float';
    public const C_FLOAT_POSITIVE = 'floatPositive';
    public const C_FLOAT_POSITIVE_OR_ZERO = 'floatPositiveOrZero';
    public const C_HEX_STRING = 'hexString';
    public const C_OCT_STRING = 'octString';
    public const C_INT = 'int';
    public const C_INT_POSITIVE = 'intPositive';
    public const C_INT_POSITIVE_OR_ZERO = 'intPositiveOrZero';
    public const C_IS_ARRAY = 'isArray'; // array - is a PHP keyword and can be used as function name only for PHP 7
    public const C_KNOWN_SET = 'knownSet';
    public const C_MAX_LENGTH = 'maxLength';
    public const C_MIN_LENGTH = 'minLength';
    public const C_NULL = 'null';
    public const C_RANGE = 'range';
    public const C_REG_EXP = 'regExp';
    public const C_REQUIRED = 'required';
    public const C_STRING = 'string';
    public const C_SUBNET = 'subnet';
    public const C_SUBSTRING_DELIMITER_COMA = 'substringDelimiterComa';
    public const C_SUBSTRING_QUOTED = 'substringQuoted';
    public const C_BOOL = 'boolean';
    public const C_SPECIAL_COMPLEX = 'specialComplex'; // Custom constraints for several option values check
    /**
     * Constrains with arguments
     * @var string[]
     */
    public const WITH_ARGUMENT_CONSTRAINTS = [
        self::C_KNOWN_SET,
        self::C_MAX_LENGTH,
        self::C_MIN_LENGTH,
        self::C_RANGE,
        self::C_REG_EXP,
        self::C_SPECIAL_COMPLEX,
    ];
    /**
     * Additional constrains for data with type array
     * @var string[]
     */
    public const ADDITIONAL_CONSTRAINTS_FOR_ARRAY_TYPE = [
        self::C_SUBSTRING_QUOTED,
        self::C_SUBSTRING_DELIMITER_COMA,
    ];
    /**
     * Constrains are located according to their checking priority.
     * @var string[]
     */
    public const CONSTRAINTS_BY_CHECKING_PRIORITY = [
        self::C_REQUIRED,
        self::C_SUBSTRING_DELIMITER_COMA,
        self::C_SUBSTRING_QUOTED,
        self::C_IS_ARRAY,
        self::C_FLOAT,
        self::C_FLOAT_POSITIVE,
        self::C_FLOAT_POSITIVE_OR_ZERO,
        self::C_HEX_STRING,
        self::C_OCT_STRING,
        self::C_INT,
        self::C_INT_POSITIVE,
        self::C_INT_POSITIVE_OR_ZERO,
        self::C_BOOL,
        self::C_STRING,
        self::C_NULL,
        self::C_MAX_LENGTH,
        self::C_MIN_LENGTH,
        self::C_KNOWN_SET,
        self::C_RANGE,
        self::C_REG_EXP,
        self::C_SUBNET,
        self::C_SPECIAL_COMPLEX,
    ];

    //Validation statuses
    public const V_STATUS_SUCCESS = 2;
    public const V_STATUS_FAIL = 1;
    public const V_STATUS_DEFAULT = 0;
}
