<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class LotCustomField
 * @package Sam\Core\Constants
 */
class LotCustomField
{
    /** @var int[] */
    public static array $availableTypes = [
        CustomField::TYPE_INTEGER,
        CustomField::TYPE_DECIMAL,
        CustomField::TYPE_TEXT,
        CustomField::TYPE_SELECT,
        CustomField::TYPE_DATE,
        CustomField::TYPE_FULLTEXT,
        CustomField::TYPE_FILE,
        CustomField::TYPE_POSTALCODE,
        CustomField::TYPE_YOUTUBELINK,
    ];

    public static array $lotCategoryTypes = [
        CustomField::TYPE_INTEGER,
        CustomField::TYPE_DECIMAL,
        CustomField::TYPE_TEXT,
        CustomField::TYPE_SELECT,
        CustomField::TYPE_FULLTEXT,
    ];
}
