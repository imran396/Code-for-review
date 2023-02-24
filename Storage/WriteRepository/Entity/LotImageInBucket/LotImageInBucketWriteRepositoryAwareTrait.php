<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotImageInBucket;

trait LotImageInBucketWriteRepositoryAwareTrait
{
    protected ?LotImageInBucketWriteRepository $lotImageInBucketWriteRepository = null;

    protected function getLotImageInBucketWriteRepository(): LotImageInBucketWriteRepository
    {
        if ($this->lotImageInBucketWriteRepository === null) {
            $this->lotImageInBucketWriteRepository = LotImageInBucketWriteRepository::new();
        }
        return $this->lotImageInBucketWriteRepository;
    }

    /**
     * @param LotImageInBucketWriteRepository $lotImageInBucketWriteRepository
     * @return static
     * @internal
     */
    public function setLotImageInBucketWriteRepository(LotImageInBucketWriteRepository $lotImageInBucketWriteRepository): static
    {
        $this->lotImageInBucketWriteRepository = $lotImageInBucketWriteRepository;
        return $this;
    }
}
