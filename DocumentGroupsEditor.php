<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 23.08.17
 * Time: 15:46
 */

namespace Dsv\PortalBundle\Model\Document;

use Dsv\PortalBundle\Entity\DocumentsGroups;
use Dsv\PortalBundle\Entity\Portal;
use Dsv\PortalBundle\Repository\DocumentsGroupsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DocumentGroupsEditor
 * @package Dsv\PortalBundle\Model\Document
 */
class DocumentGroupsEditor
{
    /**
     * @var DocumentsGroupsRepository
     */
    private $documentsGroupsRepository;
    /**
     * @var Portal
     */
    private $portal;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var array
     */
    private $parameters;

    /**
     * DocumentGroupsEditor constructor.
     * @param DocumentsGroupsRepository $documentsGroupsRepository
     * @param Portal                    $portal
     * @param EntityManager             $entityManager
     */
    public function __construct(
        DocumentsGroupsRepository $documentsGroupsRepository,
        Portal $portal,
        EntityManager $entityManager
    ) {
        $this->documentsGroupsRepository = $documentsGroupsRepository;
        $this->portal                    = $portal;
        $this->entityManager             = $entityManager;

        $this->setParameters([]);

    }

    /**
     * @return array
     */
    public function getData()
    {
        $groups = $this->documentsGroupsRepository->getAllPortalDocumentGroups($this->portal);
        $ret    = [];
        foreach ($groups as $group) {
            $ret['rows'][] = array(
                'id'   => $group['id'],
                'cell' => array('', $group['title'], $group['position']),
            );
        }

        return $ret;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $resolver  = new OptionsResolver();
        $resolver->setDefaults(
            [
                'id'       => '',
                'title'    => '',
                'position' => 0,
                'oper' => 0,
            ]);

        $this->parameters = $resolver->resolve($parameters);

        return $this;
    }

    /**
     *
     */
    public function edit()
    {
        $this->setupEntity($this->findDocumentGroup());
        $this->entityManager->flush();
    }

    /**
     *
     */
    public function add()
    {
        $documentsGroups = new DocumentsGroups($this->portal);

        $this->setupEntity($documentsGroups);

        $this->entityManager->persist($documentsGroups);
        $this->entityManager->flush();
    }


    /**
     *
     */
    public function del()
    {
        $this->entityManager->remove($this->findDocumentGroup());
        $this->entityManager->flush();
    }

    /**
     * @return DocumentsGroups
     */
    private function findDocumentGroup()
    {
        /** @var  DocumentsGroups $documentsGroup */
        $documentsGroup = $this->documentsGroupsRepository->find($this->parameters['id']);

        if (!$documentsGroup) {
            throw new NotFoundHttpException('Could not find a document group');
        }

        return $documentsGroup;
    }

    /**
     * @param DocumentsGroups $documentsGroup
     */
    private function setupEntity(DocumentsGroups $documentsGroup)
    {
        $documentsGroup->setTitle($this->parameters['title']);
        $documentsGroup->setPosition($this->parameters['position']);
    }
}
