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

use DateTime;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuditTrail\AuditTrailWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Log event in \AuditTrail using $this->log()
 * Class AuditTrailLogger
 * @package Sam\AuditTrail
 */
class AuditTrailLogger extends CustomizableClass
{
    use AuditTrailWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use ServerRequestReaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Account id of the event
     * @var int|null
     */
    protected ?int $accountId = null;

    /**
     * Brief description of the event. Only relevant information, more relevant info to the beginning of the message.
     * Max 255 characters
     * @var string|null
     */
    protected ?string $event = null;

    /**
     * For example the user.id of the authorized API user
     * @var int|null
     */
    protected ?int $proxyUserId = null;

    /**
     * Remote IP address
     * @var string|null
     */
    protected ?string $remoteIp = null;

    /**
     * Remote Port
     * @var int|null
     */
    protected ?int $remotePort = null;

    /**
     * Section of the site usually {'admin/'}controller/action
     * @var string|null
     */
    protected ?string $section = null;

    /**
     * user.id who triggers the event
     * @var int|null
     */
    protected ?int $userId = null;

    /**
     * @var int|null
     */
    protected ?int $editorUserId = null;

    /**
     * Log event in \AuditTrail
     */
    public function log(): void
    {
        $auditTrail = $this->createEntityFactory()->auditTrail();
        $floatTs = microtime(true);
        $integerTs = (int)$floatTs;
        $ms = round($floatTs - $integerTs, 3) * 1000;
        $auditTrail->AccountId = $this->getAccountId();
        $auditTrail->Event = $this->getEvent();
        $auditTrail->Ip = ip2long($this->getRemoteIp());
        $auditTrail->Ms = (int)$ms;
        $auditTrail->Port = $this->getRemotePort();
        $auditTrail->ProxyUserId = $this->getProxyUserId();
        $auditTrail->Section = $this->getSection();
        $auditTrail->Timestamp = (new DateTime())->setTimestamp($integerTs);
        $auditTrail->UserId = $this->userId;
        $this->getAuditTrailWriteRepository()->saveWithModifier($auditTrail, $this->detectModifierUserId());
    }

    /**
     * Get account id of the event
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * Set account id of the event
     */
    public function setAccountId(?int $accountId): static
    {
        $this->accountId = $accountId > 0 ? $accountId : null;
        return $this;
    }

    /**
     * Get Brief description of the event.
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * Set Brief description of the event.
     */
    public function setEvent(string $event): static
    {
        $event = $this->normalizeString(trim($event));
        $this->event = $event;
        return $this;
    }

    /**
     * Get proxy user Id.
     */
    public function getProxyUserId(): ?int
    {
        return $this->proxyUserId;
    }

    /**
     * Set proxy user Id.
     * @noinspection PhpUnused
     */
    public function setProxyUserId(?int $proxyUserId): static
    {
        $this->proxyUserId = $proxyUserId > 0 ? $proxyUserId : null;
        return $this;
    }

    /**
     * Get remote IP address
     */
    public function getRemoteIp(): string
    {
        if ($this->remoteIp === null) {
            $this->remoteIp = $this->getServerRequestReader()->remoteAddr();
        }
        return $this->remoteIp;
    }

    /**
     * Set remote IP address
     * @noinspection PhpUnused
     */
    public function setRemoteIp(string $remoteIp): static
    {
        $this->remoteIp = trim($remoteIp);
        return $this;
    }

    /**
     * Get remote port
     */
    public function getRemotePort(): ?int
    {
        if ($this->remotePort === null) {
            $this->remotePort = $this->getServerRequestReader()->remotePort();
        }
        return $this->remotePort;
    }

    /**
     * Set remote port
     * @noinspection PhpUnused
     */
    public function setRemotePort(?int $remotePort): static
    {
        $this->remotePort = $remotePort > 0 ? $remotePort : null;
        return $this;
    }

    /**
     * Get section of the site
     */
    public function getSection(): ?string
    {
        return $this->section;
    }

    /**
     * Set section of the site
     */
    public function setSection(string $section): static
    {
        $this->section = trim($section);
        return $this;
    }

    /**
     * Set user.id who triggers the event
     */
    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * Return entity modifier user - he is either authorized user, or system user when the current user is anonymous or not defined.
     */
    protected function detectModifierUserId(): int
    {
        if ($this->editorUserId) {
            return $this->editorUserId;
        }

        if ($this->userId) {
            return $this->userId;
        }

        return $this->getUserLoader()->loadSystemUserId();
    }

    /**
     * Adjust string length up to 255 characters length.
     */
    protected function normalizeString(string $string): string
    {
        $output = $string;
        if (strlen($string) > 255) {
            $output = substr($string, 0, 254);
        }
        return $output;
    }

}
