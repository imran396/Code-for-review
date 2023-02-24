<?php
/**
 * SAM-10383: Refactor remote image import for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\RemoteImport\Ftp;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class FtpClientResult
 * @package Sam\Lot\Image\RemoteImport
 */
class FtpClientResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_CANNOT_CONNECT = 1;
    public const ERR_CANNOT_LOGIN = 2;
    public const ERR_CANNOT_CHANGE_DIRECTORY = 3;
    public const ERR_CANNOT_TURN_ON_PASSIVE_MODE = 4;
    public const ERR_CANNOT_READ_DIRECTORY = 5;
    public const ERR_CANNOT_DOWNLOAD_FILE = 6;

    protected const ERROR_MESSAGES = [
        self::ERR_CANNOT_CONNECT => 'Cannot connect to server',
        self::ERR_CANNOT_LOGIN => 'Login authentication failed',
        self::ERR_CANNOT_CHANGE_DIRECTORY => 'Cannot change directory',
        self::ERR_CANNOT_TURN_ON_PASSIVE_MODE => 'Cannot turn on passive mode',
        self::ERR_CANNOT_READ_DIRECTORY => 'Failed to retrieve directory listing',
        self::ERR_CANNOT_DOWNLOAD_FILE => 'Failed to download file',
    ];

    /**
     * Infrastructural message produced by php function of ftp standard library.
     * @var string
     */
    public string $ftpErrorMessage = '';

    /**
     * List of files in the given directory.
     * @var array
     */
    public array $fileNames = [];
    /**
     * Remote file name assigned, when it cannot be downloaded.
     * @var string
     */
    public string $remoteFile = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code, string $ftpErrorMessage): static
    {
        $this->getResultStatusCollector()->addError($code);
        $this->ftpErrorMessage = $ftpErrorMessage;
        return $this;
    }

    // --- Query ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function logMessage(): string
    {
        $output = $this->errorMessage();
        if ($this->ftpErrorMessage) {
            $output .= ' - ' . $this->ftpErrorMessage;
        }
        if ($this->getResultStatusCollector()->hasConcreteError(self::ERR_CANNOT_DOWNLOAD_FILE)) {
            $output .= composeSuffix(['remote file' => $this->remoteFile]);
        }
        return $output;
    }
}
