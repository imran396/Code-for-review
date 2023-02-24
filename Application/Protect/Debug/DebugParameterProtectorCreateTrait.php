<?php
/**
 * SAM-5669: Debug level parameter for web request
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/1/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Debug;

/**
 * Trait DebugParameterCheckProcessorCreateTrait
 * @package
 */
trait DebugParameterProtectorCreateTrait
{
    /**
     * @var DebugParameterProtector|null
     */
    protected ?DebugParameterProtector $debugParameterProtector = null;

    /**
     * @return DebugParameterProtector
     */
    protected function createDebugParameterProtector(): DebugParameterProtector
    {
        return $this->debugParameterProtector ?: DebugParameterProtector::new();
    }

    /**
     * @param DebugParameterProtector $requestParameterProtector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setDebugParameterProtector(DebugParameterProtector $requestParameterProtector): static
    {
        $this->debugParameterProtector = $requestParameterProtector;
        return $this;
    }
}
