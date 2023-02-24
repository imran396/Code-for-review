<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-15, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Constraint;

/**
 * Trait DescriptorConstraintsBuilderAwareTrait
 * @package Sam\Installation\Config
 */
trait DescriptorConstraintsBuilderAwareTrait
{
    /**
     * @var DescriptorConstraintsBuilder|null
     */
    protected ?DescriptorConstraintsBuilder $descriptorConstraintsBuilder = null;

    /**
     * @return DescriptorConstraintsBuilder
     */
    protected function getDescriptorConstraintsBuilder(): DescriptorConstraintsBuilder
    {
        if ($this->descriptorConstraintsBuilder === null) {
            $this->descriptorConstraintsBuilder = DescriptorConstraintsBuilder::new();
        }
        return $this->descriptorConstraintsBuilder;
    }

    /**
     * @param DescriptorConstraintsBuilder $builder
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setDescriptorConstraintsBuilder(DescriptorConstraintsBuilder $builder): static
    {
        $this->descriptorConstraintsBuilder = $builder;
        return $this;
    }
}
