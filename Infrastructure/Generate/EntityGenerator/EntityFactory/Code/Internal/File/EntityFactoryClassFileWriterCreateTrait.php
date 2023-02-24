<?php
/**
 * SAM-9486: Entity factory class generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code\Internal\File;

/**
 * Trait EntityFactoryClassFileWriterCreateTrait
 * @package Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code\Internal
 */
trait EntityFactoryClassFileWriterCreateTrait
{
    protected ?EntityFactoryClassFileWriter $entityFactoryClassFileWriter = null;

    /**
     * @return EntityFactoryClassFileWriter
     */
    protected function createEntityFactoryClassFileWriter(): EntityFactoryClassFileWriter
    {
        return $this->entityFactoryClassFileWriter ?: EntityFactoryClassFileWriter::new();
    }

    /**
     * @param EntityFactoryClassFileWriter $entityFactoryClassFileWriter
     * @return $this
     * @internal
     */
    public function setEntityFactoryClassFileWriter(EntityFactoryClassFileWriter $entityFactoryClassFileWriter): static
    {
        $this->entityFactoryClassFileWriter = $entityFactoryClassFileWriter;
        return $this;
    }
}
