<?php
/**
 * SAM-5788: Adjust proxy.php script
 * SAM-7980: Refactor proxy.php
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy;

/**
 * Trait CrossDomainAjaxRequestHandlerCreateTrait
 * @package Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy
 */
trait CrossDomainAjaxRequestHandlerCreateTrait
{
    /**
     * @var CrossDomainAjaxRequestHandler|null
     */
    protected ?CrossDomainAjaxRequestHandler $crossDomainAjaxRequestHandler = null;

    /**
     * @return CrossDomainAjaxRequestHandler
     */
    protected function createCrossDomainAjaxRequestHandler(): CrossDomainAjaxRequestHandler
    {
        return $this->crossDomainAjaxRequestHandler ?: CrossDomainAjaxRequestHandler::new();
    }

    /**
     * @param CrossDomainAjaxRequestHandler $crossDomainAjaxRequestHandler
     * @return static
     * @internal
     */
    public function setCrossDomainAjaxRequestHandler(CrossDomainAjaxRequestHandler $crossDomainAjaxRequestHandler): static
    {
        $this->crossDomainAjaxRequestHandler = $crossDomainAjaxRequestHandler;
        return $this;
    }
}
