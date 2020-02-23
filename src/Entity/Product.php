<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     * @Assert\NotBlank
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="price", type="decimal", precision=19, scale=2, nullable=false)
     *
     * @Serializer\Expose()
     * @Serializer\Type("double")
     */
    private $price;

    /**
     * @var Account[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Account",
     *     mappedBy="product",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $accounts;

    /**
     * @ORM\Column(name="photo_filename", type="string", length=255, unique=false)
     * @Assert\NotBlank
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    private $photoFilename;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var bool
     *
     * @ORM\Column(name="sold", type="boolean")
     *
     * @Serializer\Expose()
     * @Serializer\Type("boolean")
     */
    private $sold = false;

    /**
     * @var int
     *
     * @ORM\Column(name="days_not_played", type="integer", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    private $daysNotPlayed;

    /**
     * @var int
     *
     * @ORM\Column(name="min_level", type="integer", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    private $minLevel;

    /**
     * @var int
     *
     * @ORM\Column(name="min_champ_count", type="integer", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    private $minChampCount;

    /**
     * @var int
     *
     * @ORM\Column(name="min_skin_count", type="integer", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    private $minSkinCount;

    /**
     * @var int
     *
     * @ORM\Column(name="min_rp_count", type="integer", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    private $minRpCount;

    /**
     * @var string
     *
     * @ORM\Column(name="ranks", type="array", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Type("array<string>")
     */
    private $ranks;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose()
     * @Serializer\Type("datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose()
     * @Serializer\Type("datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->ranks = [];
    }

    /**
     * @return mixed
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Product
     */
    public function setId(string $id): Product
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string|null $price
     */
    public function setPrice(?string $price): void
    {
        $this->price = $price;
    }

    /**
     * @param bool $sold
     * @return Product
     */
    public function setSold(bool $sold): Product
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
     * @param mixed $photoFilename
     * @return Product
     */
    public function setPhotoFilename($photoFilename)
    {
        $this->photoFilename = $photoFilename;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoFilename()
    {
        return $this->photoFilename;
    }

    /**
     * @param Account[] $accounts
     * @return Product
     */
    public function setAccounts(array $accounts): Product
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * @return Account[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    /**
     * @param Category $category
     * @return Product
     */
    public function setCategory(Category $category): Product
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @Serializer\Expose()
     * @Serializer\VirtualProperty("inStock")
     *
     * @return int
     */
    public function getInStock(): int
    {
        $count = 0;
        foreach ($this->accounts as $account) {
            if (!$account->isSold()) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return int
     */
    public function getDaysNotPlayed(): ?int
    {
        return $this->daysNotPlayed;
    }

    /**
     * @param int $daysNotPlayed
     * @return Product
     */
    public function setDaysNotPlayed(int $daysNotPlayed): Product
    {
        $this->daysNotPlayed = $daysNotPlayed;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinLevel(): ?int
    {
        return $this->minLevel;
    }

    /**
     * @param int $minLevel
     * @return Product
     */
    public function setMinLevel(int $minLevel): Product
    {
        $this->minLevel = $minLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinChampCount(): ?int
    {
        return $this->minChampCount;
    }

    /**
     * @param int $minChampCount
     * @return Product
     */
    public function setMinChampCount(?int $minChampCount): Product
    {
        $this->minChampCount = $minChampCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinSkinCount(): ?int
    {
        return $this->minSkinCount;
    }

    /**
     * @param int $minSkinCount
     * @return Product
     */
    public function setMinSkinCount(?int $minSkinCount): Product
    {
        $this->minSkinCount = $minSkinCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinRpCount(): ?int
    {
        return $this->minRpCount;
    }

    /**
     * @param int $minRpCount
     * @return Product
     */
    public function setMinRpCount(?int $minRpCount): Product
    {
        $this->minRpCount = $minRpCount;
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
     * @return Product
     */
    public function setRanks(?array $ranks): Product
    {
        $this->ranks = $ranks;
        return $this;
    }
}
