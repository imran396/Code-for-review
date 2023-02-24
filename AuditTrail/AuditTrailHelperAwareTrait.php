<?php

namespace Sam\AuditTrail;

/**
 * Trait AuditTrailHelperAwareTrait
 * @package Sam\AuditTrail
 */
trait AuditTrailHelperAwareTrait
{
    protected ?AuditTrailHelper $auditTrailHelper = null;

    protected function getAuditTrailHelper(): AuditTrailHelper
    {
        if ($this->auditTrailHelper === null) {
            $this->auditTrailHelper = AuditTrailHelper::new();
        }
        return $this->auditTrailHelper;
    }

    /**
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuditTrailHelper(AuditTrailHelper $auditTrailHelper): static
    {
        $this->auditTrailHelper = $auditTrailHelper;
        return $this;
    }
}
