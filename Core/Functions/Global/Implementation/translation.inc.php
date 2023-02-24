<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

use Sam\Translation\AdminTranslator;

/**
 * Translate string or key
 *
 * @param string $id
 * @param array $parameters
 * @param string|null $domain
 * @return string
 */
function __(string $id, array $parameters = [], string $domain = null): string
{
    return AdminTranslator::new()->trans($id, $parameters, $domain);
}
