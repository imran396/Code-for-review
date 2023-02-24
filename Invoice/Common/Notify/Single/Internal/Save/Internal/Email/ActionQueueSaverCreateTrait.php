<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single\Internal\Save\Internal\Email;

/**
 * Trait ActionQueueSaverCreateTrait
 * @package Sam\Invoice\Common\Notify\Single\Internal\Save\Internal\Email
 */
trait ActionQueueSaverCreateTrait
{
    protected ?ActionQueueSaver $actionQueueSaver = null;

    /**
     * @return ActionQueueSaver
     */
    protected function createActionQueueSaver(): ActionQueueSaver
    {
        return $this->actionQueueSaver ?: ActionQueueSaver::new();
    }

    /**
     * @param ActionQueueSaver $actionQueueSaver
     * @return $this
     * @internal
     */
    public function setActionQueueSaver(ActionQueueSaver $actionQueueSaver): self
    {
        $this->actionQueueSaver = $actionQueueSaver;
        return $this;
    }
}
