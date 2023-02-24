<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Feed;

trait FeedWriteRepositoryAwareTrait
{
    protected ?FeedWriteRepository $feedWriteRepository = null;

    protected function getFeedWriteRepository(): FeedWriteRepository
    {
        if ($this->feedWriteRepository === null) {
            $this->feedWriteRepository = FeedWriteRepository::new();
        }
        return $this->feedWriteRepository;
    }

    /**
     * @param FeedWriteRepository $feedWriteRepository
     * @return static
     * @internal
     */
    public function setFeedWriteRepository(FeedWriteRepository $feedWriteRepository): static
    {
        $this->feedWriteRepository = $feedWriteRepository;
        return $this;
    }
}
