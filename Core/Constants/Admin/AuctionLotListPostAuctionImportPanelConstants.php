<?php
/**
 * SAM-6780: Move sections' logic to separate Panel classes at Manage auction lots page (/admin/manage-auctions/lots/id/%s)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-06, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


/**
 * Class AuctionLotListPostAuctionImportPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctionLotListPostAuctionImportPanelConstants
{
    public const CID_BLK_CSV_FORMAT_POST = 'csv-format-post';
    public const CID_BLK_SHOW_CSV_FORMAT_POST = 'show-csv-format-post';
    public const CID_BLK_UPLOAD_ABORT = 'upload-abort';
    public const CID_BLK_UPLOAD_BIDS = 'upload-bids';
    public const CID_BLK_UPLOAD_ERROR = 'upload-error';
    public const CID_BLK_UPLOAD_PROGRESS = 'upload-progress';
    public const CID_BTN_PAI_UPLOAD = 'alf76';
    public const CID_BTN_POST_AUCTION_IMPORT = 'section_postau';
    public const CID_CHK_PAI_UNASSIGN_UNSOLD = 'alf77';
    public const CID_FLA_PAI_UPLOAD = 'alf75';
    public const CID_LST_PAI_ENCODING = 'alf78';
    public const CID_TXT_PAI_PREMIUM = 'alf109';
}
