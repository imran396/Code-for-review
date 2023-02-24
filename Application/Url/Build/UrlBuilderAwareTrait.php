<?php
/**
 * SAM-5140: Url Builder class
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-26, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build;

/**
 * Trait UrlBuilderAwareTrait
 * @package Sam\Application\Url\Build
 */
trait UrlBuilderAwareTrait
{
    protected ?UrlBuilderInterface $urlBuilder = null;

    /**
     * @return UrlBuilderInterface
     */
    protected function getUrlBuilder(): UrlBuilderInterface
    {
        if ($this->urlBuilder === null) {
            $this->urlBuilder = UrlBuilder::new();
        }
        return $this->urlBuilder;
    }

    /**
     * @param UrlBuilderInterface $urlBuilder
     * @return static
     */
    public function setUrlBuilder(UrlBuilderInterface $urlBuilder): static
    {
        $this->urlBuilder = $urlBuilder;
        return $this;
    }
}
