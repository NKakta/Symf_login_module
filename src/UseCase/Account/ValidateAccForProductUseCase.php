<?php
declare(strict_types=1);

namespace App\UseCase\Account;

use App\Entity\Account;
use App\Entity\Product;

class ValidateAccForProductUseCase
{
    /**
     * @param Product $product
     * @param Account $account
     * @return bool
     * @throws \Exception
     */
    public function isValid(Product $product, Account $account): bool
    {
        $today = new \DateTime();
        $daysDiff = $today->diff(new \DateTime($account->getLastPlay()))->days;

        if ($product->getMinLevel() && $account->getLevel() < $product->getMinLevel()
            || $product->getMinChampCount() && $account->getChampCount() < $product->getMinChampCount()
            || $product->getMinSkinCount() && $account->getSkinCount() < $product->getMinSkinCount()
            || $product->getMinRpCount() && $account->getRpBalance() < $product->getMinRpCount()
            || $product->getRanks() && in_array($account->getSoloQRank(), $product->getRanks())
            || $product->getDaysNotPlayed() && $daysDiff < $product->getDaysNotPlayed()
        ) {
            return false;
        }

        return true;
    }
}
