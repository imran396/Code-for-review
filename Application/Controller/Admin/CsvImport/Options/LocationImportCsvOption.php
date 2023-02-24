<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\CsvImport\Options;

use Sam\Application\RequestParam\RequestParamFetcher;
use Sam\Core\Service\CustomizableClass;

/**
 * Class that contains all location import options
 *
 * Class LocationImportCsvOption
 * @package Sam\Application\Controller\Admin\CsvImport\Options
 */
class LocationImportCsvOption extends CustomizableClass implements ImportCsvOptionInterface
{
    /**
     * @var string
     */
    public string $encoding;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $encoding): static
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fromRequest(RequestParamFetcher $paramFetcher): static
    {
        return $this->construct(
            $paramFetcher->getString('encoding')
        );
    }
}
