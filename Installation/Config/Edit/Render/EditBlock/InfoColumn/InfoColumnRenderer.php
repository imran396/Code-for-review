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

namespace Sam\Installation\Config\Edit\Render\EditBlock\InfoColumn;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Render\EditBlock\InputDataWebAwareTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;

/**
 * Class CheckboxRenderer
 * @package Sam\Installation\Config
 */
class InfoColumnRenderer extends CustomizableClass
{
    use InputDataWebAwareTrait;
    use EditBlockRendererHelperCreateTrait;

    protected const HTML_TMPL = '<div class="col-md-1 col-sm-1">%s</div>';

    protected const BADGE_HTML_TMPL = '<p>
<a href="#" class="badge %s mt-1 text-decoration-none" onclick="return false;" data-toggle="tooltip" 
    data-placement="top" title="%s">%s</a>
</p>';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Internal class constructor
     *
     * @param InputDataWeb $inputData
     * @return $this
     */
    public function construct(InputDataWeb $inputData): static
    {
        $this->setInputDataWeb($inputData);
        return $this;
    }

    /**
     * Render info column html.
     * @return string
     */
    public function render(): string
    {
        $badgeNotEditable = $this->renderBadgeNotEditable();
        [$badgeLocal, $badgeMissedOption] = $this->renderOtherBadges();

        $output = sprintf(self::HTML_TMPL, '&nbsp; ' . $badgeLocal . $badgeMissedOption . $badgeNotEditable);
        return $output;
    }

    /**
     * @return string
     */
    protected function renderBadgeNotEditable(): string
    {
        $output = '';
        if (!$this->getInputDataWeb()->getMetaDescriptor()->isEditable()) {
            $output = sprintf(
                self::BADGE_HTML_TMPL,
                'bg-danger',
                'The value of this config option is not editable.',
                'Not editable'
            );
        }
        return $output;
    }

    /**
     * @return array
     */
    protected function renderOtherBadges(): array
    {
        $badgeLocal = $badgeMissedOption = '';
        $inputData = $this->getInputDataWeb();
        if ($inputData->hasLocalConfigValue()) {
            $badgeLocal = sprintf(self::BADGE_HTML_TMPL, 'bg-success', 'Setting from local config file.', 'Local');
            if ($inputData->getMetaDescriptor() instanceof MissingDescriptor) {
                $badgeMissedOption = sprintf(
                    self::BADGE_HTML_TMPL,
                    'bg-danger',
                    'This is missed config option (exists only in local configuration file).',
                    'Missed'
                );
            }
        }

        return [$badgeLocal, $badgeMissedOption];
    }
}
