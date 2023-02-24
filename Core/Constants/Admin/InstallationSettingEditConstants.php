<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июль 18, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InstallationSettingEditConstants
 * @package Sam\Core\Constants\Admin
 */
class InstallationSettingEditConstants
{
    public const HID_CONFIG_NAME = 'configName';
    public const HID_OPTION_KEY = 'optionKey';

    // Component or controls constants
    // Id for component block with form controls.
    public const CID_EB = 'eb_%s'; // general
    public const CID_EB_TXT_TPL = 'eb_txt_%s'; // with 'input' control element
    public const CID_EB_LST_TPL = 'eb_lst_%s'; // with 'select' control element
    public const CID_EB_RAD_TPL = 'eb_rad_%s'; // with 'radiobutton' control element
    public const CID_EB_TEA_TPL = 'eb_tea_%s'; // with 'textarea' control element
    public const CID_EB_STRUCT_TEA_TPL = 'eb_struct_tea_%s'; // for 'textarea' with structured data control element

    // template for rendering titles for 'knownSetNames'
    public const WEB_KNOWNSET_NAMES_TITLE_TPL = '%s (%s)';

    // main column with controls
    public const WEB_MAIN_CTRL_NUM_SUFFIX = '_n%s';

    // CSS class name constants
    public const CSS_TEXTAREA_AUTO_ADJUST_HEIGHT = 'autoAdjustHeight';
    public const CSS_CHKBOX_MULTIDELETE = 'multidelete_checkbox_block';
    public const CSS_EDIT_CONTROL_WRAPPER = 'editControlWrapper';
    public const CSS_CONFIG_OPTION_DEFAULT_VALUE_ANCHOR = 'configOptionDefaultValueAnchor';
    public const CSS_LOCAL_VALUE_ABSENT_IN_GLOBAL_CONFIG = 'notGlobal';
    public const CSS_LOCAL_VALUE_PRESENT_IN_GLOBAL_CONFIG = 'global';

    public const ECOM_BUILD_TYPE_GENERAL = 'general';
    public const ECOM_BUILD_TYPE_BOOLEAN = 'boolean';
    public const ECOM_BUILD_TYPE_ARRAY = 'array';
    public const ECOM_BUILD_TYPE_STRUCT_ARRAY = 'structArray';
    public const ECOM_BUILD_TYPE_SELECT_SINGLE = 'selectSingle';
    public const ECOM_BUILD_TYPE_SELECT_MULTIPLE = 'selectMultiple';
    public const ECOM_BUILD_TYPE_RADIO = 'radio';
    public const ECOM_BUILD_TYPE_UNKNOWN = 'unknown';
}
