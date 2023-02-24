<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CustomCsvExportConfig;

trait CustomCsvExportConfigDeleteRepositoryCreateTrait
{
    protected ?CustomCsvExportConfigDeleteRepository $customCsvExportConfigDeleteRepository = null;

    protected function createCustomCsvExportConfigDeleteRepository(): CustomCsvExportConfigDeleteRepository
    {
        return $this->customCsvExportConfigDeleteRepository ?: CustomCsvExportConfigDeleteRepository::new();
    }

    /**
     * @param CustomCsvExportConfigDeleteRepository $customCsvExportConfigDeleteRepository
     * @return static
     * @internal
     */
    public function setCustomCsvExportConfigDeleteRepository(CustomCsvExportConfigDeleteRepository $customCsvExportConfigDeleteRepository): static
    {
        $this->customCsvExportConfigDeleteRepository = $customCsvExportConfigDeleteRepository;
        return $this;
    }
}
