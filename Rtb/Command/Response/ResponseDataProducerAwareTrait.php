<?php
/**
 * Aware trait for service that produces rtbd response data
 *
 * SAM-4620: Bid request not showing on clerk when clerk action collides with incoming bid request
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response;

/**
 * Trait ResponseDataProducerAwareTrait
 * @package Sam\Rtb\Command\Response
 */
trait ResponseDataProducerAwareTrait
{
    protected ?ResponseDataProducer $responseDataProducer = null;

    /**
     * @return ResponseDataProducer
     */
    protected function getResponseDataProducer(): ResponseDataProducer
    {
        if ($this->responseDataProducer === null) {
            $this->responseDataProducer = ResponseDataProducer::new();
        }
        return $this->responseDataProducer;
    }

    /**
     * @param ResponseDataProducer $responseDataProducer
     * @return static
     * @internal
     */
    public function setResponseDataProducer(ResponseDataProducer $responseDataProducer): static
    {
        $this->responseDataProducer = $responseDataProducer;
        return $this;
    }
}
