<?php
/**
 * SAM-9728: Move \Stats_Server to Sam namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\StatisticServer;

/**
 * Trait StatisticServerHttpClientCreateTrait
 * @package Sam\Infrastructure\StatisticServer
 */
trait StatisticServerHttpClientCreateTrait
{
    /**
     * @var StatisticServerHttpClient|null
     */
    protected ?StatisticServerHttpClient $statisticServerHttpClient = null;

    /**
     * @return StatisticServerHttpClient
     */
    protected function createStatisticServerHttpClient(): StatisticServerHttpClient
    {
        return $this->statisticServerHttpClient ?: StatisticServerHttpClient::new();
    }

    /**
     * @param StatisticServerHttpClient $statisticServerHttpClient
     * @return static
     * @internal
     */
    public function setStatisticServerHttpClient(StatisticServerHttpClient $statisticServerHttpClient): static
    {
        $this->statisticServerHttpClient = $statisticServerHttpClient;
        return $this;
    }
}
