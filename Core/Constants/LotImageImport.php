<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-2785: Image Import- Append image option check box
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/20/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class LotImageImport
 * @package Sam\Core\Constants
 */
class LotImageImport
{
    public const LNS_DOT = 0;
    public const LNS_UNDERSCORE = 1;
    public const LNS_DASH = 2;
    public const LNS_BRACKET = 3;

    public const LT_FTP = 'FTP';

    public const ASSOCIATE_BY_BARCODE = 1;
    public const ASSOCIATE_BY_LOT_NUMBER = 2;
    public const ASSOCIATE_BY_ITEM_NUMBER = 3;
    public const ASSOCIATE_BY_CUSTOM_FIELD = 4;
    public const ASSOCIATE_BY_FILENAMES_IN_CSV = 5;
    public const ASSOCIATE_MANUALLY = 6;

    public const INSERT_STRATEGY_REPLACE = 0;
    public const INSERT_STRATEGY_PREPEND = 1;
    public const INSERT_STRATEGY_APPEND = 2;

    public const RESPONSE_SUCCESS = 'success';
    public const RESPONSE_ERROR = 'error';
}
