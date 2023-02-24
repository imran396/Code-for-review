<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 * SAM-7961: Move \Simple_Db to Sam\Storage namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Database;


/**
 * Trait SimpleMysqliDatabaseAwareTrait
 * @package Sam\Storage\Database
 */
trait SimpleMysqliDatabaseAwareTrait
{
    protected ?SimpleMysqliDatabase $simpleMysqliDatabase = null;

    /**
     * @return SimpleMysqliDatabase
     */
    protected function getSimpleMysqliDatabase(): SimpleMysqliDatabase
    {
        if ($this->simpleMysqliDatabase === null) {
            $this->simpleMysqliDatabase = SimpleMysqliDatabase::new();
        }
        return $this->simpleMysqliDatabase;
    }

    /**
     * @param SimpleMysqliDatabase $simpleMysqliDatabase
     * @return static
     * @internal
     */
    public function setSimpleMysqliDatabase(SimpleMysqliDatabase $simpleMysqliDatabase): static
    {
        $this->simpleMysqliDatabase = $simpleMysqliDatabase;
        return $this;
    }
}
