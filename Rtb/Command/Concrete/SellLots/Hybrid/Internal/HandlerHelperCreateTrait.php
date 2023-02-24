<?php
/**
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Hybrid\Internal;

/**
 * Trait HandlerHelperCreateTrait
 * @package
 */
trait HandlerHelperCreateTrait
{
    protected ?HandlerHelper $handlerHelper = null;

    /**
     * @return HandlerHelper
     */
    protected function createHandlerHelper(): HandlerHelper
    {
        return $this->handlerHelper ?: HandlerHelper::new();
    }

    /**
     * @param HandlerHelper $handlerHelper
     * @return $this
     * @internal
     */
    public function setHandlerHelper(HandlerHelper $handlerHelper): static
    {
        $this->handlerHelper = $handlerHelper;
        return $this;
    }
}
