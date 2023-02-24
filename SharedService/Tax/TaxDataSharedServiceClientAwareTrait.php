<?php
/**
 * SAM-4767: Refactor SAM Shared Service API clients
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           25.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\SharedService\Tax;

/**
 * Trait TaxDataSharedServiceClientAwareTrait
 * @package Sam\SharedService\Tax
 */
trait TaxDataSharedServiceClientAwareTrait
{
    protected ?TaxDataSharedServiceClient $taxDataSharedServiceClient = null;

    /**
     * @return TaxDataSharedServiceClient
     */
    protected function getTaxDataSharedServiceClient(): TaxDataSharedServiceClient
    {
        if ($this->taxDataSharedServiceClient === null) {
            $this->taxDataSharedServiceClient = TaxDataSharedServiceClient::new();
        }
        return $this->taxDataSharedServiceClient;
    }

    /**
     * @param TaxDataSharedServiceClient $taxDataSharedServiceClient
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTaxDataSharedServiceClient(TaxDataSharedServiceClient $taxDataSharedServiceClient): static
    {
        $this->taxDataSharedServiceClient = $taxDataSharedServiceClient;
        return $this;
    }
}
