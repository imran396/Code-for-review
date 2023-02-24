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

/**
 * Trait FtpClientResultTranslatorCreateTrait
 * @package Sam\Lot\Image\RemoteImport
 */
trait FtpClientResultTranslatorCreateTrait
{
    protected ?FtpClientResultTranslator $ftpClientResultTranslator = null;

    /**
     * @return FtpClientResultTranslator
     */
    protected function createFtpClientResultTranslator(): FtpClientResultTranslator
    {
        return $this->ftpClientResultTranslator ?: FtpClientResultTranslator::new();
    }

    /**
     * @param FtpClientResultTranslator $ftpClientResultTranslator
     * @return $this
     * @internal
     */
    public function setFtpClientResultTranslator(FtpClientResultTranslator $ftpClientResultTranslator): static
    {
        $this->ftpClientResultTranslator = $ftpClientResultTranslator;
        return $this;
    }
}
