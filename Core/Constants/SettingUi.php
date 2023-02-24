<?php
/**
 * SAM-10664: Refactoring of settings system parameters storage - Move constants
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class SettingUi
 * @package Sam\Core\Constants
 */
class SettingUi
{
    // Landing Page (setting_ui.landing_page)
    public const LP_AUCTION = 'A';
    public const LP_SEARCH = 'S';
    public const LP_OTHER = 'O';

    // Search Results Format (setting_ui.search_results_format)
    public const SRF_LIST = 'T';
    public const SRF_GRID = 'L';
    public const SRF_COMPACT = 'C';

    /** @var string[] */
    public const SEARCH_RESULTS_FORMAT_NAMES = [
        self::SRF_GRID => 'Grid view',
        self::SRF_LIST => 'List view',
        self::SRF_COMPACT => 'Compact view',
    ];

    // Page Header Type (setting_ui.page_header_type)
    public const PHT_TEXT = 'T';
    public const PHT_LOGO = 'L';
    public const PHT_URL = 'U';
}
