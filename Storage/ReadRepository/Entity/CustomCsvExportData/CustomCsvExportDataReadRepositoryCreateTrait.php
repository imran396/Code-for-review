<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CustomCsvExportData;

trait CustomCsvExportDataReadRepositoryCreateTrait
{
    protected ?CustomCsvExportDataReadRepository $customCsvExportDataReadRepository = null;

    protected function createCustomCsvExportDataReadRepository(): CustomCsvExportDataReadRepository
    {
        return $this->customCsvExportDataReadRepository ?: CustomCsvExportDataReadRepository::new();
    }

    /**
     * @param CustomCsvExportDataReadRepository $customCsvExportDataReadRepository
     * @return static
     * @internal
     */
    public function setCustomCsvExportDataReadRepository(CustomCsvExportDataReadRepository $customCsvExportDataReadRepository): static
    {
        $this->customCsvExportDataReadRepository = $customCsvExportDataReadRepository;
        return $this;
    }
}
