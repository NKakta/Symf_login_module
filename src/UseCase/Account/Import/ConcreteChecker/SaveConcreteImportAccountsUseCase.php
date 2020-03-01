<?php
declare(strict_types=1);

namespace App\UseCase\Account\Import\ConcreteChecker;

use App\Entity\Account;
use App\Enum\Regions;
use App\UseCase\Account\SetProductUseCase;
use Doctrine\ORM\EntityManagerInterface;

class SaveConcreteImportAccountsUseCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SetProductUseCase
     */
    private $setProductUseCase;

    public function __construct(
        EntityManagerInterface $em,
        SetProductUseCase $setProductUseCase
    ) {
        $this->em = $em;
        $this->setProductUseCase = $setProductUseCase;
    }

    /**
     * @param \App\Model\Import\ConcreteChecker\Account[] $accounts
     * @throws \Exception
     */
    public function save(array $accounts)
    {
        $count = 0;
        foreach ($accounts as $account) {
            $new = new Account();
            $new->setUsername($account->getEmail());
            $new->setSummoner($account->getPayload()->getUsername());
            $new->setPassword($account->getPayload()->getPassword());
            $new->setLevel($account->getLevel());
            $new->setChampCount($account->getChampionCount());
            $new->setSkinCount($account->getSkinsCount());
            $new->setRpBalance($account->getRp());
            $new->setLastPlay($account->getLastPlayed());
            $new->setSoloQRank($account->getCurrent());
            $new->setRegion($account->getRegion());
            $new->setCreatedAt(new \DateTime());
            $new->setUpdatedAt(new \DateTime());
            $new->setSold(false);

            $this->setProductUseCase->setProduct($new);

            $this->em->persist($new);
            $count+=1;

            if($count == 1000) {
                $this->em->flush();
                $count=0;
                $this->em->clear();
            }
            $this->em->flush();
        }
    }

}
