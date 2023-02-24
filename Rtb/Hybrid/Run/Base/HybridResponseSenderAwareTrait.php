<?php
/**
 * Response sending logic for hybrid auction rtbd processing
 * SAM-3775: Rtbd improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/24/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Hybrid\Run\Base;

/**
 * Trait ResponseSenderAwareTrait
 * @package Sam\Rtb\Hybrid\Run\Base
 */
trait HybridResponseSenderAwareTrait
{
    /**
     * @var HybridResponseSender|null
     */
    protected ?HybridResponseSender $hybridResponseSender = null;

    /**
     * @return HybridResponseSender
     */
    protected function getHybridResponseSender(): HybridResponseSender
    {
        if ($this->hybridResponseSender === null) {
            $this->hybridResponseSender = HybridResponseSender::new();
        }
        return $this->hybridResponseSender;
    }

    /**
     * @param HybridResponseSender $responseSender
     * @return static
     */
    public function setHybridResponseSender(HybridResponseSender $responseSender): static
    {
        $this->hybridResponseSender = $responseSender;
        return $this;
    }
}
