<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Validate\Web;

use AuctionCustField;
use Sam\CustomField\Base\Validate\CustomDataValidatorInterface;
use Sam\CustomField\Base\Validate\Web\CustomFieldWebDataSimpleValidator;

/**
 * Class AuctionCustomFieldWebDataValidator
 * @package Sam\CustomField\Auction\Validate\Web
 */
class AuctionCustomWebDataValidator extends CustomFieldWebDataSimpleValidator implements CustomDataValidatorInterface
{

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate value for auction custom field
     *
     * @param AuctionCustField $auctionCustomField
     * @param int|string|bool|null $value can accept int|string|bool|null values depending of custom field type.
     * @param string|null $encoding
     * @return bool
     */
    public function validate($auctionCustomField, int|string|bool|null $value, ?string $encoding = null): bool
    {
        $isValid = $this->validateByType(
            $value,
            $auctionCustomField->Type,
            $auctionCustomField->Parameters,
            $auctionCustomField->Required
        );
        return $isValid;
    }
}
