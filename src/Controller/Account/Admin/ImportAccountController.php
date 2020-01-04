<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Form\ImportFormType;
use App\Model\AccountImportFormModel;
use App\UseCase\Account\ReadAccountDataFromFileUseCase;
use App\UseCase\Account\ReadDataFromGreenCheckerFileUseCase;
use App\UseCase\Account\SaveGreenCheckerImportAccountsUseCase;
use App\UseCase\Account\SaveImportedAccountsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportAccountController extends AbstractController
{
    /**
     * @var ReadAccountDataFromFileUseCase
     */
    private $readFileUseCase;

    /**
     * @var SaveImportedAccountsUseCase
     */
    private $saveAccountsUseCase;

    /**
     * @var ReadDataFromGreenCheckerFileUseCase
     */
    private $readGreenCheckerFileUseCase;

    /**
     * @var SaveGreenCheckerImportAccountsUseCase
     */
    private $saveGreenCheckerAccountsUseCase;

    public function __construct(
        ReadAccountDataFromFileUseCase $readFileUseCase,
        ReadDataFromGreenCheckerFileUseCase $readGreenCheckerFileUseCase,
        SaveImportedAccountsUseCase $saveAccountsUseCase,
        SaveGreenCheckerImportAccountsUseCase $saveGreenCheckerAccountsUseCase
    ) {
        $this->readFileUseCase = $readFileUseCase;
        $this->saveAccountsUseCase = $saveAccountsUseCase;
        $this->readGreenCheckerFileUseCase = $readGreenCheckerFileUseCase;
        $this->saveGreenCheckerAccountsUseCase = $saveGreenCheckerAccountsUseCase;
    }

    /**
     * @Route("/admin/account/import", name="admin_import_account")
     * @Method({"GET", "POST"})
     * @Template("account/admin/import.html.twig")
     * @param Request $request
     * @return array|Response
     * @throws \Exception
     */
    public function importAccountAction(Request $request)
    {
        $model = new AccountImportFormModel();
        $form = $this->createForm(ImportFormType::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $model->getFile();
            //TODO: reikia nustatyti accountui kategorija (pagal kaina hierarchija)
            if ($model->getType() == AccountImportFormModel::TYPE_GREEN_CHECKER) {
                $import = $this->readGreenCheckerFileUseCase->read($file);
                $this->saveGreenCheckerAccountsUseCase->save($import, $model->getRegion());
            }
            if ($model->getType() == AccountImportFormModel::TYPE_WHITE_CHECKER) {
                $import = $this->readFileUseCase->read($file, $model->getType());
                $this->saveAccountsUseCase->save($import, $model->getRegion());
            }
        }

        return ['form' => $form->createView()];
    }
}


