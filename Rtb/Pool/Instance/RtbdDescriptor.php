<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Instance;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;

/**
 * Class PoolInstance
 * @package
 */
class RtbdDescriptor extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use UserTypeAwareTrait;

    private ?string $name = null;
    /** @var int[]|null */
    private ?array $includeAccountIds = null;
    /** @var int[]|null */
    private ?array $excludeAccountIds = null;
    private ?string $publicHost = null;
    private ?int $publicPort = null;
    private ?string $publicPath = null;
    private ?string $bindHost = null;
    private ?int $bindPort = null;
    private ?string $logFileName = null;
    private ?string $pidFileName = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @return bool
     */
    public function amongIncludeAccountIds(int $accountId): bool
    {
        if (
            $this->getIncludeAccountIds()
            && in_array($accountId, $this->getIncludeAccountIds())
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param int $accountId
     * @return bool
     */
    public function amongExcludeAccountIds(int $accountId): bool
    {
        if (
            $this->getExcludeAccountIds()
            && in_array($accountId, $this->getExcludeAccountIds())
        ) {
            return true;
        }
        return false;
    }

    /**
     * Means, we want to run auctions from all accounts
     * Empty array means "Include All"
     * @return bool
     */
    public function isIncludeAll(): bool
    {
        return empty($this->getIncludeAccountIds());
    }

    /**
     * Checks, descriptor has valid setting values to run instance
     * @return bool
     */
    public function isValid(): bool
    {
        if (trim($this->getName()) === '') {
            return false;
        }
        return true;
    }

    /**
     * Return descriptor fields as associative array
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'bindHost' => $this->getBindHost(),
            'bindPort' => $this->getBindPort(),
            'publicHost' => $this->getPublicHost(),
            'publicPort' => $this->getPublicPort(),
            'publicPath' => $this->getPublicPath(),
            'includeAccount' => $this->getIncludeAccountIds(),
            'excludeAccount' => $this->getExcludeAccountIds(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = trim($name);
        return $this;
    }

    /**
     * Empty array mean include all
     * @return int[]
     */
    public function getIncludeAccountIds(): array
    {
        return $this->includeAccountIds;
    }

    /**
     * Empty array means exclude nothing
     * @param int[] $includeAccountIds
     * @return static
     */
    public function setIncludeAccountIds(array $includeAccountIds): static
    {
        $this->includeAccountIds = ArrayCast::castInt($includeAccountIds, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int[]
     */
    public function getExcludeAccountIds(): array
    {
        return $this->excludeAccountIds;
    }

    /**
     * @param int[] $excludeAccountIds
     * @return static
     */
    public function setExcludeAccountIds(array $excludeAccountIds): static
    {
        $this->excludeAccountIds = ArrayCast::castInt($excludeAccountIds, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return string
     */
    public function getBindHost(): string
    {
        if ($this->bindHost === null) {
            $this->bindHost = $this->getPublicHost();
        }
        return $this->bindHost;
    }

    /**
     * @param string $bindHost
     * @return static
     */
    public function setBindHost(string $bindHost): static
    {
        $this->bindHost = trim($bindHost);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBindPort(): ?int
    {
        if ($this->bindPort === null) {
            $this->bindPort = $this->getPublicPort();
        }
        return $this->bindPort;
    }

    /**
     * @param int|null $bindPort
     * @return static
     */
    public function setBindPort(?int $bindPort): static
    {
        $this->bindPort = $bindPort;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicHost(): string
    {
        if ($this->publicHost === null) {
            $this->publicHost = $this->cfg()->get('core->app->httpHost');
        }
        return $this->publicHost;
    }

    /**
     * @param string $publicHost
     * @return static
     */
    public function setPublicHost(string $publicHost): static
    {
        $this->publicHost = trim($publicHost);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPublicPort(): ?int
    {
        return $this->publicPort;
    }

    /**
     * @param int|null $publicPort
     * @return static
     */
    public function setPublicPort(?int $publicPort): static
    {
        $this->publicPort = $publicPort;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicPath(): string
    {
        if ($this->publicPath === null) {
            $this->publicPath = $this->getRtbGeneralHelper()->makePublicPath('/', $this->getUserType());
        }
        return $this->publicPath;
    }

    /**
     * @param string $publicPath
     * @return static
     */
    public function setPublicPath(string $publicPath): static
    {
        $this->publicPath = $this->getRtbGeneralHelper()->makePublicPath($publicPath, $this->getUserType());
        return $this;
    }

    /**
     * @return string
     */
    public function getLogFileName(): string
    {
        if ($this->logFileName === null) {
            $this->logFileName = sprintf(Constants\RtbdPool::LOG_FILE_NAME_TPL, $this->getName());
        }
        return $this->logFileName;
    }

    /**
     * @param string $logFileName
     * @return static
     */
    public function setLogFileName(string $logFileName): static
    {
        $this->logFileName = trim($logFileName);
        return $this;
    }

    /**
     * @return string
     */
    public function getPidFileName(): string
    {
        if ($this->pidFileName === null) {
            $this->pidFileName = sprintf(Constants\RtbdPool::PID_FILE_NAME_TPL, $this->getName());
        }
        return $this->pidFileName;
    }

    /**
     * @param string $pidFileName
     * @return static
     */
    public function setPidFileName(string $pidFileName): static
    {
        $this->pidFileName = trim($pidFileName);
        return $this;
    }
}
