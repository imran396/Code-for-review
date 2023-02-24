<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Console\Internal\Load;

/**
 * Trait GeneralDataProviderAwareTrait
 * @package Sam\Rtb\Console\Base\Internal\Load
 */
trait GeneralDataProviderAwareTrait
{
    /**
     * @var GeneralDataProvider|null
     */
    protected ?GeneralDataProvider $generalDataProvider = null;

    /**
     * @return GeneralDataProvider
     */
    protected function getGeneralDataProvider(): GeneralDataProvider
    {
        if ($this->generalDataProvider === null) {
            $this->generalDataProvider = GeneralDataProvider::new();
        }
        return $this->generalDataProvider;
    }

    /**
     * @param GeneralDataProvider $generalDataProvider
     * @return $this
     * @internal
     */
    public function setGeneralDataProvider(GeneralDataProvider $generalDataProvider): static
    {
        $this->generalDataProvider = $generalDataProvider;
        return $this;
    }
}
