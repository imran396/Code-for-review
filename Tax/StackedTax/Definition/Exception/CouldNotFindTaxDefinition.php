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

use RuntimeException;

/**
 * Class CouldNotFindTaxDefinition
 * @package Sam\Tax\StackedTax\Definition\Exception
 */
class CouldNotFindTaxDefinition extends RuntimeException
{
    /**
     * @param int|null $taxDefinitionId
     * @return self
     */
    public static function withId(?int $taxDefinitionId): self
    {
        $message = "Could not find TaxDefinition by id \"{$taxDefinitionId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
