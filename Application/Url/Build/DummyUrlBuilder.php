<?php
/**
 * SAM-8543: Dummy classes for service stubbing in unit tests
 * SAM-5140: Url Builder class
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

namespace Sam\Application\Url\Build;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Service\Dummy\DummyServiceTrait;

/**
 * Class DummyUrlBuilder
 * @package Sam\Application\Url\Build
 */
class DummyUrlBuilder implements UrlBuilderInterface
{
    use DummyServiceTrait;

    /**
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    public function build(AbstractUrlConfig $urlConfig): string
    {
        $class = substr(get_class($urlConfig), strlen('Sam\\Application\\Url\\Build\\Config\\'));
        $values = array_merge([$class, $urlConfig->urlType()], $urlConfig->params());
        return $this->toString($values);
    }
}
