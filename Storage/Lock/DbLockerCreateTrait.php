<?php
/**
 * SAM-5652: Move db locking logic and apply trait
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 28, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Lock;

/**
 * Trait DbLockerCreateTrait
 * @package Sam\Storage\Lock
 */
trait DbLockerCreateTrait
{
    protected ?DbLocker $dbLocker = null;

    /**
     * @return DbLocker
     */
    protected function createDbLocker(): DbLocker
    {
        return $this->dbLocker ?: DbLocker::new();
    }

    /**
     * @param DbLocker $dbLocker
     * @return static
     * @internal
     */
    public function setDbLocker(DbLocker $dbLocker): static
    {
        $this->dbLocker = $dbLocker;
        return $this;
    }
}
