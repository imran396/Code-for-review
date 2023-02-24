<?php
/**
 * Interface for Service for storing current rtb state. It allows to save and restore state snapshots.
 * Assumed to use for Undo feature.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 Okt, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\History\Base;

use RtbCurrent;
use RtbCurrentSnapshot;

/**
 * Interface ServiceInterface
 * @package Sam\Rtb\State\History\Base
 */
interface ServiceInterface
{
    /**
     * Make snapshot of current rtb state
     * @param RtbCurrent $rtbCurrent
     * @param string $command
     * @param int $editorUserId
     */
    public function snapshot(RtbCurrent $rtbCurrent, string $command, int $editorUserId): void;

    /**
     * Restore rtb state from the last snapshot, roll back current bid if needed
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     * @return RtbCurrent|null
     */
    public function restore(RtbCurrent $rtbCurrent, int $editorUserId): ?RtbCurrent;

    /**
     * Return command name of snapshot
     * @param RtbCurrentSnapshot $snapshot
     * @return string
     */
    public function getCommandName(RtbCurrentSnapshot $snapshot): string;

    /**
     * Return supported commands with names
     * @return array
     */
    public function getCommands(): array;

    /**
     * Return snapshot, that was previously restored by this service
     * @return RtbCurrentSnapshot
     */
    public function getLastRestoredSnapshot(): ?RtbCurrentSnapshot;

    /**
     * @return Helper
     */
    public function getHistoryHelper(): Helper;
}
