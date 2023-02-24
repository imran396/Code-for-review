<?php
/**
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\WinnerInfo;

/**
 * Trait WinnerInfoBuilderCreateTrait
 * @package Sam\Rtb
 */
trait WinnerInfoBuilderCreateTrait
{
    protected ?WinnerInfoBuilder $winnerInfoBuilder = null;

    /**
     * @return WinnerInfoBuilder
     */
    protected function createWinnerInfoBuilder(): WinnerInfoBuilder
    {
        return $this->winnerInfoBuilder ?: WinnerInfoBuilder::new();
    }

    /**
     * @param WinnerInfoBuilder $winnerInfoBuilder
     * @return $this
     * @internal
     */
    public function setWinnerInfoBuilder(WinnerInfoBuilder $winnerInfoBuilder): static
    {
        $this->winnerInfoBuilder = $winnerInfoBuilder;
        return $this;
    }
}
