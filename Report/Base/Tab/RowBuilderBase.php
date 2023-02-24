<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Base\Tab;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class RendererBase
 * @package Sam\Report\Base\Csv
 */
abstract class RowBuilderBase extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;

    /** @var string|null */
    private ?string $encoding = null;

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        if ($this->encoding === null) {
            $this->encoding = $this->getSettingsManager()
                ->get(Constants\Setting::DEFAULT_EXPORT_ENCODING, $this->getSystemAccountId());
        }
        return $this->encoding;
    }

    /**
     * @param string $encoding
     * @return static
     */
    public function setEncoding(string $encoding): static
    {
        $this->encoding = $encoding;
        return $this;
    }
}
