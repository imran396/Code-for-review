<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotImageInBucket;

trait LotImageInBucketDeleteRepositoryCreateTrait
{
    protected ?LotImageInBucketDeleteRepository $lotImageInBucketDeleteRepository = null;

    protected function createLotImageInBucketDeleteRepository(): LotImageInBucketDeleteRepository
    {
        return $this->lotImageInBucketDeleteRepository ?: LotImageInBucketDeleteRepository::new();
    }

    /**
     * @param LotImageInBucketDeleteRepository $lotImageInBucketDeleteRepository
     * @return static
     * @internal
     */
    public function setLotImageInBucketDeleteRepository(LotImageInBucketDeleteRepository $lotImageInBucketDeleteRepository): static
    {
        $this->lotImageInBucketDeleteRepository = $lotImageInBucketDeleteRepository;
        return $this;
    }
}
