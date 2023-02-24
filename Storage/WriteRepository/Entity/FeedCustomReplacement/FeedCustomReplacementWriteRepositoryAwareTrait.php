<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\FeedCustomReplacement;

trait FeedCustomReplacementWriteRepositoryAwareTrait
{
    protected ?FeedCustomReplacementWriteRepository $feedCustomReplacementWriteRepository = null;

    protected function getFeedCustomReplacementWriteRepository(): FeedCustomReplacementWriteRepository
    {
        if ($this->feedCustomReplacementWriteRepository === null) {
            $this->feedCustomReplacementWriteRepository = FeedCustomReplacementWriteRepository::new();
        }
        return $this->feedCustomReplacementWriteRepository;
    }

    /**
     * @param FeedCustomReplacementWriteRepository $feedCustomReplacementWriteRepository
     * @return static
     * @internal
     */
    public function setFeedCustomReplacementWriteRepository(FeedCustomReplacementWriteRepository $feedCustomReplacementWriteRepository): static
    {
        $this->feedCustomReplacementWriteRepository = $feedCustomReplacementWriteRepository;
        return $this;
    }
}
