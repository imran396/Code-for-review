<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Response;

/**
 * Trait JsonResponseAwareTrait
 * @package Sam\Application\Controller\Response
 */
trait JsonResponseAwareTrait
{
    /**
     * @var JsonResponse|null
     */
    protected ?JsonResponse $jsonResponse = null;

    /**
     * @return JsonResponse
     */
    protected function getJsonResponse(): JsonResponse
    {
        if ($this->jsonResponse === null) {
            $this->jsonResponse = new JsonResponse();
        }
        return $this->jsonResponse;
    }

    /**
     * @param JsonResponse $jsonResponse
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setJsonResponse(JsonResponse $jsonResponse): static
    {
        $this->jsonResponse = $jsonResponse;
        return $this;
    }
}
