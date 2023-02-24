<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PaymentGateway\AuctionRegistration;

/**
 * Trait AuthOrCapturePerformerCreateTrait
 * @package Sam\PaymentGateway\AuctionRegistration
 */
trait AuthOrCapturePerformerCreateTrait
{
    protected ?AuthOrCapturePerformer $authOrCapturePerformer = null;

    /**
     * @return AuthOrCapturePerformer
     */
    protected function createAuthOrCapturePerformer(): AuthOrCapturePerformer
    {
        return $this->authOrCapturePerformer ?: AuthOrCapturePerformer::new();
    }

    /**
     * @param AuthOrCapturePerformer $authOrCapturePerformer
     * @return $this
     * @internal
     */
    public function setAuthOrCapturePerformer(AuthOrCapturePerformer $authOrCapturePerformer): static
    {
        $this->authOrCapturePerformer = $authOrCapturePerformer;
        return $this;
    }
}
