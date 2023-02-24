<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb;

/**
 * Trait RtbMessengerCreateTrait
 * @package Sam\Rtb
 */
trait RtbMessengerCreateTrait
{
    /**
     * @var Messenger|null
     */
    protected ?Messenger $rtbMessenger = null;

    /**
     * @return Messenger
     */
    protected function createRtbMessenger(): Messenger
    {
        return $this->rtbMessenger ?: Messenger::new();
    }

    /**
     * @param Messenger $rtbMessenger
     * @return $this
     * @internal
     */
    public function setRtbMessenger(Messenger $rtbMessenger): static
    {
        $this->rtbMessenger = $rtbMessenger;
        return $this;
    }
}
