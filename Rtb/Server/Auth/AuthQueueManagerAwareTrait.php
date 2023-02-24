<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Auth;

/**
 * Trait AuthQueueManagerAwareTrait
 * @package Sam\Rtb\Server\Auth
 */
trait AuthQueueManagerAwareTrait
{
    /**
     * @var AuthQueueManager|null
     */
    protected ?AuthQueueManager $authQueueManager = null;

    /**
     * @return AuthQueueManager
     */
    protected function getAuthQueueManager(): AuthQueueManager
    {
        if ($this->authQueueManager === null) {
            $this->authQueueManager = AuthQueueManager::new();
        }
        return $this->authQueueManager;
    }

    /**
     * @param AuthQueueManager $authQueueManager
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuthQueueManager(AuthQueueManager $authQueueManager): static
    {
        $this->authQueueManager = $authQueueManager;
        return $this;
    }
}
