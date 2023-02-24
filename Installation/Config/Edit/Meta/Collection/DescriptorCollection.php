<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26/05/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Collection;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\ConfigNameAwareTrait;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;

/**
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 */
class DescriptorCollection extends CustomizableClass
{
    use ConfigNameAwareTrait;

    /**
     * @var Descriptor[]
     */
    protected array $descriptorArray = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $configName
     * @param Descriptor[] $descriptors
     * @return $this
     */
    public function construct(string $configName, array $descriptors): DescriptorCollection
    {
        $this->setConfigName($configName);
        $this->descriptorArray = $descriptors;
        return $this;
    }

    /**
     * @return Descriptor[]
     */
    public function toArray(): array
    {
        return $this->descriptorArray;
    }

    /**
     * Get Meta descriptor from collection by key
     * @param string $optionKey it follows Constants\Installation::DELIMITER_GENERAL_OPTION_KEY pattern
     * @return Descriptor
     * @throws \LogicException when descriptor not found by key, you should check by has($key) before call get($key).
     */
    public function get(string $optionKey): Descriptor
    {
        $descriptors = $this->toArray();
        $descriptor = $descriptors[$optionKey] ?? null;
        if (!$descriptor instanceof Descriptor) {
            throw new \LogicException(sprintf('Cannot find meta descriptor by key "%s"', $optionKey));
        }
        return $descriptor;
    }

    /**
     * @param string $optionKey
     * @return bool
     */
    public function has(string $optionKey): bool
    {
        return isset($this->toArray()[$optionKey]);
    }

    /**
     * @param bool $forWeb only descriptors for web editable options (visible and editable)
     * @return Descriptor[]
     */
    public function getEditableDescriptors(bool $forWeb = false): array
    {
        $descriptors = [];
        foreach ($this->toArray() as $optionKey => $descriptor) {
            if ($forWeb) {
                if ($descriptor->isEditable() && $descriptor->isVisible()) {
                    $descriptors[$optionKey] = $descriptor;
                }
            } elseif ($descriptor->isEditable()) {
                $descriptors[$optionKey] = $descriptor;
            }
        }
        return $descriptors;
    }
}
