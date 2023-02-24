<?php

namespace Sam\Bidder\PreferredPrivilege;

/**
 * Trait RevokerAwareTrait
 * @package Sam\Bidder\PreferredPrivilege
 */
trait RevokerAwareTrait
{
    /**
     * @var Revoker|null
     */
    protected ?Revoker $revoker = null;

    /**
     * @return Revoker
     */
    protected function getRevoker(): Revoker
    {
        if ($this->revoker === null) {
            $this->revoker = Revoker::new();
        }
        return $this->revoker;
    }

    /**
     * @param Revoker $revoker
     * @return static
     */
    public function setRevoker(Revoker $revoker): static
    {
        $this->revoker = $revoker;
        return $this;
    }
}
