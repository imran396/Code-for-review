<?php
/**
 * SAM-6435: Refactor data loader for every rtb console running lot title
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\LotTitle\Load;

/**
 * Trait RunningLotTitleDataLoaderCreateTrait
 * @package Sam\Rtb
 */
trait RunningLotTitleDataLoaderCreateTrait
{
    /**
     * @var RunningLotTitleDataLoader|null
     */
    protected ?RunningLotTitleDataLoader $runningLotTitleDataLoader = null;

    /**
     * @return RunningLotTitleDataLoader
     */
    protected function createRunningLotTitleDataLoader(): RunningLotTitleDataLoader
    {
        return $this->runningLotTitleDataLoader ?: RunningLotTitleDataLoader::new();
    }

    /**
     * @param RunningLotTitleDataLoader $runningLotTitleDataLoader
     * @return $this
     * @internal
     */
    public function setRunningLotTitleDataLoader(RunningLotTitleDataLoader $runningLotTitleDataLoader): static
    {
        $this->runningLotTitleDataLoader = $runningLotTitleDataLoader;
        return $this;
    }
}
