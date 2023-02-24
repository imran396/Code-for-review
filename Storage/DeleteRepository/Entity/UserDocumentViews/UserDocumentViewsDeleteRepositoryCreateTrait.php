<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserDocumentViews;

trait UserDocumentViewsDeleteRepositoryCreateTrait
{
    protected ?UserDocumentViewsDeleteRepository $userDocumentViewsDeleteRepository = null;

    protected function createUserDocumentViewsDeleteRepository(): UserDocumentViewsDeleteRepository
    {
        return $this->userDocumentViewsDeleteRepository ?: UserDocumentViewsDeleteRepository::new();
    }

    /**
     * @param UserDocumentViewsDeleteRepository $userDocumentViewsDeleteRepository
     * @return static
     * @internal
     */
    public function setUserDocumentViewsDeleteRepository(UserDocumentViewsDeleteRepository $userDocumentViewsDeleteRepository): static
    {
        $this->userDocumentViewsDeleteRepository = $userDocumentViewsDeleteRepository;
        return $this;
    }
}
