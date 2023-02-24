<?php
/**
 * SAM-10237: Make fixes on "Local configuration files management page" page ( /admin/installation-setting/)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-13, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Installation\Config\Edit\Render\WebData\FormData\Dto;


/**
 * Class PreparedFormDataDto
 * @package Sam\Installation\Config\Edit\Render\WebData\FormData\Dto
 */
class PreparedFormDataDto
{
    private array $formData;

    /**
     * Stores how many visible/editable options for current config area
     * @var array
     */
    private array $formStatistics;

    public function __construct(array $formData, array $formStatistics)
    {
        $this->formData = $formData;
        $this->formStatistics = $formStatistics;
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    /**
     * @return array
     */
    public function getFormStatistics(): array
    {
        return $this->formStatistics;
    }
}
