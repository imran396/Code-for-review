<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ViewLanguage;

trait ViewLanguageReadRepositoryCreateTrait
{
    protected ?ViewLanguageReadRepository $viewLanguageReadRepository = null;

    protected function createViewLanguageReadRepository(): ViewLanguageReadRepository
    {
        return $this->viewLanguageReadRepository ?: ViewLanguageReadRepository::new();
    }

    /**
     * @param ViewLanguageReadRepository $viewLanguageReadRepository
     * @return static
     * @internal
     */
    public function setViewLanguageReadRepository(ViewLanguageReadRepository $viewLanguageReadRepository): static
    {
        $this->viewLanguageReadRepository = $viewLanguageReadRepository;
        return $this;
    }
}
