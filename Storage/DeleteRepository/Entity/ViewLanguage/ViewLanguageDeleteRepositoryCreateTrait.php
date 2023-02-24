<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ViewLanguage;

trait ViewLanguageDeleteRepositoryCreateTrait
{
    protected ?ViewLanguageDeleteRepository $viewLanguageDeleteRepository = null;

    protected function createViewLanguageDeleteRepository(): ViewLanguageDeleteRepository
    {
        return $this->viewLanguageDeleteRepository ?: ViewLanguageDeleteRepository::new();
    }

    /**
     * @param ViewLanguageDeleteRepository $viewLanguageDeleteRepository
     * @return static
     * @internal
     */
    public function setViewLanguageDeleteRepository(ViewLanguageDeleteRepository $viewLanguageDeleteRepository): static
    {
        $this->viewLanguageDeleteRepository = $viewLanguageDeleteRepository;
        return $this;
    }
}
