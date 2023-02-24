<?php
/**
 * SAM-11722: Public main menu management
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Item\Produce\Multiple;

/**
 * Trait MultiplePublicMainMenuItemProducerCreateTrait
 * @package Sam\PublicMainMenu\Item\Produce\Multiple
 */
trait MultiplePublicMainMenuItemProducerCreateTrait
{
    protected ?MultiplePublicMainMenuItemProducer $multiplePublicMainMenuItemProducer = null;

    /**
     * @return MultiplePublicMainMenuItemProducer
     */
    protected function createMultiplePublicMainMenuItemProducer(): MultiplePublicMainMenuItemProducer
    {
        return $this->multiplePublicMainMenuItemProducer ?: MultiplePublicMainMenuItemProducer::new();
    }

    /**
     * @param MultiplePublicMainMenuItemProducer $multiplePublicMainMenuItemProducer
     * @return static
     * @internal
     */
    public function setMultiplePublicMainMenuItemProducer(MultiplePublicMainMenuItemProducer $multiplePublicMainMenuItemProducer): static
    {
        $this->multiplePublicMainMenuItemProducer = $multiplePublicMainMenuItemProducer;
        return $this;
    }
}
