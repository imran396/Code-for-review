<?php
/**
 * SAM-10008: Move sections' logic to separate Panel classes at Manage settings system parameters live/hybrid auction page (/admin/manage-system-parameter/live-hybrid-auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 01, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLiveHybridAuctionBiddingUiPanelConstants
 */
class SystemParameterLiveHybridAuctionBiddingUiPanelConstants
{
    public const CID_TXT_SWITCH_FRAME_SECONDS = 'scf41';
    public const CID_CHK_SLIDESHOW_PROJECTOR_ONLY = 'scf127';
    public const CID_LST_DEFAULT_IMAGE_PREVIEW = 'scf128';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_SWITCH_FRAME_SECONDS => Constants\Setting::SWITCH_FRAME_SECONDS,
        self::CID_CHK_SLIDESHOW_PROJECTOR_ONLY => Constants\Setting::SLIDESHOW_PROJECTOR_ONLY,
        self::CID_LST_DEFAULT_IMAGE_PREVIEW => Constants\Setting::DEFAULT_IMAGE_PREVIEW,
    ];
    /** @var string[] */
    public static array $imagePreviewOptions = [
        Constants\SettingRtb::DIP_NO_IMAGE_PREVIEW => 'bidding_ui.default_image_preview_in_catalog.option.no_image_preview',
        Constants\SettingRtb::DIP_DEFAULT_IMAGE_PREVIEW => 'bidding_ui.default_image_preview_in_catalog.option.default_image_preview',
        Constants\SettingRtb::DIP_THUMBNAIL_IMAGE_PREVIEW_FOR_BIDDERS => 'bidding_ui.default_image_preview_in_catalog.option.thumbnails_for_bidders',
        Constants\SettingRtb::DIP_THUMBNAIL_IMAGE_PREVIEW_FOR_VIEWERS => 'bidding_ui.default_image_preview_in_catalog.option.thumbnails_for_viewers',
    ];
}
