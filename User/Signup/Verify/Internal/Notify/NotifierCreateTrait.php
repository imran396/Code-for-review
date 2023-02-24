<?php

namespace Sam\User\Signup\Verify\Internal\Notify;

/**
 * Trait NotifierCreateTrait
 * @package Sam\User\Signup\Verify\Internal\Notify
 */
trait NotifierCreateTrait
{
    protected ?Notifier $notifier = null;

    /**
     * @return Notifier
     */
    protected function createNotifier(): Notifier
    {
        return $this->notifier ?: Notifier::new();
    }

    /**
     * @param Notifier $notifier
     * @return $this
     * @internal
     */
    public function setNotifier(Notifier $notifier): static
    {
        $this->notifier = $notifier;
        return $this;
    }
}
