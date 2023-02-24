<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ViewLanguage;

trait ViewLanguageWriteRepositoryAwareTrait
{
    protected ?ViewLanguageWriteRepository $viewLanguageWriteRepository = null;

    protected function getViewLanguageWriteRepository(): ViewLanguageWriteRepository
    {
        if ($this->viewLanguageWriteRepository === null) {
            $this->viewLanguageWriteRepository = ViewLanguageWriteRepository::new();
        }
        return $this->viewLanguageWriteRepository;
    }

    /**
     * @param ViewLanguageWriteRepository $viewLanguageWriteRepository
     * @return static
     * @internal
     */
    public function setViewLanguageWriteRepository(ViewLanguageWriteRepository $viewLanguageWriteRepository): static
    {
        $this->viewLanguageWriteRepository = $viewLanguageWriteRepository;
        return $this;
    }
}
