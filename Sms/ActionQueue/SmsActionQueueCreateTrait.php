<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\ActionQueue;

/**
 * Trait SmsActionQueueCreateTrait
 * @package Sam\Sms\ActionQueue
 */
trait SmsActionQueueCreateTrait
{
    protected ?SmsActionQueue $smsActionQueue = null;

    /**
     * @return SmsActionQueue
     */
    protected function createSmsActionQueue(): SmsActionQueue
    {
        return $this->smsActionQueue ?: SmsActionQueue::new();
    }

    /**
     * @param SmsActionQueue $smsActionQueue
     * @return static
     * @internal
     */
    public function setSmsActionQueue(SmsActionQueue $smsActionQueue): static
    {
        $this->smsActionQueue = $smsActionQueue;
        return $this;
    }
}
