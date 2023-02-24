<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Daemon;

use RuntimeException;

/**
 * Trait RtbDaemonAwareTrait
 * @package
 */
trait RtbDaemonAwareTrait
{
    /**
     * @var RtbDaemonLegacy|RtbDaemonEvent|null
     */
    protected RtbDaemonLegacy|RtbDaemonEvent|null $rtbDaemon = null;

    /**
     * Called from
     * @return RtbDaemonLegacy|RtbDaemonEvent
     * @see \Sam\Rtb\Command\Concrete\Base\CommandBase::initByCommandContext
     * @see \Sam\Rtb\Server\ServerResponseSender::handleCommandResponse
     */
    public function getRtbDaemon(): RtbDaemonLegacy|RtbDaemonEvent
    {
        if (
            !$this->rtbDaemon instanceof RtbDaemonLegacy
            && !$this->rtbDaemon instanceof RtbDaemonEvent
        ) {
            throw new RuntimeException('RtbDaemon not defined' . composeSuffix(['class' => get_class($this->rtbDaemon)]));
        }
        return $this->rtbDaemon;
    }

    /**
     * @param RtbDaemonLegacy|RtbDaemonEvent $rtbDaemon
     * @return static
     */
    public function setRtbDaemon(RtbDaemonLegacy|RtbDaemonEvent $rtbDaemon): static
    {
        $this->rtbDaemon = $rtbDaemon;
        return $this;
    }
}
