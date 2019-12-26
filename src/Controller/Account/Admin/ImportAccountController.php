<?php
declare(strict_types=1);

namespace App\Controller\Account\Admin;

use App\Form\ImportFormType;
use App\Model\AccountImportFormModel;
use App\UseCase\Account\ReadAccountDataFromFileUseCase;
use App\UseCase\Account\SaveImportedAccountsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    public function __construct(
        ReadAccountDataFromFileUseCase $readFileUseCase,
        SaveImportedAccountsUseCase $saveAccountsUseCase
    ) {
        $this->readFileUseCase = $readFileUseCase;
        $this->saveAccountsUseCase = $saveAccountsUseCase;
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
            /** @var UploadedFile $file */
            $file = $form['file']->getData();
            if(!$file) {
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
            $model = $this->readFileUseCase->read($file);
            $this->saveAccountsUseCase->save($model);
        }

        return ['form' => $form->createView()];
    }
}


