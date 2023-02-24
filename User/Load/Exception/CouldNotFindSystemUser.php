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

namespace Sam\User\Load\Exception;

use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\User\Load\UserLoader;

class CouldNotFindSystemUser extends CouldNotFindUser
{
    /**
     * @return self
     */
    public static function withDefaultMessage(): self
    {
        $configKey = UserLoader::CFG_SYSTEM_USERNAME;
        $username = ConfigRepository::getInstance()->get($configKey);
        return new self("System user not found by username \"{$username}\", check installation config option \"{$configKey}\"");
    }
}
