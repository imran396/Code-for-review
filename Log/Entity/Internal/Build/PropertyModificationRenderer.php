<?php
/**
 * SAM-6729: Improve logging - entity dump attribute logging options
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Entity\Internal\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Entity\Config\EntityPropertyConfig;
use Sam\Log\Render\LogValueRenderer;

/**
 * Class PropertyModificationRenderer
 * @package Sam\Log\Entity\Internal\Render
 * @internal
 */
class PropertyModificationRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param mixed $oldValue
     * @param mixed $newValue
     * @param EntityPropertyConfig $config
     * @return string
     */
    public function render(mixed $oldValue, mixed $newValue, EntityPropertyConfig $config): string
    {
        if ($config->skip) {
            return '';
        }

        $output = '';

        $logValueRenderer = LogValueRenderer::new();
        $oldValueRendered = $logValueRenderer->render($oldValue, $config->renderConfig);
        $newValueRendered = $logValueRenderer->render($newValue, $config->renderConfig);
        $output .= "{$oldValueRendered} => {$newValueRendered}";

        if ($config->showDiff) {
            $diff = $this->diff($oldValue, $newValue);
            $diff = $logValueRenderer->cut($diff, $config->renderConfig->maxLength);
            $output .= ' ' . $diff;
        }

        $output = trim($output);

        if (!$output) {
            $output = '... skip value ...';
        }

        return "{$config->propertyName}: {$output}";
    }

    /**
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return string
     */
    protected function diff(mixed $oldValue, mixed $newValue): string
    {
        if (function_exists('xdiff_string_diff')) {
            $diff = "\n" . xdiff_string_diff($oldValue . PHP_EOL, $newValue . PHP_EOL);
        } else {
            $diff = 'xdiff is not installed';
        }
        return $diff;
    }
}
