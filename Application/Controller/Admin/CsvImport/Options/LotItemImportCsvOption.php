<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\CsvImport\Options;

use Sam\Application\RequestParam\RequestParamFetcher;
use Sam\Core\Service\CustomizableClass;

/**
 * Class that contains all lot item import options
 *
 * Class LotItemImportCsvOption
 * @package Sam\Application\Controller\Admin\CsvImport\Options
 */
class LotItemImportCsvOption extends CustomizableClass implements ImportCsvOptionInterface
{
    /**
     * @var string
     */
    public string $encoding;
    /**
     * @var bool
     */
    public bool $overwriteExisting;
    /**
     * @var bool
     */
    public bool $htmlBreaks;
    /**
     * @var bool
     */
    public bool $clearEmptyFields;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $encoding, bool $overwriteExisting, bool $htmlBreaks, bool $clearEmptyFields): static
    {
        $this->clearEmptyFields = $clearEmptyFields;
        $this->encoding = $encoding;
        $this->htmlBreaks = $htmlBreaks;
        $this->overwriteExisting = $overwriteExisting;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fromRequest(RequestParamFetcher $paramFetcher): static
    {
        return $this->construct(
            $paramFetcher->getString('encoding'),
            $paramFetcher->getBool('lotItemOverwriteExisting'),
            $paramFetcher->getBool('htmlBreaks'),
            $paramFetcher->getBool('clearEmptyFields')
        );
    }
}
