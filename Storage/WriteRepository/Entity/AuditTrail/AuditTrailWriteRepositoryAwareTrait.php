<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuditTrail;

trait AuditTrailWriteRepositoryAwareTrait
{
    protected ?AuditTrailWriteRepository $auditTrailWriteRepository = null;

    protected function getAuditTrailWriteRepository(): AuditTrailWriteRepository
    {
        if ($this->auditTrailWriteRepository === null) {
            $this->auditTrailWriteRepository = AuditTrailWriteRepository::new();
        }
        return $this->auditTrailWriteRepository;
    }

    /**
     * @param AuditTrailWriteRepository $auditTrailWriteRepository
     * @return static
     * @internal
     */
    public function setAuditTrailWriteRepository(AuditTrailWriteRepository $auditTrailWriteRepository): static
    {
        $this->auditTrailWriteRepository = $auditTrailWriteRepository;
        return $this;
    }
}
