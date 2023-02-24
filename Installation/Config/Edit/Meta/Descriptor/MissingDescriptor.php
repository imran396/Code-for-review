<?php
/**
 * This descriptor for option that presents in local config only.
 * Missing option may happen because of global option key renaming, or because of local option key error.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/4/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Descriptor;

/**
 * Class AbsentOptionDescriptor
 * @package
 */
class MissingDescriptor extends Descriptor
{
    /**
     * Always false
     * @var bool
     */
    protected bool $isEditable = false;
    /**
     * Always true
     * @var bool
     */
    protected bool $isDeletable = true;
    /**
     * Always true
     * @var bool
     */
    protected bool $isVisible = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Always deletable
     * @return bool
     */
    public function isDeletable(): bool
    {
        return true;
    }

    /**
     * @override parent::enableDeletable() - reject mutating property
     * @param bool $enable
     * @return $this
     */
    public function enableDeletable(bool $enable): static
    {
        return $this;
    }

    /**
     * Always visible
     * @return bool
     */
    public function isVisible(): bool
    {
        return true;
    }

    /**
     * @override parent::enableVisible() - reject mutating property
     * @param bool $enable
     * @return $this
     */
    public function enableVisible(bool $enable): static
    {
        return $this;
    }

    /**
     * Always non-editable
     * @return bool
     */
    public function isEditable(): bool
    {
        return false;
    }

    /**
     * @override parent::enableEditable() - reject mutating property
     * @param bool $enable
     * @return $this
     */
    public function enableEditable(bool $enable): static
    {
        return $this;
    }
}
