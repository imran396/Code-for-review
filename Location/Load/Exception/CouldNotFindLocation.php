<?php
/**
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 7, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\Load\Exception;

use RuntimeException;

class CouldNotFindLocation extends RuntimeException
{
    /**
     * @param int|null $locationId
     * @return self
     */
    public static function withId(?int $locationId): self
    {
        $message = "Could not find Location by id \"{$locationId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
