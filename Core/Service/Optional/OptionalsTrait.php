<?php
/**
 * SAM-6658: Improve application and domain layer services
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Service\Optional;

/**
 * Trait OptionsTrait
 * @package Sam\Core\Service
 */
trait OptionalsTrait
{
    /**
     * @var OptionalsCollection|null
     */
    protected ?OptionalsCollection $optionalsCollection = null;

    /**
     * @param array $optionals
     * @return $this
     */
    protected function setOptionals(array $optionals): static
    {
        $this->optionalsCollection = OptionalsCollection::new()->construct($optionals);
        return $this;
    }

    /**
     * @return OptionalsCollection
     */
    protected function getOptionalsCollection(): OptionalsCollection
    {
        if ($this->optionalsCollection === null) {
            $this->optionalsCollection = OptionalsCollection::new()->construct([]);
        }
        return $this->optionalsCollection;
    }

    /**
     * @param string $key key name of optional value
     * @param array $arguments arguments to pass into callable initializer
     * @return mixed
     */
    protected function fetchOptional(string $key, array $arguments = []): mixed
    {
        return $this->getOptionalsCollection()->fetchOptional($key, $arguments);
    }

    /**
     * @param array $keys
     * @return array
     */
    protected function extractOptionalsByKeys(array $keys): array
    {
        return $this->getOptionalsCollection()->extractOptionalsByKeys($keys);
    }

    /**
     * @param string $key
     * @param $value
     * @param array $arguments
     */
    protected function setOptional(string $key, $value, array $arguments = []): void
    {
        $this->getOptionalsCollection()->set($key, $value, $arguments);
    }
}
