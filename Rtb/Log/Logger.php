<?php
/**
 * Auction trail logger
 */

namespace Sam\Rtb\Log;

use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use Sam\Report\Base\Csv\ReportToolAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;

/**
 * Class Logger
 * @package Sam\Rtb
 */
class Logger extends CustomizableClass
{
    use AuctionAwareTrait;
    use ReportToolAwareTrait;
    use UserAwareTrait;

    protected ?string $rootPath = null;
    protected string $remoteAddress = '';
    protected ?int $remotePort = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $message
     */
    public function log(string $message): void
    {
        if (!$this->getAuctionId()) {
            throw new RuntimeException("Auction Id not set");
        }

        $csvRow = [
            date(Constants\Date::ISO),
            "UTC",
            $this->remoteAddress,
            $this->remotePort,
            $this->getUserId(),
            $message
        ];
        $csvRow = $this->getReportTool()->prepareValues($csvRow, 'UTF-8');
        $csvLine = $this->getReportTool()->rowToLine($csvRow);
        $fileRootPath = $this->getRootPath() . '/rtb-' . $this->getAuctionId() . '.log';
        $filePath = substr($fileRootPath, strlen(path()->sysRoot()));
        try {
            LocalFileManager::new()->append($csvLine, $filePath);
        } catch (FileException) {
            log_error(composeLogData(["Rtbd cannot append data to log file" => $filePath, "data" => $csvLine]));
        }
        // Log to regular log too
        log_info($message);
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        if ($this->rootPath === null) {
            $this->rootPath = path()->docRoot() . '/lot-info';
        }
        return $this->rootPath;
    }

    /**
     * @param string $rootPath
     * @return static
     * @noinspection PhpUnused
     */
    public function setRootPath(string $rootPath): static
    {
        $this->rootPath = trim($rootPath);
        return $this;
    }

    /**
     * @param string $remoteAddress
     * @return static
     * @noinspection PhpUnused
     */
    public function setRemoteAddress(string $remoteAddress): static
    {
        $this->remoteAddress = trim($remoteAddress);
        return $this;
    }

    /**
     * @param int $remotePort
     * @return static
     * @noinspection PhpUnused
     */
    public function setRemotePort(int $remotePort): static
    {
        $this->remotePort = $remotePort;
        return $this;
    }

    /**
     * @param int $userType
     * @param string $userInfo
     * @return string
     */
    public function getUserRoleName(int $userType, string $userInfo = ''): string
    {
        $roleName = null;
        if ($userInfo) {
            $userInfo = ' ' . $userInfo;
        }
        if ($this->getUserId()) {
            $userInfo .= ' (' . $this->getUserId() . ')';
        }
        if ($userType === Constants\Rtb::UT_CLERK) {
            $roleName = 'Admin clerk' . $userInfo;
        } elseif ($userType === Constants\Rtb::UT_AUCTIONEER) {
            $roleName = 'Auctioneer' . $userInfo;
        } elseif ($userType === Constants\Rtb::UT_BIDDER) {
            $roleName = 'Bidder' . $userInfo;
        } elseif (in_array($userType, [Constants\Rtb::UT_VIEWER, Constants\Rtb::UT_PROJECTOR], true)) {
            $roleName = 'Viewer' . $userInfo;
        } elseif ($userType === Constants\Rtb::UT_SYSTEM) {
            $roleName = 'Rtb daemon' . $userInfo;
        }
        return $roleName;
    }
}
