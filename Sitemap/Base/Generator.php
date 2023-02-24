<?php
/**
 * Sitemap Generator
 * We must inject data reader and output builder at first.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\Base;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sitemap\Base\Reader\ReaderInterface;
use Sam\Sitemap\Base\Builder\BuilderInterface;

/**
 * Class Generator
 * @package Sam\Sitemap\Base
 */
class Generator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    protected ?ReaderInterface $reader = null;
    protected ?BuilderInterface $builder = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ReaderInterface $reader
     * @return static
     */
    public function setReader(ReaderInterface $reader): static
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * @param BuilderInterface $builder
     * @return static
     */
    public function setBuilder(BuilderInterface $builder): static
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @return \Generator
     */
    public function yieldData(): ?\Generator
    {
        $counter = 1;
        while ($data = $this->reader->read()) {
            if ($counter > $this->cfg()->get('core->sitemap->maxNumberOfUrls')) {
                break;
            }
            $data = $this->builder->prepare($data);
            yield $data;
            $counter++;
        }
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        $this->builder->setData($this->yieldData());
        $output = $this->builder->build();
        return $output;
    }
}
