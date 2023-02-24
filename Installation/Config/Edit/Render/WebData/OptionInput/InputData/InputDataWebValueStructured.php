<?php
/**
 * Immutable value object stores structured wed-ready input data value.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-15, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData;


/**
 * Class InputDataWebStructuredValue
 * @package Sam\Installation\Config
 */
final class InputDataWebValueStructured
{
    protected array $structures;

    /**
     * InputDataWebValueStructured constructor.
     * @param array $structures
     */
    public function __construct(array $structures)
    {
        $this->structures = $structures;
    }

    public function getStructures(): array
    {
        return $this->structures;
    }
}
