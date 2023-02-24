<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Feed;

trait FeedReadRepositoryCreateTrait
{
    protected ?FeedReadRepository $feedReadRepository = null;

    protected function createFeedReadRepository(): FeedReadRepository
    {
        return $this->feedReadRepository ?: FeedReadRepository::new();
    }

    /**
     * @param FeedReadRepository $feedReadRepository
     * @return static
     * @internal
     */
    public function setFeedReadRepository(FeedReadRepository $feedReadRepository): static
    {
        $this->feedReadRepository = $feedReadRepository;
        return $this;
    }
}
