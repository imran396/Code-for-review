<?php
/**
 * Can add and remove back-page url to query string ("url" key)
 * Can sanitize back-page url value
 *
 * SAM-5305: Back-page url Parser
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\BackPage;

use Sam\Core\Service\Dummy\DummyServiceTrait;

/**
 * Class BackUrlParser
 * @package Sam\Application\Url
 */
class DummyBackUrlParser extends BackUrlParser
{
    use DummyServiceTrait;

    public function replace(string $url, ?string $backUrl): string
    {
        return $this->toString(func_get_args());
    }

    public function sanitize(string $backUrl): string
    {
        return $this->toString(func_get_args());
    }
}
