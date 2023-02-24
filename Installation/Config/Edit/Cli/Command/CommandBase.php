<?php
/**
 * SAM-5708: Local configuration management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Cli\Command;

use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\ConfigNameAwareTrait;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionBuilder;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 * @package Sam\Installation\Config
 */
abstract class CommandBase extends Command
{
    use ConfigNameAwareTrait;
    use OptionHelperAwareTrait;

    public const NAME = null;

    /**
     * @var DescriptorCollection|null
     */
    private ?DescriptorCollection $descriptorCollection = null;

    public function __construct(?string $name = null)
    {
        if ($name === null) {
            $name = static::NAME;
        }
        parent::__construct($name);
    }

    /**
     * @return DescriptorCollection
     */
    protected function getDescriptorCollection(): DescriptorCollection
    {
        if ($this->descriptorCollection === null) {
            $this->descriptorCollection = DescriptorCollectionBuilder::new()->build($this->getConfigName());
        }
        return $this->descriptorCollection;
    }

    /**
     * @param string|null $optionKey
     * @return string
     */
    protected function prepareInputOption(?string $optionKey): string
    {
        $optionComponents = explode(Constants\Installation::DELIMITER_META_OPTION_KEY, $optionKey);
        $configName = array_shift($optionComponents);
        $this->setConfigName($configName);
        return implode(Constants\Installation::DELIMITER_GENERAL_OPTION_KEY, $optionComponents);
    }

    /**
     * @param string $value
     * @return bool|null|string
     */
    protected function normalizeInputValue(string $value): null|bool|string
    {
        return match (strtolower($value)) {
            'null' => null,
            'true' => true,
            'false' => false,
            default => $value,
        };
    }

    /**
     * @param string $optionKey
     * @return bool
     */
    protected function isOptionExist(string $optionKey): bool
    {
        return $this->getDescriptorCollection()->has($optionKey);
    }
}
