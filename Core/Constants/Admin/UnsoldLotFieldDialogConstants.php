<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Vahagn Hovsepyan
 * @since         Jan 21, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class UnsoldLotFieldDialogConstants
 */
class UnsoldLotFieldDialogConstants
{
    public const CID_ICO_CONFIRM_WAIT = 'unslfd0';
    public const CID_CHK_LST_LOT_FIELDS = 'unslfd3';
    public const CID_BTN_HTML = 'unslfd1';
    public const CID_BTN_CANCEL = 'unslfd2';
    public const CID_BTN_CSV = 'unslfd4';

    public const UNSOLD_LOT_FIELDS = [
        'LotNum' => ['LotNum', true],
        'Quantity' => ['Quantity', true],
        'Name' => ['Name', true],
        'ReservePrice' => ['Reserve Price', true],
        'LowEstimate' => ['Low Estimate', true],
        'HighEstimate' => ['High Estimate', true],
        'Consignor' => ['Consignor', true],
        'ItemNum' => ['ItemNum', false],
        'Description' => ['Description', false],
        'StartingBid' => ['Starting Bid', false],
        'Cost' => ['Cost', false],
        'ReplacementPrice' => ['Replacement Price', false],
        'SalesTax' => ['Sales Tax', false],
        'GeneralNote' => ['General note', false],
        'NoteToClerk' => ['Note to auction clerk', false],
    ];
}
