<?php
/**
 * SAM-4328: Remote Image Import Manager
 *
 * @author        Igor Mironyak
 * @version       SVN: $Id: $
 * @since         Jun. 11, 2021
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Image\RemoteImport\Ftp;


/**
 * Trait FtpClientAwareTrait
 * @package Sam\Lot\Image\RemoteImport
 */
trait FtpClientAwareTrait
{
    /**
     * @var FtpClient|null
     */
    protected ?FtpClient $ftpClient = null;

    /**
     * @return FtpClient
     */
    protected function getFtpClient(): FtpClient
    {
        if ($this->ftpClient === null) {
            $this->ftpClient = FtpClient::new();
        }
        return $this->ftpClient;
    }

    /**
     * @param FtpClient $ftpClient
     * @return static
     * @internal
     */
    public function setFtpClient(FtpClient $ftpClient): static
    {
        $this->ftpClient = $ftpClient;
        return $this;
    }
}
