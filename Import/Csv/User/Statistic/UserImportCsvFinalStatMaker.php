<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\User\Statistic;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for generating messages about the result of the user import process
 *
 * Class BidIncrementImportCsvFinalStatMaker
 * @package Sam\Import\Csv\User\Statistic
 */
class UserImportCsvFinalStatMaker extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Make a messages based on the collected statistics
     *
     * @param UserImportCsvProcessStatistic $statistic
     * @return array
     */
    public function make(UserImportCsvProcessStatistic $statistic): array
    {
        $stat = [];

        $translator = $this->getAdminTranslator();
        $messages = [];
        if ($statistic->addedUsersQuantity > 0) {
            $messages[] = $translator->trans('import.csv.user.stat.added_users', ['qty' => $statistic->addedUsersQuantity], 'admin_message');
        }
        if ($statistic->updatedUsersQuantity > 0) {
            $messages[] = $translator->trans('import.csv.user.stat.updated_users', ['qty' => $statistic->updatedUsersQuantity], 'admin_message');
        }

        if ($messages) {
            $stat[] = ['type' => 'success', 'text' => implode(' ', $messages)];
        }

        return $stat;
    }
}
