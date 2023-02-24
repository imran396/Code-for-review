<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Exception;

use Exception;
use QMySqliDatabaseException;

/**
 * Class DuplicateTaxDefinitionNameException
 * @package Sam\Tax\StackedTax\Definition\Exception
 */
class DuplicateTaxDefinitionNameException extends Exception
{
    protected const UNIQUE_INDEX_NAME = 'idx_tax_definition_account_id_name_active_unique';

    public static function fromQMySqliDatabaseException(QMySqliDatabaseException $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }

    public static function isDuplicateTaxDefinitionNameError(string $message): bool
    {
        return str_contains($message, 'Duplicate entry')
            && str_contains($message, self::UNIQUE_INDEX_NAME);
    }
}
