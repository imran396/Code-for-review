<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ImageImportQueue;

trait ImageImportQueueDeleteRepositoryCreateTrait
{
    protected ?ImageImportQueueDeleteRepository $imageImportQueueDeleteRepository = null;

    protected function createImageImportQueueDeleteRepository(): ImageImportQueueDeleteRepository
    {
        return $this->imageImportQueueDeleteRepository ?: ImageImportQueueDeleteRepository::new();
    }

    /**
     * @param ImageImportQueueDeleteRepository $imageImportQueueDeleteRepository
     * @return static
     * @internal
     */
    public function setImageImportQueueDeleteRepository(ImageImportQueueDeleteRepository $imageImportQueueDeleteRepository): static
    {
        $this->imageImportQueueDeleteRepository = $imageImportQueueDeleteRepository;
        return $this;
    }
}
