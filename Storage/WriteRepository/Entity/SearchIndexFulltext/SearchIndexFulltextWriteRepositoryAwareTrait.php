<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SearchIndexFulltext;

trait SearchIndexFulltextWriteRepositoryAwareTrait
{
    protected ?SearchIndexFulltextWriteRepository $searchIndexFulltextWriteRepository = null;

    protected function getSearchIndexFulltextWriteRepository(): SearchIndexFulltextWriteRepository
    {
        if ($this->searchIndexFulltextWriteRepository === null) {
            $this->searchIndexFulltextWriteRepository = SearchIndexFulltextWriteRepository::new();
        }
        return $this->searchIndexFulltextWriteRepository;
    }

    /**
     * @param SearchIndexFulltextWriteRepository $searchIndexFulltextWriteRepository
     * @return static
     * @internal
     */
    public function setSearchIndexFulltextWriteRepository(SearchIndexFulltextWriteRepository $searchIndexFulltextWriteRepository): static
    {
        $this->searchIndexFulltextWriteRepository = $searchIndexFulltextWriteRepository;
        return $this;
    }
}
