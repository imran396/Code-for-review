<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\FeedCustomReplacement;

trait FeedCustomReplacementReadRepositoryCreateTrait
{
    protected ?FeedCustomReplacementReadRepository $feedCustomReplacementReadRepository = null;

    protected function createFeedCustomReplacementReadRepository(): FeedCustomReplacementReadRepository
    {
        return $this->feedCustomReplacementReadRepository ?: FeedCustomReplacementReadRepository::new();
    }

    /**
     * @param FeedCustomReplacementReadRepository $feedCustomReplacementReadRepository
     * @return static
     * @internal
     */
    public function setFeedCustomReplacementReadRepository(FeedCustomReplacementReadRepository $feedCustomReplacementReadRepository): static
    {
        $this->feedCustomReplacementReadRepository = $feedCustomReplacementReadRepository;
        return $this;
    }
}
