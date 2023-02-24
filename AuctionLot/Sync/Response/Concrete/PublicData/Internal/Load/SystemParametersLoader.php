<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load;

use RuntimeException;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\PublicDataProducer;
use Sam\Core\Service\CustomizableClass;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Storage\Database\SimpleMysqliDatabaseAwareTrait;

/**
 * Class SystemParametersLoader
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load
 * @internal
 */
class SystemParametersLoader extends CustomizableClass
{
    use SimpleMysqliDatabaseAwareTrait;
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load system parameters fields
     *
     * @param int $accountId
     * @param bool $enableProfiling
     * @return array
     */
    public function load(int $accountId, bool $enableProfiling = false): array
    {
        $this->getSimpleMysqliDatabase()->construct($enableProfiling);
        $tmpTs = microtime(true);
        $query = $this->makeSystemParametersQuery($accountId);
        if (!$query) {
            return [];
        }
        $dbResult = $this->getSimpleMysqliDatabase()->query($query);
        if ($dbResult === false) {
            $message = 'Failed to fetch system parameters';
            $this->getSupportLogger()->always($message);
            throw new RuntimeException($message);
        }

        $row = $dbResult->fetch_assoc();
        $dbResult->free();

        if (!$row) {
            $message = 'System parameters query result is empty' . composeSuffix(['acc' => $accountId]);
            $this->getSupportLogger()->error($message);
            throw new RuntimeException($message);
        }

        if ($enableProfiling) {
            $this->getSupportLogger()->debug('fetch system parameters: ' . ((microtime(true) - $tmpTs) * 1000) . 'ms');
        }
        return $row;
    }

    /**
     * Get the query string for the system parameters query
     * (setting_auction table)
     *
     * @param int $accountId
     * @return string the query string
     */
    protected function makeSystemParametersQuery(int $accountId): string
    {
        $query = 'SELECT'
            . ' seta.`' . PublicDataProducer::SETTINGS_DISPLAY_BIDDING_INFO . '`,'
            . ' seta.`' . PublicDataProducer::SETTINGS_SHOW_WINNER_IN_CATALOG . '`'
            . ' FROM `setting_auction` seta'
            . ' WHERE `account_id`=' . $this->getSimpleMysqliDatabase()->escape($accountId);
        return $query;
    }
}
