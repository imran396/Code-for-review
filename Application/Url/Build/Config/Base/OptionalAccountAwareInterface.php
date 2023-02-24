<?php
/**
 * Interface for reading optional values related to account of entity that is covered by url
 *
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Base;

use Account;

interface OptionalAccountAwareInterface
{
    /**
     * Return optional account id
     * @return int|null
     */
    public function getOptionalAccountId(): ?int;

    /**
     * Return optional account
     * @return Account|null
     */
    public function getOptionalAccount(): ?Account;
}
