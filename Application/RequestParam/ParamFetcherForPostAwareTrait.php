<?php
/**
 * Trait for access to RequestParamFetcher initialized with POST arguments
 *
 * SAM-4075: Apply Qform helpers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jul 19, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam;

/**
 * Trait ParamFetcherForPostAwareTrait
 * @package Sam\Application\RequestParam
 */
trait ParamFetcherForPostAwareTrait
{
    protected ?ParamFetcherForPost $paramFetcherForPost = null;

    /**
     * In regular situations, when we don't predefine instance by setParamFetcherForPost(),
     * we always return fresh initialized by POST arguments instance. We don't cache object instance in $paramFetcherForPost.
     * @return ParamFetcherForPost
     */
    protected function getParamFetcherForPost(): ParamFetcherForPost
    {
        $paramFetcherForPost = $this->paramFetcherForPost ?? ParamFetcherForPost::new()->construct();
        return $paramFetcherForPost;
    }

    /**
     * Inject dependency. For unit tests.
     * @param ParamFetcherForPost $paramFetcherForPost
     * @return static
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @internal
     */
    public function setParamFetcherForPost(ParamFetcherForPost $paramFetcherForPost): static
    {
        $this->paramFetcherForPost = $paramFetcherForPost;
        return $this;
    }
}
