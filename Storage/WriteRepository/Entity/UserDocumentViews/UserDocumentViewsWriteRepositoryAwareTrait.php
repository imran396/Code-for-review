<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserDocumentViews;

trait UserDocumentViewsWriteRepositoryAwareTrait
{
    protected ?UserDocumentViewsWriteRepository $userDocumentViewsWriteRepository = null;

    protected function getUserDocumentViewsWriteRepository(): UserDocumentViewsWriteRepository
    {
        if ($this->userDocumentViewsWriteRepository === null) {
            $this->userDocumentViewsWriteRepository = UserDocumentViewsWriteRepository::new();
        }
        return $this->userDocumentViewsWriteRepository;
    }

    /**
     * @param UserDocumentViewsWriteRepository $userDocumentViewsWriteRepository
     * @return static
     * @internal
     */
    public function setUserDocumentViewsWriteRepository(UserDocumentViewsWriteRepository $userDocumentViewsWriteRepository): static
    {
        $this->userDocumentViewsWriteRepository = $userDocumentViewsWriteRepository;
        return $this;
    }
}
