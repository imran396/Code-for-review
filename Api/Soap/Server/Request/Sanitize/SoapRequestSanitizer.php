<?php
/**
 * Contains logic to clean up the SOAP request
 *
 * SAM-5794: SOAP call processing shouldn't ignore incorrect fields
 * SAM-6445: Soap should ignore tags with xsi:nil="true" attribute
 *
 * Project        SOAP Server
 * @author        Victor Pautoff
 * @version       SVN: $Id: $
 * @since         Sep 03, 2021
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Server\Request\Sanitize;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SoapRequestHelper
 * @package Sam\Soap
 */
class SoapRequestSanitizer extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Removes tags with xsi:nil="true" attribute from the request
     * @param string $request
     * @return string
     */
    public function removeNilTags(string $request): string
    {
        return preg_replace('/<([^>]*)xsi:nil="true"([^>]*)\/>|<([^>]*)xsi:nil="true"([^>]*)>([^>]*)<\/([^>]*)>/', '', $request);
    }
}
