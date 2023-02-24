<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Statistic;

/**
 * Trait StatisticManagerAwareTrait
 * @package Sam\Rtb\Server\Statistic
 */
trait StatisticManagerAwareTrait
{
    protected ?StatisticManager $statisticManager = null;

    /**
     * @return StatisticManager
     */
    protected function getStatisticManager(): StatisticManager
    {
        if ($this->statisticManager === null) {
            $this->statisticManager = StatisticManager::new();
        }
        return $this->statisticManager;
    }

    /**
     * @param StatisticManager $statisticManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setStatisticManager(StatisticManager $statisticManager): static
    {
        $this->statisticManager = $statisticManager;
        return $this;
    }
}
