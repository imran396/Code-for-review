<?php
/**
 * SAM-5138: Url Advisor class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url;

/**
 * Trait UrlAdvisorAwareTrait
 * @package Sam\Application\HttpRequest
 */
trait UrlAdvisorAwareTrait
{
    /**
     * @var UrlAdvisor|null
     */
    protected ?UrlAdvisor $urlAdvisor = null;

    /**
     * @return UrlAdvisor
     */
    protected function getUrlAdvisor(): UrlAdvisor
    {
        if ($this->urlAdvisor === null) {
            $this->urlAdvisor = UrlAdvisor::new();
        }
        return $this->urlAdvisor;
    }

    /**
     * @param UrlAdvisor $urlAdvisor
     * @return static
     * @internal
     */
    public function setUrlAdvisor(UrlAdvisor $urlAdvisor): static
    {
        $this->urlAdvisor = $urlAdvisor;
        return $this;
    }
}
