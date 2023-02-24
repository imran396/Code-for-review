<?php
/**
 * SAM-4647: Refactor csv import sample builders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/23/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\ImportSample;

use InvalidArgumentException;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class ImportSamplerBase
 * @package Sam\Report\ImportSample
 */
abstract class ImportSamplerBase extends ReporterBase
{
    protected ?string $outputFileName = '';
    /**
     * When array values - render each value per csv line, if not enough values in array, we render empty cell.
     * When single value - render the same value for each row.
     */
    protected array $sampleValues = [];
    /** @var string[] */
    protected array $titles = [];

    /**
     * @return string[]
     */
    public function getTitles(): array
    {
        if (!$this->titles) {
            throw new InvalidArgumentException("Column headers not defined");
        }
        return $this->titles;
    }

    /**
     * @param string[] $titles
     * @return static
     */
    public function setTitles(array $titles): static
    {
        $this->titles = ArrayCast::makeStringArray($titles);
        return $this;
    }

    /**
     * @param string $title
     * @param int $i
     * @return string
     */
    protected function produceValue(string $title, int $i): string
    {
        $value = '';
        if (isset($this->sampleValues[$title])) {
            $values = $this->sampleValues[$title];
            if (is_array($values)) {
                $value = $values[$i] ?? '';
            } else {
                $value = $values;
            }
        }
        return $value;
    }
}
