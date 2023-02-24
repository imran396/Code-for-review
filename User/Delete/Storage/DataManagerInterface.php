<?php
/**
 * Interface for data manager for User Deleter class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 04, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Delete\Storage;

use Sam\Core\Service\CustomizableClassInterface;

/**
 * Interface DataManagerInterface
 * @package Sam\User\Delete\Storage
 */
interface DataManagerInterface extends CustomizableClassInterface
{
    /**
     * Return count of admin users linked to main account
     * @return int
     */
    public function countMainAccountAdmins(): int;

}
