<?php
/**
 * SAM-4634:Refactor special terms report
 * https://bidpath.atlassian.net/browse/SAM-4634
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/8/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SpecialTerm;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class SpecialTermReporterFactory
 * @package Sam\Report\SpecialTerm
 */
class SpecialTermReporterFactory extends CustomizableClass
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
     * @param string $viewMode
     * @return ReporterBase
     */
    public function create(string $viewMode): ReporterBase
    {
        if ($viewMode === Constants\Report::CSV) {
            $reporter = Csv\Reporter::new();
        } else {
            throw new InvalidArgumentException("Unknown view mode: " . $viewMode);
        }
        return $reporter;
    }
}
