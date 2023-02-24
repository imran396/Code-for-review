<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Location\Internal\Validate;

use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class HeaderValidationHelper
 * @package Sam\Import\Csv\Location\Internal\Validate
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
     * Only name column is required
     *
     * @return array
     */
    public function detectRequiredColumns(): array
    {
        $columnNames = $this->cfg()->get('csv->admin->location')->toArray();
        return [$columnNames[Constants\Csv\Location::NAME]];
    }

    /**
     * @return array
     */
    public function detectAvailableColumns(): array
    {
        return $this->cfg()->get('csv->admin->location')->toArray();
    }
}
