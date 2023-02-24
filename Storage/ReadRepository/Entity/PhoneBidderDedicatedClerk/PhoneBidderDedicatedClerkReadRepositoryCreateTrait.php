<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\PhoneBidderDedicatedClerk;

trait PhoneBidderDedicatedClerkReadRepositoryCreateTrait
{
    protected ?PhoneBidderDedicatedClerkReadRepository $phoneBidderDedicatedClerkReadRepository = null;

    protected function createPhoneBidderDedicatedClerkReadRepository(): PhoneBidderDedicatedClerkReadRepository
    {
        return $this->phoneBidderDedicatedClerkReadRepository ?: PhoneBidderDedicatedClerkReadRepository::new();
    }

    /**
     * @param PhoneBidderDedicatedClerkReadRepository $phoneBidderDedicatedClerkReadRepository
     * @return static
     * @internal
     */
    public function setPhoneBidderDedicatedClerkReadRepository(PhoneBidderDedicatedClerkReadRepository $phoneBidderDedicatedClerkReadRepository): static
    {
        $this->phoneBidderDedicatedClerkReadRepository = $phoneBidderDedicatedClerkReadRepository;
        return $this;
    }
}
