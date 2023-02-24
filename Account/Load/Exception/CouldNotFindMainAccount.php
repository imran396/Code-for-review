<?php
/**
 * SAM-9665: Assign system user reference to CreatedBy property of entities
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Load\Exception;

use Sam\Account\Main\MainAccountDetector;

class CouldNotFindMainAccount extends CouldNotFindAccount
{
    /**
     * @return self
     */
    public static function withDefaultMessage(): self
    {
        $mainAccountDetector = MainAccountDetector::new();
        $configKey = $mainAccountDetector->configKey();
        $mainAccountId = $mainAccountDetector->id();
        return new self("Could not find main account by id \"{$mainAccountId}\", check installation config option \"{$configKey}\"");
    }
}
