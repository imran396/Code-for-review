<?php
/**
 * SAM-3502: Accidental high bid warning
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AskingBid;

/**
 * Trait LiveAbsenteeBidCalculatorCreateTrait
 * @package Sam\Bidding\AskingBid
 */
trait LiveAbsenteeBidCalculatorCreateTrait
{
    /**
     * @var LiveAbsenteeBidCalculator|null
     */
    protected ?LiveAbsenteeBidCalculator $liveAbsenteeBidCalculator = null;

    /**
     * @return LiveAbsenteeBidCalculator
     */
    protected function createLiveAbsenteeBidCalculator(): LiveAbsenteeBidCalculator
    {
        return $this->liveAbsenteeBidCalculator ?: LiveAbsenteeBidCalculator::new();
    }

    /**
     * @param LiveAbsenteeBidCalculator $liveAbsenteeBidCalculator
     * @return $this
     * @internal
     */
    public function setLiveAbsenteeBidCalculator(LiveAbsenteeBidCalculator $liveAbsenteeBidCalculator): static
    {
        $this->liveAbsenteeBidCalculator = $liveAbsenteeBidCalculator;
        return $this;
    }
}
