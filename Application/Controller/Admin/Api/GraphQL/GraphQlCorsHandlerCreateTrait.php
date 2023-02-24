<?php
/**
 * SAM-10962: Introduce CORS to GraphQL entry point
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Api\GraphQL;

/**
 * Trait GraphQlCorsHandlerCreateTrait
 * @package Sam\Application\Controller\Admin\Api\GraphQL
 */
trait GraphQlCorsHandlerCreateTrait
{
    protected ?GraphQlCorsHandler $graphQlCorsHandler = null;

    /**
     * @return GraphQlCorsHandler
     */
    protected function createGraphQlCorsHandler(): GraphQlCorsHandler
    {
        return $this->graphQlCorsHandler ?: GraphQlCorsHandler::new();
    }

    /**
     * @param GraphQlCorsHandler $graphQlCorsHandler
     * @return static
     * @internal
     */
    public function setGraphQlCorsHandler(GraphQlCorsHandler $graphQlCorsHandler): static
    {
        $this->graphQlCorsHandler = $graphQlCorsHandler;
        return $this;
    }
}
