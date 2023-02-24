<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-21, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Installation\Config\Edit\Render\WebData;


/**
 * Trait UrlHashBuilderAwareTrait
 * @package Sam\Installation\Config
 */
trait UrlHashBuilderAwareTrait
{
    /**
     * @var UrlHashBuilder|null
     */
    protected ?UrlHashBuilder $urlHashBuilder = null;

    /**
     * @return UrlHashBuilder
     */
    protected function getUrlHashBuilder(): UrlHashBuilder
    {
        if ($this->urlHashBuilder === null) {
            $this->urlHashBuilder = UrlHashBuilder::new();
        }
        return $this->urlHashBuilder;
    }

    /**
     * @param UrlHashBuilder $urlHashBuilder
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setUrlHashBuilder(UrlHashBuilder $urlHashBuilder): static
    {
        $this->urlHashBuilder = $urlHashBuilder;
        return $this;
    }
}
