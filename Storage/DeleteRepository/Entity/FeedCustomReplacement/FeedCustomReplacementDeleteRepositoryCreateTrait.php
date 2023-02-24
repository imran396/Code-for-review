<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\FeedCustomReplacement;

trait FeedCustomReplacementDeleteRepositoryCreateTrait
{
    protected ?FeedCustomReplacementDeleteRepository $feedCustomReplacementDeleteRepository = null;

    protected function createFeedCustomReplacementDeleteRepository(): FeedCustomReplacementDeleteRepository
    {
        return $this->feedCustomReplacementDeleteRepository ?: FeedCustomReplacementDeleteRepository::new();
    }

    /**
     * @param FeedCustomReplacementDeleteRepository $feedCustomReplacementDeleteRepository
     * @return static
     * @internal
     */
    public function setFeedCustomReplacementDeleteRepository(FeedCustomReplacementDeleteRepository $feedCustomReplacementDeleteRepository): static
    {
        $this->feedCustomReplacementDeleteRepository = $feedCustomReplacementDeleteRepository;
        return $this;
    }
}
