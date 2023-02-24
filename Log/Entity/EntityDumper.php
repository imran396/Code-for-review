<?php
/**
 * Helper methods for logging and debug
 *
 * SAM-6729: Improve logging - entity dump attribute logging options
 * SAM-3907: Improve logging and debug
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Sep 28, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Entity;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Entity\Config\EntityDumpConfig;
use Sam\Log\Entity\Internal\Build\OutputBuilder;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class VarDumper
 * @package Sam\Log
 */
class EntityDumper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SupportLoggerAwareTrait;

    // We shouldn't output log info when debugLevel application option is stricter than this restriction value
    public const OP_DEBUG_LEVEL_DISPLAY_RESTRICTION = 'debugLevelDisplayLimit'; // int

    /**
     * Get instance of Logger
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param mixed $entity
     * @param array $idInfo
     * @param EntityDumpConfig|null $config
     * @param array $optionals = [
     *      self::OP_DEBUG_LEVEL_DISPLAY_RESTRICTION => int,
     * ]
     */
    public function logEntityChanges(
        mixed $entity,
        array $idInfo = [],
        ?EntityDumpConfig $config = null,
        array $optionals = []
    ): void {
        if (!$entity->__Modified) {
            return;
        }

        $debugLevelMax = (int)($optionals[self::OP_DEBUG_LEVEL_DISPLAY_RESTRICTION] ?? Constants\Debug::INFO);

        $logOutputCb = static function () use ($config, $entity, $idInfo) {
            $config = $config ?? EntityDumpConfig::new();
            return OutputBuilder::new()
                ->construct()
                ->build($entity, $config, $idInfo);
        };

        $this->getSupportLogger()->log($debugLevelMax, $logOutputCb);
    }
}
