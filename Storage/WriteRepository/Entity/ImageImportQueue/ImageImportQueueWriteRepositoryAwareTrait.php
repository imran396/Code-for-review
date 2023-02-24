<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ImageImportQueue;

trait ImageImportQueueWriteRepositoryAwareTrait
{
    protected ?ImageImportQueueWriteRepository $imageImportQueueWriteRepository = null;

    protected function getImageImportQueueWriteRepository(): ImageImportQueueWriteRepository
    {
        if ($this->imageImportQueueWriteRepository === null) {
            $this->imageImportQueueWriteRepository = ImageImportQueueWriteRepository::new();
        }
        return $this->imageImportQueueWriteRepository;
    }

    /**
     * @param ImageImportQueueWriteRepository $imageImportQueueWriteRepository
     * @return static
     * @internal
     */
    public function setImageImportQueueWriteRepository(ImageImportQueueWriteRepository $imageImportQueueWriteRepository): static
    {
        $this->imageImportQueueWriteRepository = $imageImportQueueWriteRepository;
        return $this;
    }
}
