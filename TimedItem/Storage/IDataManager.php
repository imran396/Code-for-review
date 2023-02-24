<?php
/**
 * Interface for data manager of timed online item
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 04, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\TimedItem\Storage;

use Sam\Core\Service\CustomizableClassInterface;

/**
 * Interface IDataManager
 * @package Sam\TimedItem\Storage
 */
interface IDataManager extends CustomizableClassInterface
{
    /**
     * Lock timed online item entry inside transaction
     * @param int $timedItemId
     */
    public function lockInTransaction(int $timedItemId): void;

}
