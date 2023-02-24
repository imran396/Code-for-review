<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июль 06, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Feature\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AvailabilityChecker. Checks is installation management available.
 * @package Sam\Installation\Config
 */
class FeatureAvailabilityChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks is installation management available.
     * @return bool
     */
    public function isAvailable(): bool
    {
        $allowed = true;
        if (empty($this->getExpectedUsername()) || empty($this->getExpectedPassword())) {
            $allowed = false;
        }
        return $allowed;
    }

    /**
     * Getting expected username from configuration,
     * @return string
     */
    protected function getExpectedUsername(): string
    {
        $output = (string)$this->cfg()->get('core->install->username');
        return $output;
    }

    /**
     * Getting expected password from configuration.
     * @return string
     */
    protected function getExpectedPassword(): string
    {
        $output = (string)$this->cfg()->get('core->install->password');
        return $output;
    }

}
