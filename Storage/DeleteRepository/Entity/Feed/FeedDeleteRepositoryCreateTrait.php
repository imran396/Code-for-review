<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Feed;

trait FeedDeleteRepositoryCreateTrait
{
    protected ?FeedDeleteRepository $feedDeleteRepository = null;

    protected function createFeedDeleteRepository(): FeedDeleteRepository
    {
        return $this->feedDeleteRepository ?: FeedDeleteRepository::new();
    }

    /**
     * @param FeedDeleteRepository $feedDeleteRepository
     * @return static
     * @internal
     */
    public function setFeedDeleteRepository(FeedDeleteRepository $feedDeleteRepository): static
    {
        $this->feedDeleteRepository = $feedDeleteRepository;
        return $this;
    }
}
