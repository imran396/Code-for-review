<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta;

/**
 * Trait OptionHelperAwareTrait
 * @package Sam\Installation\Config
 */
trait OptionHelperAwareTrait
{
    /**
     * @var OptionHelper|null
     */
    protected ?OptionHelper $optionHelper = null;

    /**
     * @return OptionHelper
     */
    protected function getOptionHelper(): OptionHelper
    {
        if ($this->optionHelper === null) {
            $this->optionHelper = OptionHelper::new();
        }
        return $this->optionHelper;
    }

    /**
     * @param OptionHelper $optionHelper
     * @return static
     * @noinspection PhpUnused
     */
    public function setOptionHelper(OptionHelper $optionHelper): static
    {
        $this->optionHelper = $optionHelper;
        return $this;
    }
}
