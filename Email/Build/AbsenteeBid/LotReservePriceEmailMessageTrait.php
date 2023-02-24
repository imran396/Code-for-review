<?php
/**
 * SAM-5018 Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 24, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email\Build\AbsenteeBid;


/**
 * Trait LotReservePriceEmailMessageTrait
 * @package Sam\Email\Build\AbsenteeBid
 */
trait LotReservePriceEmailMessageTrait
{
    protected ?LotReservePriceEmailMessage $lotReservePriceEmailMessage = null;

    /**
     * @return LotReservePriceEmailMessage
     */
    protected function getLotReservePriceEmailMessage(): LotReservePriceEmailMessage
    {
        if ($this->lotReservePriceEmailMessage === null) {
            $this->lotReservePriceEmailMessage = LotReservePriceEmailMessage::new();
        }
        return $this->lotReservePriceEmailMessage;
    }

    /**
     * @param LotReservePriceEmailMessage $lotReservePriceEmailMessage
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotReservePriceEmailMessage(LotReservePriceEmailMessage $lotReservePriceEmailMessage): static
    {
        $this->lotReservePriceEmailMessage = $lotReservePriceEmailMessage;
        return $this;
    }
}
