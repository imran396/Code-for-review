<?php
/**
 * SAM-4921: Clear message center and auction history at Rtb Admin Clerk and Auctioneer consoles.
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           23.03.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Rtb\Log;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LogCleaner
 * @package Sam\Rtb\Log
 */
class LogCleaner extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clean auction lot history
     *
     * @param string $messageType one of \Sam\Rtb\Messenger::MESSAGE_TYPE_*
     * @param int $auctionId
     * @return bool true on success, false on failure
     */
    public function clean(string $messageType, int $auctionId): bool
    {
        if ($auctionId) {
            $dirPath = path()->docRoot() . '/lot-info';
            $filePath = $dirPath . '/' . $messageType . "_" . $auctionId . '.html';
            if (file_exists($filePath)) {
                file_put_contents($filePath, '');
            }
        }

        log_info("Lot history cleaned" . composeSuffix(['a' => $auctionId]));
        return true;
    }

}
