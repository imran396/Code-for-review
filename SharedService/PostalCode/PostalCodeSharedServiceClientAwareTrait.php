<?php
/**
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

namespace Sam\SharedService\PostalCode;

/**
 * Trait PostalCodeSharedServiceClientAwareTrait
 * @package Sam\SharedService\PostalCode
 */
trait PostalCodeSharedServiceClientAwareTrait
{
    protected ?PostalCodeSharedServiceClient $postalCodeSharedServiceClient = null;

    /**
     * @return PostalCodeSharedServiceClient
     */
    protected function getPostalCodeSharedServiceClient(): PostalCodeSharedServiceClient
    {
        if ($this->postalCodeSharedServiceClient === null) {
            $this->postalCodeSharedServiceClient = PostalCodeSharedServiceClient::new();
        }
        return $this->postalCodeSharedServiceClient;
    }

    /**
     * @param PostalCodeSharedServiceClient $postalCodeSharedServiceClient
     * @return static
     * @internal
     */
    public function setPostalCodeSharedServiceClient(PostalCodeSharedServiceClient $postalCodeSharedServiceClient): static
    {
        $this->postalCodeSharedServiceClient = $postalCodeSharedServiceClient;
        return $this;
    }
}
