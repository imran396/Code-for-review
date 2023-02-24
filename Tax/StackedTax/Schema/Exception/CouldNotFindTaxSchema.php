<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Exception;

use RuntimeException;

/**
 * Class CouldNotFindTaxSchema
 * @package Sam\Tax\StackedTax\Schema\Exception
 */
class CouldNotFindTaxSchema extends RuntimeException
{
    /**
     * @param int|null $taxSchemaId
     * @return self
     */
    public static function withId(?int $taxSchemaId): self
    {
        $message = "Could not find TaxSchema by id \"{$taxSchemaId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
