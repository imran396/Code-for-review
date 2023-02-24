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
 * Trait OptionValueCheckerCreateTrait
 * @package Sam\Installation\Config
 */
trait OptionValueCheckerCreateTrait
{
    /**
     * @var OptionValueChecker|null
     */
    protected ?OptionValueChecker $optionValueChecker = null;

    /**
     * @return OptionValueChecker
     */
    protected function createOptionValueChecker(): OptionValueChecker
    {
        return $this->optionValueChecker ?: OptionValueChecker::new();
    }

    /**
     * @param OptionValueChecker $optionValueChecker
     * @return static
     */
    public function setOptionValueChecker(OptionValueChecker $optionValueChecker): static
    {
        $this->optionValueChecker = $optionValueChecker;
        return $this;
    }
}
