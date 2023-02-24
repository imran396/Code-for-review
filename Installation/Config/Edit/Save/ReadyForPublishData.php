<?php
/**
 * SAM-4886: Local configuration files management page
 * Prepared ready for publish data Immutable value object.
 *
 * @copyright       2020 Yura Vakulenko.
 * @author          Yura Vakulenko
 * @since           26.03.2020
 * file encoding    UTF-8
 *
 */


namespace Sam\Installation\Config\Edit\Save;


/**
 * Class ReadyForPublishData
 * @package Sam\Installation\Config
 */
final class ReadyForPublishData
{
    /**
     * @var array
     */
    private array $update;

    /**
     * @var array
     */
    private array $remove;

    /**
     * Used only for debugging purposes.
     * So that we understand why this option did not get into the 'remove' property.
     *
     * @var array
     */
    private array $sameAsGlobalNotExistInLocal;

    /**
     * ReadyForPublishData constructor.
     * @param array $update
     * @param array $remove
     * @param array $sameAsGlobalNotExistInLocal
     */
    public function __construct(array $update, array $remove, array $sameAsGlobalNotExistInLocal)
    {
        $this->update = $update;
        $this->remove = $remove;
        $this->sameAsGlobalNotExistInLocal = $sameAsGlobalNotExistInLocal;
    }

    /**
     * @return array
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * @return array
     */
    public function getRemove(): array
    {
        return $this->remove;
    }

    /**
     * @return array
     */
    public function getSameAsGlobalNotExistInLocal(): array
    {
        return $this->sameAsGlobalNotExistInLocal;
    }
}
