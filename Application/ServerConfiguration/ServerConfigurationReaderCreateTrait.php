<?php
/**
 * SAM-11587: Refactor Qform_UploadHelper for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\ServerConfiguration;

/**
 * Trait ServerConfigurationReaderCreateTrait
 * @package Sam\Application\ServerConfiguration
 */
trait ServerConfigurationReaderCreateTrait
{
    protected ?ServerConfigurationReader $serverConfigurationReader = null;

    /**
     * @return ServerConfigurationReader
     */
    protected function createServerConfigurationReader(): ServerConfigurationReader
    {
        return $this->serverConfigurationReader ?: ServerConfigurationReader::new();
    }

    /**
     * @param ServerConfigurationReader $serverConfigurationReader
     * @return static
     * @internal
     */
    public function setServerConfigurationReader(ServerConfigurationReader $serverConfigurationReader): static
    {
        $this->serverConfigurationReader = $serverConfigurationReader;
        return $this;
    }
}
