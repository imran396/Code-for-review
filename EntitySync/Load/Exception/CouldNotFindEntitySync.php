<?php
/**
 * Exception in case entitySync is not found
 *
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntitySync\Load\Exception;

use RuntimeException;

class CouldNotFindEntitySync extends RuntimeException
{
    /**
     * @param int|null $entityId
     * @param int|null $entityType
     * @return self
     */
    public static function withEntityIdAndType(?int $entityId, ?int $entityType): self
    {
        $message = "Could not find EntitySync by entity id \"{$entityId}\" and type \"{$entityType}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
