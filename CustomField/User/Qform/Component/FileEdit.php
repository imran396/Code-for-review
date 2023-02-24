<?php
/**
 * File-type custom User field editing component
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Dec 04, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Qform\Component;

use RuntimeException;
use Sam\Core\Constants;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class FileEdit
 * @method \Sam\CustomField\Base\Qform\Component\BaseEdit getBaseComponent()
 */
class FileEdit extends BaseEdit
{
    use UserLoaderAwareTrait;

    protected int $type = Constants\CustomField::TYPE_FILE;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return void
     */
    public function create(): void
    {
        $isPublic = $this->getBaseComponent()->isPublic();
        if (!$isPublic) {
            $this->getBaseComponent()->enableDownloadLink(true);
        }
        // next 2 methods are independent each of other at the moment of 2019
        $this->getBaseComponent()->create();
        $this->refreshFilePath();
    }

    /**
     * Define FilePath property - absolute file path to permanent location
     *
     * @return void
     */
    public function refreshFilePath(): void
    {
        $userId = $this->getBaseComponent()->getRelatedEntityId();
        if ($userId) {
            $user = $this->getUserLoader()->load($userId, true);
            if (!$user) {
                throw new RuntimeException("User not found by id: " . $userId);
            }
            $dirName = $user->Id;
        } else {
            // don't know related user at signup
            // it will be updated at save
            $dirName = 'unknown';
        }
        // Same filenames are extended by id: <filename>__<user.id>.<extension>
        $this->getBaseComponent()->setFilePath(path()->uploadUserCustomFieldFile() . '/' . $dirName);
    }
}
