<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ReportImageImport;

trait ReportImageImportDeleteRepositoryCreateTrait
{
    protected ?ReportImageImportDeleteRepository $reportImageImportDeleteRepository = null;

    protected function createReportImageImportDeleteRepository(): ReportImageImportDeleteRepository
    {
        return $this->reportImageImportDeleteRepository ?: ReportImageImportDeleteRepository::new();
    }

    /**
     * @param ReportImageImportDeleteRepository $reportImageImportDeleteRepository
     * @return static
     * @internal
     */
    public function setReportImageImportDeleteRepository(ReportImageImportDeleteRepository $reportImageImportDeleteRepository): static
    {
        $this->reportImageImportDeleteRepository = $reportImageImportDeleteRepository;
        return $this;
    }
}
