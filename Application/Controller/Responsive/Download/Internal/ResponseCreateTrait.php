<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\Internal;

/**
 * Trait ResponseCreateTrait
 * @package Sam\Application\Controller\Responsive\Download\Internal
 */
trait ResponseCreateTrait
{
    /**
     * @var Response|null
     */
    protected ?Response $response = null;

    /**
     * @return Response
     */
    protected function createResponse(): Response
    {
        return $this->response ?: Response::new();
    }

    /**
     * @param Response $response
     * @return static
     * @internal
     */
    public function setResponse(Response $response): static
    {
        $this->response = $response;
        return $this;
    }
}
