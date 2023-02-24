<?php
/**
 * Check db connection, is used in HealthChecker
 *
 * SAM-7956: Create a basic health check endpoint /health
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Health\Internal\Validate\Concrete;

use Exception;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Db\Exception\DbAdapterException;
use Sam\Infrastructure\Db\Exception\DbDisabledException;

/**
 * Class DbChecker
 * @package Sam\Infrastructure\Health
 */
class DbChecker extends CustomizableClass
{
    use DbConnectionTrait;

    public bool $isAdapterIncorrect = false;
    public bool $isDisabled = false;
    public bool $isUnavailable = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        try {
            $this->isUnavailable = !$this->runQuery();
            return !$this->isUnavailable;
        } catch (DbAdapterException) {
            $this->isAdapterIncorrect = true;
            return false;
        } catch (DbDisabledException) {
            $this->isDisabled = true;
            return false;
        } catch (Exception) {
            $this->isUnavailable = true;
            return false;
        }
    }

    /**
     * @return bool
     * @throws DbDisabledException
     * @throws DbAdapterException
     */
    protected function runQuery(): bool
    {
        // Throw mysqli_sql_exception for errors instead of warnings
        mysqli_report(MYSQLI_REPORT_STRICT);

        $query = 'SELECT 1 FROM dual';
        $dbResult = $this->query($query);
        if (!$dbResult) {
            return false;
        }

        $isAvailable = $dbResult->FetchRow()[0] === '1';
        return $isAvailable;
    }
}
