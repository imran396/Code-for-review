<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ReportImageImport;

trait ReportImageImportWriteRepositoryAwareTrait
{
    protected ?ReportImageImportWriteRepository $reportImageImportWriteRepository = null;

    protected function getReportImageImportWriteRepository(): ReportImageImportWriteRepository
    {
        if ($this->reportImageImportWriteRepository === null) {
            $this->reportImageImportWriteRepository = ReportImageImportWriteRepository::new();
        }
        return $this->reportImageImportWriteRepository;
    }

    /**
     * @param ReportImageImportWriteRepository $reportImageImportWriteRepository
     * @return static
     * @internal
     */
    public function setReportImageImportWriteRepository(ReportImageImportWriteRepository $reportImageImportWriteRepository): static
    {
        $this->reportImageImportWriteRepository = $reportImageImportWriteRepository;
        return $this;
    }
}
