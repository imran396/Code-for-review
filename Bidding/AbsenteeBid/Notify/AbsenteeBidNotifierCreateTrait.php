<?php

namespace Sam\Bidding\AbsenteeBid\Notify;

/**
 * Trait AbsenteeBidNotifierCreateTrait
 * @package Sam\Bidding\AbsenteeBid\Notify
 */
trait AbsenteeBidNotifierCreateTrait
{
    protected ?AbsenteeBidNotifier $absenteeBidNotifier = null;

    /**
     * @return AbsenteeBidNotifier
     */
    protected function createAbsenteeBidNotifier(): AbsenteeBidNotifier
    {
        return $this->absenteeBidNotifier ?: AbsenteeBidNotifier::new();
    }

    /**
     * @param AbsenteeBidNotifier $absenteeBidNotifier
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAbsenteeBidNotifier(AbsenteeBidNotifier $absenteeBidNotifier): static
    {
        $this->absenteeBidNotifier = $absenteeBidNotifier;
        return $this;
    }
}
