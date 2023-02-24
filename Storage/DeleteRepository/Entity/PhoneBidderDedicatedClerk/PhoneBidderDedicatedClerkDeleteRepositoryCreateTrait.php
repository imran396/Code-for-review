<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\PhoneBidderDedicatedClerk;

trait PhoneBidderDedicatedClerkDeleteRepositoryCreateTrait
{
    protected ?PhoneBidderDedicatedClerkDeleteRepository $phoneBidderDedicatedClerkDeleteRepository = null;

    protected function createPhoneBidderDedicatedClerkDeleteRepository(): PhoneBidderDedicatedClerkDeleteRepository
    {
        return $this->phoneBidderDedicatedClerkDeleteRepository ?: PhoneBidderDedicatedClerkDeleteRepository::new();
    }

    /**
     * @param PhoneBidderDedicatedClerkDeleteRepository $phoneBidderDedicatedClerkDeleteRepository
     * @return static
     * @internal
     */
    public function setPhoneBidderDedicatedClerkDeleteRepository(PhoneBidderDedicatedClerkDeleteRepository $phoneBidderDedicatedClerkDeleteRepository): static
    {
        $this->phoneBidderDedicatedClerkDeleteRepository = $phoneBidderDedicatedClerkDeleteRepository;
        return $this;
    }
}
