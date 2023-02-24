<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SearchIndexFulltext;

trait SearchIndexFulltextDeleteRepositoryCreateTrait
{
    protected ?SearchIndexFulltextDeleteRepository $searchIndexFulltextDeleteRepository = null;

    protected function createSearchIndexFulltextDeleteRepository(): SearchIndexFulltextDeleteRepository
    {
        return $this->searchIndexFulltextDeleteRepository ?: SearchIndexFulltextDeleteRepository::new();
    }

    /**
     * @param SearchIndexFulltextDeleteRepository $searchIndexFulltextDeleteRepository
     * @return static
     * @internal
     */
    public function setSearchIndexFulltextDeleteRepository(SearchIndexFulltextDeleteRepository $searchIndexFulltextDeleteRepository): static
    {
        $this->searchIndexFulltextDeleteRepository = $searchIndexFulltextDeleteRepository;
        return $this;
    }
}
