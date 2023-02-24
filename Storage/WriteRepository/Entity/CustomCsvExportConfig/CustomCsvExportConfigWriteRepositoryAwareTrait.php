<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomCsvExportConfig;

trait CustomCsvExportConfigWriteRepositoryAwareTrait
{
    protected ?CustomCsvExportConfigWriteRepository $customCsvExportConfigWriteRepository = null;

    protected function getCustomCsvExportConfigWriteRepository(): CustomCsvExportConfigWriteRepository
    {
        if ($this->customCsvExportConfigWriteRepository === null) {
            $this->customCsvExportConfigWriteRepository = CustomCsvExportConfigWriteRepository::new();
        }
        return $this->customCsvExportConfigWriteRepository;
    }

    /**
     * @param CustomCsvExportConfigWriteRepository $customCsvExportConfigWriteRepository
     * @return static
     * @internal
     */
    public function setCustomCsvExportConfigWriteRepository(CustomCsvExportConfigWriteRepository $customCsvExportConfigWriteRepository): static
    {
        $this->customCsvExportConfigWriteRepository = $customCsvExportConfigWriteRepository;
        return $this;
    }
}
