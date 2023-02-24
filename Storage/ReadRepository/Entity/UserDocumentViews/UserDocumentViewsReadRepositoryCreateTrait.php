<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserDocumentViews;

trait UserDocumentViewsReadRepositoryCreateTrait
{
    protected ?UserDocumentViewsReadRepository $userDocumentViewsReadRepository = null;

    protected function createUserDocumentViewsReadRepository(): UserDocumentViewsReadRepository
    {
        return $this->userDocumentViewsReadRepository ?: UserDocumentViewsReadRepository::new();
    }

    /**
     * @param UserDocumentViewsReadRepository $userDocumentViewsReadRepository
     * @return static
     * @internal
     */
    public function setUserDocumentViewsReadRepository(UserDocumentViewsReadRepository $userDocumentViewsReadRepository): static
    {
        $this->userDocumentViewsReadRepository = $userDocumentViewsReadRepository;
        return $this;
    }
}
