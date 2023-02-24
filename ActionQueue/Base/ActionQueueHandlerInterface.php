<?php

namespace Sam\ActionQueue\Base;

use ActionQueue;
use Sam\Core\Service\CustomizableClassInterface;

/**
 * Classes that handle ActionQueue events
 * need to implement this interface and extend CustomizableClassInterface
 */
interface ActionQueueHandlerInterface extends CustomizableClassInterface
{
    /**
     * Method called to handle the ActionQueue event
     * @param ActionQueue $actionQueue entity from `action_queue` table
     * @return bool return whether it was processed successfully or not
     */
    public function process(ActionQueue $actionQueue): bool;
}
