<?php
/**
 * SAM-4917: Audit Trail module refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-10, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuditTrail;

/**
 * Trait AuditTrailLoggerAwareTrait
 * @package Sam\AuditTrail
 */
trait AuditTrailLoggerAwareTrait
{
    /**
     * @var AuditTrailLogger|null
     */
    protected ?AuditTrailLogger $auditTrailLogger = null;

    protected function getAuditTrailLogger(): AuditTrailLogger
    {
        if ($this->auditTrailLogger === null) {
            $this->auditTrailLogger = AuditTrailLogger::new();
        }
        return $this->auditTrailLogger;
    }

    /**
     * @internal
     */
    public function setAuditTrailLogger(AuditTrailLogger $auditTrailLogger): static
    {
        $this->auditTrailLogger = $auditTrailLogger;
        return $this;
    }
}
