<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 30, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Lot\Note\Save;

/**
 * Trait LotNoteSaverCreateTrait
 * @package Sam\Rtb\Lot\Note\Save
 */
trait LotNoteSaverCreateTrait
{
    /**
     * @var LotNoteSaver|null
     */
    protected ?LotNoteSaver $lotNoteSaver = null;

    /**
     * @return LotNoteSaver
     */
    protected function createLotNoteSaver(): LotNoteSaver
    {
        return $this->lotNoteSaver ?: LotNoteSaver::new();
    }

    /**
     * @param LotNoteSaver $lotNoteSaver
     * @return $this
     * @internal
     */
    public function setLotNoteSaver(LotNoteSaver $lotNoteSaver): static
    {
        $this->lotNoteSaver = $lotNoteSaver;
        return $this;
    }
}
