<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/14/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\Constants;

/**
 * Class SettlementMultiplePrintFormConstants
 */
class InternalConstants
{
    public const CID_BLK_DATA_GRID = 'c2';
    public const CID_BLK_DATA_GRID_BOTTOM_ROW_TPL = 'c2row%s';
    public const CID_BLK_TAX_AMOUNT = 'tax-amount';

    public const STYLE_UNSOLD_LOT = <<<CSS
font-weight: bold!important; font-style: italic!important;
CSS;

}
