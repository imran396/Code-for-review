<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CustomCsvExportConfig;

trait CustomCsvExportConfigReadRepositoryCreateTrait
{
    protected ?CustomCsvExportConfigReadRepository $customCsvExportConfigReadRepository = null;

    protected function createCustomCsvExportConfigReadRepository(): CustomCsvExportConfigReadRepository
    {
        return $this->customCsvExportConfigReadRepository ?: CustomCsvExportConfigReadRepository::new();
    }

    /**
     * @param CustomCsvExportConfigReadRepository $customCsvExportConfigReadRepository
     * @return static
     * @internal
     */
    public function setCustomCsvExportConfigReadRepository(CustomCsvExportConfigReadRepository $customCsvExportConfigReadRepository): static
    {
        $this->customCsvExportConfigReadRepository = $customCsvExportConfigReadRepository;
        return $this;
    }
}
