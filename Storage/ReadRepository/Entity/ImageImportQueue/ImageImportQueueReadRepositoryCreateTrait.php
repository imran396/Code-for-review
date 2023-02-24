<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ImageImportQueue;

trait ImageImportQueueReadRepositoryCreateTrait
{
    protected ?ImageImportQueueReadRepository $imageImportQueueReadRepository = null;

    protected function createImageImportQueueReadRepository(): ImageImportQueueReadRepository
    {
        return $this->imageImportQueueReadRepository ?: ImageImportQueueReadRepository::new();
    }

    /**
     * @param ImageImportQueueReadRepository $imageImportQueueReadRepository
     * @return static
     * @internal
     */
    public function setImageImportQueueReadRepository(ImageImportQueueReadRepository $imageImportQueueReadRepository): static
    {
        $this->imageImportQueueReadRepository = $imageImportQueueReadRepository;
        return $this;
    }
}
