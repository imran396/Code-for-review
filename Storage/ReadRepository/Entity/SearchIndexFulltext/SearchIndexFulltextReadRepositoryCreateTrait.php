<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SearchIndexFulltext;

trait SearchIndexFulltextReadRepositoryCreateTrait
{
    protected ?SearchIndexFulltextReadRepository $searchIndexFulltextReadRepository = null;

    protected function createSearchIndexFulltextReadRepository(): SearchIndexFulltextReadRepository
    {
        return $this->searchIndexFulltextReadRepository ?: SearchIndexFulltextReadRepository::new();
    }

    /**
     * @param SearchIndexFulltextReadRepository $searchIndexFulltextReadRepository
     * @return static
     * @internal
     */
    public function setSearchIndexFulltextReadRepository(SearchIndexFulltextReadRepository $searchIndexFulltextReadRepository): static
    {
        $this->searchIndexFulltextReadRepository = $searchIndexFulltextReadRepository;
        return $this;
    }
}
