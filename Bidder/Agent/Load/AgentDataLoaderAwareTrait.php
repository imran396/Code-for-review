<?php
/**
 * SAM-3654: User related repositories
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/1/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Agent\Load;

/**
 * Trait AgentDataLoaderAwareTrait
 * @package Sam\Bidder\Agent\Load
 */
trait AgentDataLoaderAwareTrait
{
    protected ?AgentDataLoader $agentDataLoader = null;

    /**
     * @return AgentDataLoader
     */
    protected function getAgentDataLoader(): AgentDataLoader
    {
        if ($this->agentDataLoader === null) {
            $this->agentDataLoader = AgentDataLoader::new();
        }
        return $this->agentDataLoader;
    }

    /**
     * @param AgentDataLoader $agentDataLoader
     * @return static
     * @internal
     */
    public function setAgentDataLoader(AgentDataLoader $agentDataLoader): static
    {
        $this->agentDataLoader = $agentDataLoader;
        return $this;
    }
}
