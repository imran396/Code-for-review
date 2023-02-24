<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuditTrail;

trait AuditTrailDeleteRepositoryCreateTrait
{
    protected ?AuditTrailDeleteRepository $auditTrailDeleteRepository = null;

    protected function createAuditTrailDeleteRepository(): AuditTrailDeleteRepository
    {
        return $this->auditTrailDeleteRepository ?: AuditTrailDeleteRepository::new();
    }

    /**
     * @param AuditTrailDeleteRepository $auditTrailDeleteRepository
     * @return static
     * @internal
     */
    public function setAuditTrailDeleteRepository(AuditTrailDeleteRepository $auditTrailDeleteRepository): static
    {
        $this->auditTrailDeleteRepository = $auditTrailDeleteRepository;
        return $this;
    }
}
