<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="accounts")
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=255, unique=false)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(name="password", type="string", length=255, unique=false)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="sold", type="boolean")
     */
    private $sold = false;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="accounts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var Order|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Order")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     *
     */
    private $order;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="account_id", type="string", nullable=true)
     */
    private $accountId;

    /**
     * @var string
     *
     * @ORM\Column(name="summoner", type="string", nullable=true)
     */
    private $summoner;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer", nullable=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="email_status", type="string", nullable=true)
     */
    private $emailStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="rp_balance", type="decimal", precision=19, scale=2, nullable=true)
     */
    private $rpBalance;

    /**
     * @var float
     *
     * @ORM\Column(name="ip_balance", type="decimal", precision=19, scale=2, nullable=true)
     */
    private $ipBalance;

    /**
     * @var int
     *
     * @ORM\Column(name="rune_pages", type="integer", nullable=true)
     */
    private $runePages;

    /**
     * @var int
     *
     * @ORM\Column(name="refunds", type="integer", nullable=true)
     */
    private $refunds;

    /**
     * @var string
     *
     * @ORM\Column(name="previous_season_rank", type="string", nullable=true)
     */
    private $previousSeasonRank;

    /**
     * @var string
     *
     * @ORM\Column(name="solo_q_rank", type="string", nullable=true)
     */
    private $soloQRank;

    /**
     * @var string
     *
     * @ORM\Column(name="last_play", type="string", nullable=true)
     */
    private $lastPlay;

    /**
     * @var string
     *
     * @ORM\Column(name="checked_time", type="string", nullable=true)
     */
    private $checkedTime;

    /**
     * @var int
     *
     * @ORM\Column(name="runes", type="integer", nullable=true)
     */
    private $runes;

    /**
     * @var int
     *
     * @ORM\Column(name="state", type="integer", nullable=true)
     */
    private $state;

    /**
     * @var int
     *
     * @ORM\Column(name="region", type="integer", nullable=true)
     */
    private $region;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Account
     */
    public function setId(string $id): Account
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param mixed $username
     * @return Account
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $password
     * @return Account
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param bool $sold
     * @return Account
     */
    public function setSold(bool $sold): Account
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSold(): bool
    {
        return $this->sold;
    }

    /**
     * @param Product $product
     * @return Account
     */
    public function setProduct(Product $product): Account
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     * @return Account
     */
    public function setOrder(?Order $order): Account
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     * @return Account
     */
    public function setAccountId(string $accountId): Account
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummoner(): ?string
    {
        return $this->summoner;
    }

    /**
     * @param string $summoner
     * @return Account
     */
    public function setSummoner(string $summoner): Account
    {
        $this->summoner = $summoner;
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
    public function setLevel(int $level): Account
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailStatus(): ?string
    {
        return $this->emailStatus;
    }

    /**
     * @param string $emailStatus
     * @return Account
     */
    public function setEmailStatus(string $emailStatus): Account
    {
        $this->emailStatus = $emailStatus;
        return $this;
    }

    /**
     * @return float
     */
    public function getRpBalance(): ?float
    {
        return $this->rpBalance;
    }

    /**
     * @param float $rpBalance
     * @return Account
     */
    public function setRpBalance(float $rpBalance): Account
    {
        $this->rpBalance = $rpBalance;
        return $this;
    }

    /**
     * @return float
     */
    public function getIpBalance(): ?float
    {
        return $this->ipBalance;
    }

    /**
     * @param float $ipBalance
     * @return Account
     */
    public function setIpBalance(float $ipBalance): Account
    {
        $this->ipBalance = $ipBalance;
        return $this;
    }

    /**
     * @return int
     */
    public function getRunePages(): ?int
    {
        return $this->runePages;
    }

    /**
     * @param int $runePages
     * @return Account
     */
    public function setRunePages(int $runePages): Account
    {
        $this->runePages = $runePages;
        return $this;
    }

    /**
     * @return int
     */
    public function getRefunds(): ?int
    {
        return $this->refunds;
    }

    /**
     * @param int $refunds
     * @return Account
     */
    public function setRefunds(int $refunds): Account
    {
        $this->refunds = $refunds;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreviousSeasonRank(): ?string
    {
        return $this->previousSeasonRank;
    }

    /**
     * @param string $previousSeasonRank
     * @return Account
     */
    public function setPreviousSeasonRank(string $previousSeasonRank): Account
    {
        $this->previousSeasonRank = $previousSeasonRank;
        return $this;
    }

    /**
     * @return string
     */
    public function getSoloQRank(): ?string
    {
        return $this->soloQRank;
    }

    /**
     * @param string $soloQRank
     * @return Account
     */
    public function setSoloQRank(string $soloQRank): Account
    {
        $this->soloQRank = $soloQRank;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastPlay(): ?string
    {
        return $this->lastPlay;
    }

    /**
     * @param string $lastPlay
     * @return Account
     */
    public function setLastPlay(string $lastPlay): Account
    {
        $this->lastPlay = $lastPlay;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckedTime(): ?string
    {
        return $this->checkedTime;
    }

    /**
     * @param string $checkedTime
     * @return Account
     */
    public function setCheckedTime(string $checkedTime): Account
    {
        $this->checkedTime = $checkedTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getRunes(): ?int
    {
        return $this->runes;
    }

    /**
     * @param int $runes
     * @return Account
     */
    public function setRunes(int $runes): Account
    {
        $this->runes = $runes;
        return $this;
    }

    /**
     * @return int
     */
    public function getState(): ?int
    {
        return $this->state;
    }

    /**
     * @param int $state
     * @return Account
     */
    public function setState(int $state): Account
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return int
     */
    public function getRegion(): ?int
    {
        return $this->region;
    }

    /**
     * @param int $region
     * @return Account
     */
    public function setRegion(int $region): Account
    {
        $this->region = $region;
        return $this;
    }


}
