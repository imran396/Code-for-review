<?php
/**
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/20/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\History;

/**
 * Trait StateHistoryServiceFactoryCreateTrait
 * @package Sam\Rtb\State\History
 */
trait HistoryServiceFactoryCreateTrait
{
    /**
     * @var HistoryServiceFactory|null
     */
    protected ?HistoryServiceFactory $historyServiceFactory = null;

    /**
     * @return HistoryServiceFactory
     */
    protected function createHistoryServiceFactory(): HistoryServiceFactory
    {
        $factory = $this->historyServiceFactory ?: HistoryServiceFactory::new();
        return $factory;
    }

    /**
     * @param HistoryServiceFactory $factory
     * @return static
     * @internal
     */
    public function setHistoryServiceFactory(HistoryServiceFactory $factory): static
    {
        $this->historyServiceFactory = $factory;
        return $this;
    }
}
