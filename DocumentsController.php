<?php

namespace Dsv\PortalBundle\Controller\Admin\Documents;

use \Dsv\PortalBundle\Controller\AbstractBaseController;
use Dsv\PortalBundle\DataGrid\DocumentsRecycleBinBuilder;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 31.03.17
 * Time: 16:23
 */
class DocumentsController extends AbstractBaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recycleBinAction()
    {
        return $this->render(
            'DsvPortalBundle:Admin/Documents:recycleBin.html.twig',
            ['grid' => $this->container->get('thrace_data_grid.provider')->get(DocumentsRecycleBinBuilder::IDENTIFIER)]
        );
    }

    /**
     *
     */
    public function documentsGroupAction()
    {
        return new JsonResponse($this->get('dsv_portal.model_document.document_groups_editor')->getData());
    }

    public function documentsMenuUpdatePositionAction(Request $request)
    {
        $menu_editor = $this->get('dsv_portal.model_document.document_menu_editor');
        $menu_editor->updatePosition($request->get('order'));

        $this->generateSuccessRowResponse();

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function documentsGroupEditAction(Request $request)
    {
        $operation = $request->get('oper');
        $editor    = $this->get('dsv_portal.model_document.document_groups_editor');
        $editor->setParameters($request->request->all());

        if (is_callable([$editor, $operation])) {
            call_user_func([$editor, $operation]);
        }

        return $this->generateSuccessRowResponse();
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function restoreDocumentAction(Request $request)
    {
        $recycler = $this->get('recycler_repository')->find($request->get('id'));

        if (!$recycler) {
            return $this->generateFailedRowResponse(['Entity not found']);
        }

        $this->get('dsv_portal.recycler.recycler_manager')->restore($recycler);

        return $this->generateSuccessRowResponse([]);
    }
}
