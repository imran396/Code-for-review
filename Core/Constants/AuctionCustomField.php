<?php
/**
 *
 * SAM-4589: Hard-coded values to constants adjustments
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-10
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class AuctionCustomField
 * @package Sam\Core\Constants
 */
class AuctionCustomField
{
    /** @var int[] */
    public static array $availableTypes = [
        CustomField::TYPE_INTEGER,
        CustomField::TYPE_DECIMAL,
        CustomField::TYPE_TEXT,
        CustomField::TYPE_SELECT,
        CustomField::TYPE_DATE,
        CustomField::TYPE_FULLTEXT,
        CustomField::TYPE_CHECKBOX,
        CustomField::TYPE_PASSWORD,
        CustomField::TYPE_FILE,
        CustomField::TYPE_LABEL,
    ];
}
