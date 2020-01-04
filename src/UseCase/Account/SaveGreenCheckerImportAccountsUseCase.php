<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;

class SaveGreenCheckerImportAccountsUseCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @param \App\Model\Import\GreenChecker\Account[] $accounts
     * @param string $region
     * @throws \Exception
     */
    public function save(array $accounts, string $region)
    {
        $count = 0;
        foreach ($accounts as $account) {
            $new = new Account();
            $new->setUsername($account->getMail());
            $new->setPassword($account->getPassword());
            $new->setSummoner($account->getPassword());
            $new->setSummoner($account->getPassword());
            $new->setRegion($region);
            $new->setCreatedAt(new \DateTime());
            $new->setUpdatedAt(new \DateTime());
            $new->setSold(false);

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
