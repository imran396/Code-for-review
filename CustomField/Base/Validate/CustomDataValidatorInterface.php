<?php

/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 28, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Validate;

use AuctionCustField;
use UserCustField;

/**
 * Interface CustomDataValidatorInterface
 * @package Sam\CustomField\Base\Validate
 */
interface CustomDataValidatorInterface
{
    /**
     * @param UserCustField|AuctionCustField $customField
     * @param int|string|bool|null $value can accept int|string|bool|null values depending of custom field type.
     * @param string|null $encoding
     * @return bool
     */
    public function validate($customField, int|string|bool|null $value, ?string $encoding = null): bool;
}
