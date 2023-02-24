<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           февр. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Mutual;

use InvalidArgumentException;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Edit\Dto\AuctionParametersDto;
use Sam\Settings\Edit\Load\AuctionParametersDataProvider;
use Sam\Settings\Edit\Normalize\AuctionParametersCliNormalizer;
use Sam\Settings\Edit\Normalize\AuctionParametersWebNormalizer;
use Sam\Settings\Edit\Normalize\NormalizerInterface;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class MutualContext
 * @package Sam\Settings\Edit\Mutual
 */
class AuctionParametersMutualContext extends CustomizableClass
{
    use EditorUserAwareTrait;
    use SystemAccountAwareTrait;

    public const MODE_WEB = 1;
    public const MODE_CLI = 2;

    protected AuctionParametersDto $dto;
    protected ?AuctionParametersDataProvider $dataProvider = null;
    protected NormalizerInterface $normalizer;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $mode
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(int $mode, int $editorUserId, int $systemAccountId): static
    {
        $this->setEditorUserId($editorUserId);
        $this->setSystemAccountId($systemAccountId);
        if ($mode === self::MODE_WEB) {
            $this->setNormalizer(AuctionParametersWebNormalizer::new());
        } elseif ($mode === self::MODE_CLI) {
            $this->setNormalizer(AuctionParametersCliNormalizer::new());
        } else {
            throw new InvalidArgumentException(sprintf('Unknown input mode "%d"', $mode));
        }
        $this->dto = AuctionParametersDto::new();
        return $this;
    }

    /**
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return static
     */
    public function constructForWeb(int $editorUserId, int $systemAccountId): static
    {
        return $this->construct(self::MODE_WEB, $editorUserId, $systemAccountId);
    }

    /**
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return static
     */
    public function constructForCli(int $editorUserId, int $systemAccountId): static
    {
        return $this->construct(self::MODE_CLI, $editorUserId, $systemAccountId);
    }

    /**
     * @return AuctionParametersDto
     */
    public function getDto(): AuctionParametersDto
    {
        return $this->dto;
    }

    /**
     * @return AuctionParametersDataProvider
     */
    public function getDataProvider(): AuctionParametersDataProvider
    {
        if (!$this->dataProvider) {
            $this->dataProvider = AuctionParametersDataProvider::new();
        }
        return $this->dataProvider;
    }

    /**
     * @param AuctionParametersDataProvider $dataProvider
     * @return static
     * @internal
     */
    public function setDataProvider(AuctionParametersDataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * @return NormalizerInterface
     */
    public function getNormalizer(): NormalizerInterface
    {
        return $this->normalizer;
    }

    /**
     * @param NormalizerInterface $normalizer
     * @return static
     * @internal
     */
    public function setNormalizer(NormalizerInterface $normalizer): static
    {
        $this->normalizer = $normalizer;
        return $this;
    }
}
