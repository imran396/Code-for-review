<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-13, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock;

use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;

/**
 * Trait EditBlockGeneralAwareTrait
 * @package Sam\Installation\Config
 */
trait InputDataWebAwareTrait
{
    /**
     * @var InputDataWeb|null
     */
    protected ?InputDataWeb $inputDataWeb = null;

    /**
     * @return InputDataWeb
     */
    protected function getInputDataWeb(): InputDataWeb
    {
        return $this->inputDataWeb;
    }

    /**
     * @param InputDataWeb $inputDataWeb
     * @return static
     */
    public function setInputDataWeb(InputDataWeb $inputDataWeb): static
    {
        $this->inputDataWeb = $inputDataWeb;
        return $this;
    }
}
