<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Entity\Restore\Cli\Command\Handler;


/**
 * Interface EntityRestoreCommandHandlerInterface
 * @package Sam\Entity\Restore\Cli
 */
interface EntityRestoreCommandHandlerInterface
{
    public function construct();

    /**
     * @return string
     */
    public function getEntityName(): string;

    /**
     * @param int $entityId
     * @return EntityRestoreCommandHandlerResponse
     */
    public function restore(int $entityId): EntityRestoreCommandHandlerResponse;
}
