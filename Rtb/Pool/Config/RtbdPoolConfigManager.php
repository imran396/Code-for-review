<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Config;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Rtb\Pool\Instance\RtbdDescriptorBuilderCreateTrait;
use Sam\Rtb\Pool\Instance\UserTypeAwareTrait;

/**
 * Class RtbPoolConfigManager
 * @package
 */
class RtbdPoolConfigManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use RtbdDescriptorBuilderCreateTrait;
    use UserTypeAwareTrait;

    /**
     * Rtbd instance discovery strategy in pool
     */
    private ?int $discoveryStrategy = null;
    private ?bool $isEnabled = null;
    /**
     * @var RtbdDescriptor[]
     */
    private ?array $allDescriptors = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return valid descriptors only
     * @return RtbdDescriptor[]
     */
    public function getValidDescriptors(): array
    {
        $validDescriptors = [];
        foreach ($this->getAllDescriptors() as $descriptor) {
            if ($descriptor->isValid()) {
                $validDescriptors[] = $descriptor;
            }
        }
        return $validDescriptors;
    }

    /**
     * @return array
     * @noinspection PhpUnused
     */
    public function getValidDescriptorsArray(): array
    {
        $descriptorsArray = [];
        foreach ($this->getValidDescriptors() as $descriptor) {
            $descriptorsArray[] = $descriptor->toArray();
        }
        return $descriptorsArray;
    }

    /**
     * @return int|null
     */
    public function getDiscoveryStrategy(): ?int
    {
        if ($this->discoveryStrategy === null) {
            $this->discoveryStrategy = Cast::toInt($this->cfg()->get('core->rtb->server->pool->discovery'), Constants\RtbdPool::$discoveryStrategies);
        }
        return $this->discoveryStrategy;
    }

    /**
     * @param int $discoveryStrategy
     * @return static
     * @noinspection PhpUnused
     */
    public function setDiscoveryStrategy(int $discoveryStrategy): static
    {
        $this->discoveryStrategy = Cast::toInt($discoveryStrategy, Constants\RtbdPool::$discoveryStrategies);
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        if ($this->isEnabled === null) {
            $this->enable((bool)$this->cfg()->get('core->rtb->server->pool->enabled'));
        }
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return static
     */
    public function enable(bool $isEnabled): static
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * Return all descriptors, including invalid
     * @return RtbdDescriptor[]
     */
    public function getAllDescriptors(): array
    {
        if ($this->allDescriptors === null) {
            $poolInstancesAttributes = $this->cfg()->get('core->rtb->server->pool->instances')->toArray() ?: [];
            $this->allDescriptors = [];
            foreach ($poolInstancesAttributes as $instanceAttributes) {
                $this->allDescriptors[] = $this->createRtbdDescriptorBuilder()
                    ->setUserType($this->getUserType())
                    ->fromArray($instanceAttributes);
            }
        }
        return $this->allDescriptors;
    }

    /**
     * @param RtbdDescriptor[] $descriptors
     * @return static
     * @noinspection PhpUnused
     */
    public function setAllDescriptors(array $descriptors): static
    {
        $this->allDescriptors = $descriptors;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRtbdNames(): array
    {
        return array_reduce(
            $this->getValidDescriptors(),
            static function ($carry, RtbdDescriptor $descriptor) {
                $carry[] = $descriptor->getName();
                return $carry;
            },
            []
        );
    }

    /**
     * @return int
     * @noinspection PhpUnused
     */
    public function getDescriptorCount(): int
    {
        return count($this->getValidDescriptors());
    }

    /**
     * Return descriptor found by name among valid descriptors
     * @param string $rtbdName
     * @return RtbdDescriptor|null
     */
    public function getDescriptorByName(string $rtbdName): ?RtbdDescriptor
    {
        return $this->findDescriptorByName($rtbdName, $this->getValidDescriptors());
    }

    /**
     * Find descriptor by name in passed array of descriptors
     * @param string $rtbdName
     * @param RtbdDescriptor[] $descriptors
     * @return RtbdDescriptor|null
     */
    public function findDescriptorByName(string $rtbdName, array $descriptors): ?RtbdDescriptor
    {
        foreach ($descriptors as $descriptor) {
            if ($descriptor->getName() === $rtbdName) {
                return $descriptor;
            }
        }
        return null;
    }

    /**
     * @param string[] $rtbdNames
     * @return array
     * @noinspection PhpUnused
     */
    public function getDescriptorsByNames(array $rtbdNames): array
    {
        $descriptors = [];
        foreach ($rtbdNames as $rtbdName) {
            $descriptors[] = $this->getDescriptorByName($rtbdName);
        }
        return $descriptors;
    }
}
