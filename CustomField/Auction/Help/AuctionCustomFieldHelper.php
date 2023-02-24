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
 * @since           Oct 19, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Help;

use Sam\Core\Constants;
use Sam\CustomField\Base\Help\BaseCustomFieldHelper;

/**
 * Class AuctionCustomFieldHelper
 * @package Sam\CustomField\Auction\Help
 */
class AuctionCustomFieldHelper extends BaseCustomFieldHelper
{
    /**
     * Custom Method Prefix
     */
    protected string $customMethodPrefix = 'AuctionCustomField_';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array of auction custom field types: key: type id, value: type name
     */
    public function getTypeNames(): array
    {
        $typeNames = array_intersect_key(Constants\CustomField::$typeNames, array_flip(Constants\AuctionCustomField::$availableTypes));
        return $typeNames;
    }
}
