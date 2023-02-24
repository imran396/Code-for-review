<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ReportImageImport;

trait ReportImageImportReadRepositoryCreateTrait
{
    protected ?ReportImageImportReadRepository $reportImageImportReadRepository = null;

    protected function createReportImageImportReadRepository(): ReportImageImportReadRepository
    {
        return $this->reportImageImportReadRepository ?: ReportImageImportReadRepository::new();
    }

    /**
     * @param ReportImageImportReadRepository $reportImageImportReadRepository
     * @return static
     * @internal
     */
    public function setReportImageImportReadRepository(ReportImageImportReadRepository $reportImageImportReadRepository): static
    {
        $this->reportImageImportReadRepository = $reportImageImportReadRepository;
        return $this;
    }
}
