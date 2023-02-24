<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotImageInBucket;

trait LotImageInBucketReadRepositoryCreateTrait
{
    protected ?LotImageInBucketReadRepository $lotImageInBucketReadRepository = null;

    protected function createLotImageInBucketReadRepository(): LotImageInBucketReadRepository
    {
        return $this->lotImageInBucketReadRepository ?: LotImageInBucketReadRepository::new();
    }

    /**
     * @param LotImageInBucketReadRepository $lotImageInBucketReadRepository
     * @return static
     * @internal
     */
    public function setLotImageInBucketReadRepository(LotImageInBucketReadRepository $lotImageInBucketReadRepository): static
    {
        $this->lotImageInBucketReadRepository = $lotImageInBucketReadRepository;
        return $this;
    }
}
