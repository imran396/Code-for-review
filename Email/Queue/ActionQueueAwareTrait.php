<?php
/**
 * SAM-5018 Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 13, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email\Queue;


/**
 * Trait ActionQueueAwareTrait
 * @package Sam\Email\Queue
 */
trait ActionQueueAwareTrait
{
    protected ?ActionQueue $actionQueue = null;

    /**
     * @return ActionQueue
     */
    protected function getActionQueue(): ActionQueue
    {
        if ($this->actionQueue === null) {
            $this->actionQueue = ActionQueue::new();
        }
        return $this->actionQueue;
    }

    /**
     * @param ActionQueue $actionQueue
     * @return static
     * @internal
     */
    public function setActionQueue(ActionQueue $actionQueue): static
    {
        $this->actionQueue = $actionQueue;
        return $this;
    }
}
