<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\PhoneBidderDedicatedClerk;

trait PhoneBidderDedicatedClerkWriteRepositoryAwareTrait
{
    protected ?PhoneBidderDedicatedClerkWriteRepository $phoneBidderDedicatedClerkWriteRepository = null;

    protected function getPhoneBidderDedicatedClerkWriteRepository(): PhoneBidderDedicatedClerkWriteRepository
    {
        if ($this->phoneBidderDedicatedClerkWriteRepository === null) {
            $this->phoneBidderDedicatedClerkWriteRepository = PhoneBidderDedicatedClerkWriteRepository::new();
        }
        return $this->phoneBidderDedicatedClerkWriteRepository;
    }

    /**
     * @param PhoneBidderDedicatedClerkWriteRepository $phoneBidderDedicatedClerkWriteRepository
     * @return static
     * @internal
     */
    public function setPhoneBidderDedicatedClerkWriteRepository(PhoneBidderDedicatedClerkWriteRepository $phoneBidderDedicatedClerkWriteRepository): static
    {
        $this->phoneBidderDedicatedClerkWriteRepository = $phoneBidderDedicatedClerkWriteRepository;
        return $this;
    }
}
