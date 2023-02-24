<?php
/**
 * Output query statistics to log, when db profiling is enabled
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class DbProfilingFinisher
 * @package Sam\Application\Index
 */
class DbProfilingLogger extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function log(): void
    {
        if (!$this->cfg()->get('core->db->profiling')) {
            return;
        }

        $queries = $this->getDb()->getQueryStat();
        ksort($queries);
        asort($queries);
        $queries["Total query count"] = array_sum($queries);
        log_always($queries);
    }
}
