<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Entity\Account;
use App\Model\AccountImport;
use Doctrine\ORM\EntityManagerInterface;

class SaveImportedAccountsUseCase
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
     * @param AccountImport $importModel
     * @param string $region
     * @throws \Exception
     */
    public function save(AccountImport $importModel, string $region)
    {
        $accounts = $importModel->getAccounts();
        $count = 0;
        foreach ($accounts as $account) {
            $new = new Account();
            $new->setUsername($account->getUsername());
            $new->setPassword($account->getPassword());
            $new->setSummoner($account->getSummoner());
            $new->setLevel($account->getLevel());
            $new->setEmailStatus($account->getEmailStatus());
            $new->setRpBalance($account->getRpBalance());
            $new->setIpBalance($account->getIpBalance());
            $new->setRunePages($account->getRunePages());
            $new->setRefunds($account->getRefunds());
            $new->setPreviousSeasonRank($account->getPreviousSeasonRank());
            $new->setSoloQRank($account->getSoloQRank());
            $new->setLastPlay($account->getLastPlay());
            $new->setCheckedTime($account->getCheckedTime());
            $new->setRunes($account->getRunes());
            $new->setState((int)$account->getState());
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
