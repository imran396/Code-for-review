<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CustomCsvExportData;

trait CustomCsvExportDataDeleteRepositoryCreateTrait
{
    protected ?CustomCsvExportDataDeleteRepository $customCsvExportDataDeleteRepository = null;

    protected function createCustomCsvExportDataDeleteRepository(): CustomCsvExportDataDeleteRepository
    {
        return $this->customCsvExportDataDeleteRepository ?: CustomCsvExportDataDeleteRepository::new();
    }

    /**
     * @param CustomCsvExportDataDeleteRepository $customCsvExportDataDeleteRepository
     * @return static
     * @internal
     */
    public function setCustomCsvExportDataDeleteRepository(CustomCsvExportDataDeleteRepository $customCsvExportDataDeleteRepository): static
    {
        $this->customCsvExportDataDeleteRepository = $customCsvExportDataDeleteRepository;
        return $this;
    }
}
