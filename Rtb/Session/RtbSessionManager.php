<?php

namespace Sam\Rtb\Session;

use RtbSession;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\DeleteRepository\Entity\RtbSession\RtbSessionDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\RtbSession\RtbSessionReadRepository;
use Sam\Storage\ReadRepository\Entity\RtbSession\RtbSessionReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbSession\RtbSessionWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class RtbSessionManager
 * @package Sam\Rtb\Session
 */
class RtbSessionManager extends CustomizableClass
{
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use RtbSessionDeleteRepositoryCreateTrait;
    use RtbSessionReadRepositoryCreateTrait;
    use RtbSessionWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $userType
     * @param string|null $ip
     * @param int|null $port
     * @param bool $isReadOnlyDb
     * @return RtbSession|null
     */
    public function load(
        ?int $auctionId = null,
        ?int $userId = null,
        ?int $userType = null,
        ?string $ip = null,
        ?int $port = null,
        bool $isReadOnlyDb = false
    ): ?RtbSession {
        $repo = $this->prepareRepository($auctionId, $userId, $userType, $ip, $port, $isReadOnlyDb);
        $rtbSession = $repo->loadEntity();
        return $rtbSession;
    }

    /**
     * @param int|null $userId
     * @param int|null $auctionId
     * @param int|null $userType
     * @param string|null $ip
     * @param int|null $port
     * @param bool $isReadOnlyDb
     * @return RtbSession[]
     */
    public function loadEntities(
        ?int $userId = null,
        ?int $auctionId = null,
        ?int $userType = null,
        ?string $ip = null,
        ?int $port = null,
        bool $isReadOnlyDb = false
    ): array {
        $repo = $this->prepareRepository($auctionId, $userId, $userType, $ip, $port, $isReadOnlyDb);
        $rtbSessions = $repo->loadEntities();
        return $rtbSessions;
    }

    /**
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $userType
     * @param string|null $ip
     * @param int|null $port
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function count(
        ?int $auctionId = null,
        ?int $userId = null,
        ?int $userType = null,
        ?string $ip = null,
        ?int $port = null,
        bool $isReadOnlyDb = false
    ): int {
        $repo = $this->prepareRepository($auctionId, $userId, $userType, $ip, $port, $isReadOnlyDb);
        $count = $repo->count();
        return $count;
    }

    /**
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $userType
     * @param string|null $ip
     * @param int|null $port
     * @param bool $isReadOnlyDb
     * @return RtbSessionReadRepository
     */
    protected function prepareRepository(
        ?int $auctionId,
        ?int $userId = null,
        ?int $userType = null,
        ?string $ip = null,
        ?int $port = null,
        bool $isReadOnlyDb = false
    ): RtbSessionReadRepository {
        $repo = $this->createRtbSessionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($auctionId !== null) {
            $repo->filterAuctionId($auctionId);
        }
        if ($userId !== null) {
            $repo->filterUserId($userId);
        }
        if ($userType !== null) {
            $repo->filterUserType($userType);
        }
        if ($ip !== null) {
            $repo->filterIp($ip);
        }
        if ($port !== null) {
            $repo->filterPort($port);
        }
        return $repo;
    }

    /**
     * @param int $auctionId
     * @param int|null $editorUserId
     * @param string $sessionId
     * @param int $userType
     * @param string $ip
     * @param int|null $port
     * @return RtbSession
     */
    public function register(
        int $auctionId,
        ?int $editorUserId,
        string $sessionId,
        int $userType,
        string $ip,
        ?int $port
    ): RtbSession {
        $rtbSession = $this->load($auctionId, $editorUserId, $userType, $ip, $port);
        if (!$rtbSession) {
            $rtbSession = $this->createEntityFactory()->rtbSession();
            $rtbSession->AuctionId = $auctionId;
            $rtbSession->UserId = $editorUserId;
        }
        $rtbSession->SessId = $sessionId;
        $rtbSession->UserType = $userType;
        $rtbSession->Ip = $ip;
        $rtbSession->Port = $port;
        $rtbSession->ParticipatedOn = $this->getCurrentDateUtc();
        $editorUserId = $editorUserId ?? $this->getUserLoader()->loadSystemUserId();
        $this->getRtbSessionWriteRepository()->saveWithModifier($rtbSession, $editorUserId);
        return $rtbSession;
    }

    /**
     * @param int $auctionId
     * @param int|null $editorUserId
     * @param int|null $userType
     * @param string $ip
     * @param int|null $port
     * @return static
     */
    public function unregister(
        int $auctionId,
        ?int $editorUserId,
        ?int $userType,
        string $ip,
        ?int $port
    ): static {
        $this->createRtbSessionDeleteRepository()
            ->filterAuctionId($auctionId)
            ->filterUserId($editorUserId)
            ->filterUserType($userType)
            ->filterIp($ip)
            ->filterPort($port)
            ->delete();
        return $this;
    }

    /**
     * @return static
     */
    public function dropAll(): static
    {
        $this->createRtbSessionDeleteRepository()->delete();
        return $this;
    }

    /**
     * @param int|int[] $auctionId
     * @return static
     * @noinspection PhpUnused
     */
    public function dropByAuctionId(int|array $auctionId): static
    {
        $this->createRtbSessionDeleteRepository()
            ->filterAuctionId($auctionId)
            ->delete();
        return $this;
    }
}
