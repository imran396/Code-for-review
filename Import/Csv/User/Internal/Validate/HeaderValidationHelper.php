<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\User\Internal\Validate;

use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class HeaderValidationHelper
 * @package Sam\Import\Csv\User\Internal\Validate
 */
class HeaderValidationHelper extends CustomizableClass
{
    use AuctionHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CustomFieldCsvHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Only username column is required
     *
     * @return array
     */
    public function detectRequiredColumns(): array
    {
        $columnNames = $this->cfg()->get('csv->admin->user')->toArray();
        return [$columnNames[Constants\Csv\User::USERNAME]];
    }

    /**
     * Available columns depend on user custom fields and may contain additional fields
     * if the hybrid auction is available
     *
     * @param int $accountId
     * @param array $userCustomFields
     * @return array
     */
    public function detectAvailableColumns(int $accountId, array $userCustomFields): array
    {
        $columnNames = $this->cfg()->get('csv->admin->user')->toArray();
        $excludedColumns = array_merge(
            $this->getAuctionHelper()->isHybridAuctionAvailable($accountId)
                ? []
                : [
                Constants\Csv\User::ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID => null,
                Constants\Csv\User::BP_CALCULATION_HYBRID => null,
                Constants\Csv\User::BP_RANGES_HYBRID => null,
            ],
            [
                Constants\Csv\User::REGISTRATION_DATE => null,
                Constants\Csv\User::LAST_BID_DATE => null,
                Constants\Csv\User::LAST_WIN_DATE => null,
                Constants\Csv\User::LAST_INVOICE_DATE => null,
                Constants\Csv\User::LAST_PAYMENT_DATE => null,
                Constants\Csv\User::LAST_LOGIN_DATE => null,
            ]
        );

        $customFieldCsvHelper = $this->createCustomFieldCsvHelper();
        foreach ($userCustomFields as $userCustomField) {
            $columnNames[] = $customFieldCsvHelper->makeCustomFieldColumnName($userCustomField);
        }

        return array_diff_key($columnNames, $excludedColumns);
    }
}
