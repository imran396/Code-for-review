<?php

namespace Sam\Rtb\Session;

/**
 * Trait RtbSessionManagerCreateTrait
 * @package Sam\Rtb\Session
 */
trait RtbSessionManagerCreateTrait
{
    /**
     * @var RtbSessionManager|null
     */
    protected ?RtbSessionManager $rtbSessionManager = null;

    /**
     * @return RtbSessionManager
     */
    protected function createRtbSessionManager(): RtbSessionManager
    {
        return $this->rtbSessionManager ?: RtbSessionManager::new();
    }

    /**
     * @param RtbSessionManager $rtbSessionManager
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setRtbSessionManager(RtbSessionManager $rtbSessionManager): static
    {
        $this->rtbSessionManager = $rtbSessionManager;
        return $this;
    }
}
