<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-26, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Value;

/**
 * Trait InputValueCheckerCreateTrait
 * @package Sam\Installation\Config
 */
trait InputValueCheckerCreateTrait
{
    /**
     * @var InputValueChecker|null
     */
    protected ?InputValueChecker $inputValueChecker = null;

    /**
     * @return InputValueChecker
     */
    protected function createInputValueChecker(): InputValueChecker
    {
        return $this->inputValueChecker ?: InputValueChecker::new();
    }

    /**
     * @param InputValueChecker $inputValueChecker
     * @return static
     */
    public function setInputValueChecker(InputValueChecker $inputValueChecker): static
    {
        $this->inputValueChecker = $inputValueChecker;
        return $this;
    }
}
