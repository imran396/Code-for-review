<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuditTrail;

trait AuditTrailReadRepositoryCreateTrait
{
    protected ?AuditTrailReadRepository $auditTrailReadRepository = null;

    protected function createAuditTrailReadRepository(): AuditTrailReadRepository
    {
        return $this->auditTrailReadRepository ?: AuditTrailReadRepository::new();
    }

    /**
     * @param AuditTrailReadRepository $auditTrailReadRepository
     * @return static
     * @internal
     */
    public function setAuditTrailReadRepository(AuditTrailReadRepository $auditTrailReadRepository): static
    {
        $this->auditTrailReadRepository = $auditTrailReadRepository;
        return $this;
    }
}
