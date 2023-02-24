<?php
/**
 * Url config immutable VO for configuring lot image url rendering.
 *
 * All image urls are rendered with help of Domain Rule view (core->app->url->domainRule) in result, so we don't need other constructors.
 * Parent class supply possibility to build url template view.
 * When we want base file path, we can call urlFilled(). Account id not needed then.
 *
 * SAM-6695: Image link prefix detection do not provide default value and are not based on account of context
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Image\Base;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Path\PathResolver;

/**
 * Class LotItemImageUrlConfig
 * @package Sam\Application\Url
 */
abstract class AbstractImageUrlConfig extends AbstractUrlConfig
{
    /**
     * @return int|null
     */
    abstract public function accountId(): ?int;

    /**
     * Return file name of thumbnail static image
     * @return string
     */
    public function fileName(): string
    {
        return basename($this->urlFilled());
    }

    /**
     * Return url path of thumbnail static image
     * @return string
     */
    public function urlPath(): string
    {
        return dirname($this->urlFilled());
    }

    /**
     * Return base path of thumbnail static image
     * @return string
     */
    public function fileBasePath(): string
    {
        return PathResolver::DOCROOT . $this->urlFilled();
    }
}
