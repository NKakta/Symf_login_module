<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class ImportedAccountModel
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Username")
     */
    protected $username;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Password")
     */
    protected $password;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("SummonerId")
     */
    protected $summonerId;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Summoner")
     */
    protected $summoner;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("Level")
     */
    protected $level;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("EmailStatus")
     */
    protected $emailStatus;

    /**
     * @var float
     * @Serializer\Type("float")
     * @Serializer\SerializedName("RpBalance")
     */
    protected $rpBalance;

    /**
     * @var float
     * @Serializer\Type("float")
     * @Serializer\SerializedName("IpBalance")
     */
    protected $ipBalance;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("RunePages")
     */
    protected $runePages;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("Refunds")
     */
    protected $refunds;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("PreviousSeasonRank")
     */
    protected $previousSeasonRank;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("SoloQRank")
     */
    protected $soloQRank;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("LastPlay")
     */
    protected $lastPlay;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("CheckedTime")
     */
    protected $checkedTime;

    /**
     * @var array
     * @Serializer\Type("array<App\Model\Champion>")
     * @Serializer\SerializedName("ChampionList")
     */
    protected $championList;

    /**
     * @var array
     * @Serializer\Type("array<App\Model\Skin>")
     * @Serializer\SerializedName("SkinList")
     */
    protected $skinList;

    /**
     * @var array
     * @Serializer\Type("array")
     * @Serializer\SerializedName("RuneList")
     */
    protected $runeList;

    /**
     * @var integer
     * @Serializer\Type("int")
     * @Serializer\SerializedName("Runes")
     */
    protected $runes;

    /**
     * @var array
     * @Serializer\Type("array")
     * @Serializer\SerializedName("TransferList")
     */
    protected $transferList;

    /**
     * @var array
     * @Serializer\Type("array<App\Model\SummonerIcon>")
     * @Serializer\SerializedName("SummonerIconList")
     */
    protected $summonerIconList;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("PenaltyMillis")
     */
    protected $penaltyMillis;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("BannedUntil")
     */
    protected $bannedUntil;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("State")
     */
    protected $state;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Region")
     */
    protected $region;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return ImportedAccountModel
     */
    public function setUsername(string $username): ImportedAccountModel
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return ImportedAccountModel
     */
    public function setPassword(string $password): ImportedAccountModel
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummonerId(): string
    {
        return $this->summonerId;
    }

    /**
     * @param string $summonerId
     * @return ImportedAccountModel
     */
    public function setSummonerId(string $summonerId): ImportedAccountModel
    {
        $this->summonerId = $summonerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummoner(): string
    {
        return $this->summoner;
    }

    /**
     * @param string $summoner
     * @return ImportedAccountModel
     */
    public function setSummoner(string $summoner): ImportedAccountModel
    {
        $this->summoner = $summoner;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return ImportedAccountModel
     */
    public function setLevel(int $level): ImportedAccountModel
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailStatus(): string
    {
        return $this->emailStatus;
    }

    /**
     * @param string $emailStatus
     * @return ImportedAccountModel
     */
    public function setEmailStatus(string $emailStatus): ImportedAccountModel
    {
        $this->emailStatus = $emailStatus;
        return $this;
    }

    /**
     * @return float
     */
    public function getRpBalance(): float
    {
        return $this->rpBalance;
    }

    /**
     * @param float $rpBalance
     * @return ImportedAccountModel
     */
    public function setRpBalance(float $rpBalance): ImportedAccountModel
    {
        $this->rpBalance = $rpBalance;
        return $this;
    }

    /**
     * @return float
     */
    public function getIpBalance(): float
    {
        return $this->ipBalance;
    }

    /**
     * @param float $ipBalance
     * @return ImportedAccountModel
     */
    public function setIpBalance(float $ipBalance): ImportedAccountModel
    {
        $this->ipBalance = $ipBalance;
        return $this;
    }

    /**
     * @return int
     */
    public function getRunePages(): int
    {
        return $this->runePages;
    }

    /**
     * @param int $runePages
     * @return ImportedAccountModel
     */
    public function setRunePages(int $runePages): ImportedAccountModel
    {
        $this->runePages = $runePages;
        return $this;
    }

    /**
     * @return int
     */
    public function getRefunds(): int
    {
        return $this->refunds;
    }

    /**
     * @param int $refunds
     * @return ImportedAccountModel
     */
    public function setRefunds(int $refunds): ImportedAccountModel
    {
        $this->refunds = $refunds;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreviousSeasonRank(): string
    {
        return $this->previousSeasonRank;
    }

    /**
     * @param string $previousSeasonRank
     * @return ImportedAccountModel
     */
    public function setPreviousSeasonRank(string $previousSeasonRank): ImportedAccountModel
    {
        $this->previousSeasonRank = $previousSeasonRank;
        return $this;
    }

    /**
     * @return string
     */
    public function getSoloQRank(): string
    {
        return $this->soloQRank;
    }

    /**
     * @param string $soloQRank
     * @return ImportedAccountModel
     */
    public function setSoloQRank(string $soloQRank): ImportedAccountModel
    {
        $this->soloQRank = $soloQRank;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastPlay(): string
    {
        return $this->lastPlay;
    }

    /**
     * @param string $lastPlay
     * @return ImportedAccountModel
     */
    public function setLastPlay(string $lastPlay): ImportedAccountModel
    {
        $this->lastPlay = $lastPlay;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckedTime(): string
    {
        return $this->checkedTime;
    }

    /**
     * @param string $checkedTime
     * @return ImportedAccountModel
     */
    public function setCheckedTime(string $checkedTime): ImportedAccountModel
    {
        $this->checkedTime = $checkedTime;
        return $this;
    }

    /**
     * @return array
     */
    public function getChampionList(): array
    {
        return $this->championList;
    }

    /**
     * @param array $championList
     * @return ImportedAccountModel
     */
    public function setChampionList(array $championList): ImportedAccountModel
    {
        $this->championList = $championList;
        return $this;
    }

    /**
     * @return array
     */
    public function getSkinList(): array
    {
        return $this->skinList;
    }

    /**
     * @param array $skinList
     * @return ImportedAccountModel
     */
    public function setSkinList(array $skinList): ImportedAccountModel
    {
        $this->skinList = $skinList;
        return $this;
    }

    /**
     * @return array
     */
    public function getRuneList(): array
    {
        return $this->runeList;
    }

    /**
     * @param array $runeList
     * @return ImportedAccountModel
     */
    public function setRuneList(array $runeList): ImportedAccountModel
    {
        $this->runeList = $runeList;
        return $this;
    }

    /**
     * @return int
     */
    public function getRunes(): int
    {
        return $this->runes;
    }

    /**
     * @param int $runes
     * @return ImportedAccountModel
     */
    public function setRunes(int $runes): ImportedAccountModel
    {
        $this->runes = $runes;
        return $this;
    }

    /**
     * @return array
     */
    public function getTransferList(): array
    {
        return $this->transferList;
    }

    /**
     * @param array $transferList
     * @return ImportedAccountModel
     */
    public function setTransferList(array $transferList): ImportedAccountModel
    {
        $this->transferList = $transferList;
        return $this;
    }

    /**
     * @return array
     */
    public function getSummonerIconList(): array
    {
        return $this->summonerIconList;
    }

    /**
     * @param array $summonerIconList
     * @return ImportedAccountModel
     */
    public function setSummonerIconList(array $summonerIconList): ImportedAccountModel
    {
        $this->summonerIconList = $summonerIconList;
        return $this;
    }

    /**
     * @return string
     */
    public function getPenaltyMillis(): string
    {
        return $this->penaltyMillis;
    }

    /**
     * @param string $penaltyMillis
     * @return ImportedAccountModel
     */
    public function setPenaltyMillis(string $penaltyMillis): ImportedAccountModel
    {
        $this->penaltyMillis = $penaltyMillis;
        return $this;
    }

    /**
     * @return string
     */
    public function getBannedUntil(): string
    {
        return $this->bannedUntil;
    }

    /**
     * @param string $bannedUntil
     * @return ImportedAccountModel
     */
    public function setBannedUntil(string $bannedUntil): ImportedAccountModel
    {
        $this->bannedUntil = $bannedUntil;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return ImportedAccountModel
     */
    public function setState(string $state): ImportedAccountModel
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return ImportedAccountModel
     */
    public function setRegion(string $region): ImportedAccountModel
    {
        $this->region = $region;
        return $this;
    }
}
