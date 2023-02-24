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

namespace Sam\PublicMainMenu\Item\Produce\Single;

/**
 * Trait SinglePublicMainMenuItemProducerCreateTrait
 * @package Sam\PublicMainMenu\Item\Produce\Single
 */
trait SinglePublicMainMenuItemProducerCreateTrait
{
    protected ?SinglePublicMainMenuItemProducer $singlePublicMainMenuItemProducer = null;

    /**
     * @return SinglePublicMainMenuItemProducer
     */
    protected function createSinglePublicMainMenuItemProducer(): SinglePublicMainMenuItemProducer
    {
        return $this->singlePublicMainMenuItemProducer ?: SinglePublicMainMenuItemProducer::new();
    }

    /**
     * @param SinglePublicMainMenuItemProducer $singlePublicMainMenuItemProducer
     * @return static
     * @internal
     */
    public function setSinglePublicMainMenuItemProducer(SinglePublicMainMenuItemProducer $singlePublicMainMenuItemProducer): static
    {
        $this->singlePublicMainMenuItemProducer = $singlePublicMainMenuItemProducer;
        return $this;
    }
}
