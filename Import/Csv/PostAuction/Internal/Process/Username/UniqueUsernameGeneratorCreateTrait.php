<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Username;

/**
 * Trait UniqueUsernameGeneratorCreateTrait
 * @package Sam\Import\Csv\PostAuction\Internal\Process
 * @internal
 */
trait UniqueUsernameGeneratorCreateTrait
{
    protected ?UniqueUsernameGenerator $uniqueUsernameGenerator = null;

    /**
     * @return UniqueUsernameGenerator
     */
    protected function createUniqueUsernameGenerator(): UniqueUsernameGenerator
    {
        return $this->uniqueUsernameGenerator ?: UniqueUsernameGenerator::new();
    }

    /**
     * @param UniqueUsernameGenerator $uniqueUsernameGenerator
     * @return static
     * @internal
     */
    public function setUniqueUsernameGenerator(UniqueUsernameGenerator $uniqueUsernameGenerator): static
    {
        $this->uniqueUsernameGenerator = $uniqueUsernameGenerator;
        return $this;
    }
}
