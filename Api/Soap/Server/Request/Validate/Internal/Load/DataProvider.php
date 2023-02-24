<?php
/**
 * Provides custom field names for SoapRequestValidator
 *
 * SAM-5794: SOAP call processing shouldn't ignore incorrect fields
 * SAM-6445: Soap should ignore tags with xsi:nil="true" attribute
 *
 * Project        SOAP Server
 * @author        Victor Pautoff
 * @version       SVN: $Id: $
 * @since         May 04, 2021
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Server\Request\Validate\Internal\Load;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoader;
use Sam\CustomField\Lot\Load\LotCustomFieldLoader;
use Sam\CustomField\User\Load\UserCustomFieldLoader;

/**
 * Class SoapRequestHelper
 * @package Sam\Soap
 */
class DataProvider extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAllAuctionCustomFieldNames(bool $isReadOnlyDb = false): array
    {
        $rows = AuctionCustomFieldLoader::new()->loadSelectedAll(['name'], $isReadOnlyDb);
        $names = ArrayCast::arrayColumnString($rows, 'name');
        return $names;
    }

    public function loadAllUserCustomFieldNames(bool $isReadOnlyDb = false): array
    {
        $rows = UserCustomFieldLoader::new()->loadSelectedAll(['name'], $isReadOnlyDb);
        $names = ArrayCast::arrayColumnString($rows, 'name');
        return $names;
    }

    public function loadEditableLotCustomFieldNames(bool $isReadOnlyDb = false): array
    {
        $rows = LotCustomFieldLoader::new()->loadSelectedAllEditable(['name'], $isReadOnlyDb);
        $names = ArrayCast::arrayColumnString($rows, 'name');
        return $names;
    }
}
