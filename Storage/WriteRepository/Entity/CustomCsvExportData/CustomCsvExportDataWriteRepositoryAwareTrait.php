<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomCsvExportData;

trait CustomCsvExportDataWriteRepositoryAwareTrait
{
    protected ?CustomCsvExportDataWriteRepository $customCsvExportDataWriteRepository = null;

    protected function getCustomCsvExportDataWriteRepository(): CustomCsvExportDataWriteRepository
    {
        if ($this->customCsvExportDataWriteRepository === null) {
            $this->customCsvExportDataWriteRepository = CustomCsvExportDataWriteRepository::new();
        }
        return $this->customCsvExportDataWriteRepository;
    }

    /**
     * @param CustomCsvExportDataWriteRepository $customCsvExportDataWriteRepository
     * @return static
     * @internal
     */
    public function setCustomCsvExportDataWriteRepository(CustomCsvExportDataWriteRepository $customCsvExportDataWriteRepository): static
    {
        $this->customCsvExportDataWriteRepository = $customCsvExportDataWriteRepository;
        return $this;
    }
}
