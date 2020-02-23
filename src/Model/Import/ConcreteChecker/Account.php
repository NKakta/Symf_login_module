<?php
declare(strict_types=1);

namespace App\Model\Import\ConcreteChecker;

use JMS\Serializer\Annotation as Serializer;

class Account
{
    /**
     * @var Payload
     * @Serializer\Type("App\Model\Import\ConcreteChecker\Payload")
     * @Serializer\SerializedName("PayLoad")
     */
    protected $payload;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Password")
     */
    protected $password;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("SummonerName")
     */
    protected $summonerName;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Region")
     */
    protected $region;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("Level")
     */
    protected $level;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("Verified")
     */
    protected $verified;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("MailAccess")
     */
    protected $mailAccess;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("BE")
     */
    protected $be;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("RP")
     */
    protected $rp;

    /**
     * @var array
     * @Serializer\Type("array<integer>")
     * @Serializer\SerializedName("Champions")
     */
    protected $champions;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Email")
     */
    protected $email;

    /**
     * @var array
     * @Serializer\Type("array<integer>")
     * @Serializer\SerializedName("Skins")
     */
    protected $skins;

    /**
     * @var int
     * @Serializer\Type("array<integer>")
     * @Serializer\SerializedName("Icons")
     */
    protected $icons;

    /**
     * @var array
     * @Serializer\Type("array<string>")
     * @Serializer\SerializedName("Ranks")
     */
    protected $ranks;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("TFT")
     */
    protected $tft;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Flex")
     */
    protected $flex;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Current")
     */
    protected $current;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("Previous")
     */
    protected $previous;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("AvailableRefunds")
     */
    protected $availableRefunds;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("ChampionCount")
     */
    protected $championCount;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("SkinsCount")
     */
    protected $skinsCount;

    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("IconsCount")
     */
    protected $iconsCount;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("LastPlayed")
     */
    protected $lastPlayed;

    /**
     * @return Payload
     */
    public function getPayload(): Payload
    {
        return $this->payload;
    }

    /**
     * @param Payload $payload
     * @return Account
     */
    public function setPayload(Payload $payload): ?Account
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Account
     */
    public function setPassword(?string $password): ?Account
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummonerName(): ?string
    {
        return $this->summonerName;
    }

    /**
     * @param string $summonerName
     * @return Account
     */
    public function setSummonerName(?string $summonerName): ?Account
    {
        $this->summonerName = $summonerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return Account
     */
    public function setRegion(?string $region): ?Account
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return Account
     */
    public function setLevel(?int $level): ?Account
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     * @return Account
     */
    public function setVerified(?bool $verified): ?Account
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMailAccess(): ?bool
    {
        return $this->mailAccess;
    }

    /**
     * @param bool $mailAccess
     * @return Account
     */
    public function setMailAccess(?bool $mailAccess): ?Account
    {
        $this->mailAccess = $mailAccess;
        return $this;
    }

    /**
     * @return int
     */
    public function getBe(): ?int
    {
        return $this->be;
    }

    /**
     * @param int $be
     * @return Account
     */
    public function setBe(?int $be): ?Account
    {
        $this->be = $be;
        return $this;
    }

    /**
     * @return int
     */
    public function getRp(): ?int
    {
        return $this->rp;
    }

    /**
     * @param int $rp
     * @return Account
     */
    public function setRp(?int $rp): ?Account
    {
        $this->rp = $rp;
        return $this;
    }

    /**
     * @return array
     */
    public function getChampions(): ?array
    {
        return $this->champions;
    }

    /**
     * @param array $champions
     * @return Account
     */
    public function setChampions(array $champions): ?Account
    {
        $this->champions = $champions;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Account
     */
    public function setEmail(?string $email): ?Account
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getSkins(): ?array
    {
        return $this->skins;
    }

    /**
     * @param array $skins
     * @return Account
     */
    public function setSkins(array $skins): ?Account
    {
        $this->skins = $skins;
        return $this;
    }

    /**
     * @return int
     */
    public function getIcons(): ?int
    {
        return $this->icons;
    }

    /**
     * @param int $icons
     * @return Account
     */
    public function setIcons(?int $icons): ?Account
    {
        $this->icons = $icons;
        return $this;
    }

    /**
     * @return array
     */
    public function getRanks(): ?array
    {
        return $this->ranks;
    }

    /**
     * @param array $ranks
     * @return Account
     */
    public function setRanks(array $ranks): ?Account
    {
        $this->ranks = $ranks;
        return $this;
    }

    /**
     * @return string
     */
    public function getTft(): ?string
    {
        return $this->tft;
    }

    /**
     * @param string $tft
     * @return Account
     */
    public function setTft(?string $tft): ?Account
    {
        $this->tft = $tft;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlex(): ?string
    {
        return $this->flex;
    }

    /**
     * @param string $flex
     * @return Account
     */
    public function setFlex(?string $flex): ?Account
    {
        $this->flex = $flex;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrent(): ?string
    {
        return $this->current;
    }

    /**
     * @param string $current
     * @return Account
     */
    public function setCurrent(?string $current): ?Account
    {
        $this->current = $current;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    /**
     * @param string $previous
     * @return Account
     */
    public function setPrevious(?string $previous): ?Account
    {
        $this->previous = $previous;
        return $this;
    }

    /**
     * @return int
     */
    public function getAvailableRefunds(): ?int
    {
        return $this->availableRefunds;
    }

    /**
     * @param int $availableRefunds
     * @return Account
     */
    public function setAvailableRefunds(?int $availableRefunds): ?Account
    {
        $this->availableRefunds = $availableRefunds;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionCount(): ?int
    {
        return $this->championCount;
    }

    /**
     * @param int $championCount
     * @return Account
     */
    public function setChampionCount(?int $championCount): ?Account
    {
        $this->championCount = $championCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getSkinsCount(): ?int
    {
        return $this->skinsCount;
    }

    /**
     * @param int $skinsCount
     * @return Account
     */
    public function setSkinsCount(?int $skinsCount): ?Account
    {
        $this->skinsCount = $skinsCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getIconsCount(): ?int
    {
        return $this->iconsCount;
    }

    /**
     * @param int $iconsCount
     * @return Account
     */
    public function setIconsCount(?int $iconsCount): ?Account
    {
        $this->iconsCount = $iconsCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastPlayed(): ?string
    {
        return $this->lastPlayed;
    }

    /**
     * @param string $lastPlayed
     * @return Account
     */
    public function setLastPlayed(?string $lastPlayed): ?Account
    {
        $this->lastPlayed = $lastPlayed;
        return $this;
    }
}
