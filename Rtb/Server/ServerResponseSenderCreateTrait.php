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

namespace Sam\Rtb\Server;

/**
 * Trait ServerHelperCreateTrait
 * @package
 */
trait ServerResponseSenderCreateTrait
{
    /**
     * @var ServerResponseSender|null
     */
    protected ?ServerResponseSender $serverResponseSender = null;

    /**
     * @return ServerResponseSender
     */
    protected function createServerResponseSender(): ServerResponseSender
    {
        return $this->serverResponseSender ?: ServerResponseSender::new();
    }

    /**
     * @param ServerResponseSender $sender
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setServerResponseSender(ServerResponseSender $sender): static
    {
        $this->serverResponseSender = $sender;
        return $this;
    }
}
