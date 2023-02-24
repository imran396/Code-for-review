<?php
/**
 * SAM-8543: Dummy classes for service stubbing in unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Service\Dummy;

trait DummyServiceTrait
{
    /**
     * Concatenate arguments
     * @param array $arguments
     * @param array $optionals
     * @return string
     */
    protected function toString(array $arguments, array $optionals = []): string
    {
        return DummyServiceHelper::new()
            ->construct($optionals)
            ->toString($arguments);
    }
}
