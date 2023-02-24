<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Translate;

/**
 * Trait MultipleLotStatusChangeTranslatorCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Translate
 */
trait MultipleLotStatusChangeTranslatorCreateTrait
{
    protected ?MultipleLotStatusChangeTranslator $multipleLotStatusChangeTranslator = null;

    /**
     * @return MultipleLotStatusChangeTranslator
     */
    protected function createMultipleLotStatusChangeTranslator(): MultipleLotStatusChangeTranslator
    {
        return $this->multipleLotStatusChangeTranslator ?: MultipleLotStatusChangeTranslator::new();
    }

    /**
     * @param MultipleLotStatusChangeTranslator $multipleLotStatusChangeTranslator
     * @return static
     * @internal
     */
    public function setMultipleLotStatusChangeTranslator(MultipleLotStatusChangeTranslator $multipleLotStatusChangeTranslator): static
    {
        $this->multipleLotStatusChangeTranslator = $multipleLotStatusChangeTranslator;
        return $this;
    }
}
