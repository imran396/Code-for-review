<?php
/**
 * SAM-5495: Rtb server and daemon refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\PendingAction;

/**
 * Trait PendingActionUpdaterCreateTrait
 * @package Sam\Rtb\State\PendingAction
 */
trait PendingActionUpdaterCreateTrait
{
    /**
     * @var PendingActionUpdater|null
     */
    protected ?PendingActionUpdater $pendingActionUpdater = null;

    /**
     * @return PendingActionUpdater
     */
    protected function createPendingActionUpdater(): PendingActionUpdater
    {
        return $this->pendingActionUpdater ?: PendingActionUpdater::new();
    }

    /**
     * @param PendingActionUpdater $pendingActionUpdater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setPendingActionUpdater(PendingActionUpdater $pendingActionUpdater): static
    {
        $this->pendingActionUpdater = $pendingActionUpdater;
        return $this;
    }
}
