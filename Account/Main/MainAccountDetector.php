<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Main;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class MainAccountDetector
 * @package Sam\Account\Main
 */
class MainAccountDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    protected const CFG_MAIN_ACCOUNT_ID = 'core->portal->mainAccountId';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Results with id of main account fetched from installation configuration.
     * @return int
     */
    public function id(): int
    {
        return $this->cfg()->get($this->configKey());
    }

    public function configKey(): string
    {
        return self::CFG_MAIN_ACCOUNT_ID;
    }
}
