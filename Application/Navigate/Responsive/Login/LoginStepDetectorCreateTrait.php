<?php
/**
 * SAM-5546: Auction registration step detection and redirect
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Navigate\Responsive\Login;

/**
 * Trait LoginStepDetectorCreateTrait
 * @package Sam\Application\Navigate\Responsive\Login
 */
trait LoginStepDetectorCreateTrait
{
    /**
     * @var LoginStepDetector|null
     */
    protected ?LoginStepDetector $loginStepDetector = null;

    /**
     * @return LoginStepDetector
     */
    protected function createLoginStepDetector(): LoginStepDetector
    {
        return $this->loginStepDetector ?: LoginStepDetector::new();
    }

    /**
     * @param LoginStepDetector $loginStepDetector
     * @return static
     * @internal
     */
    public function setLoginStepDetector(LoginStepDetector $loginStepDetector): static
    {
        $this->loginStepDetector = $loginStepDetector;
        return $this;
    }
}
