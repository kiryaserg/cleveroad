<?php

namespace PortalBundle\Model\Document;

use Dsv\PortalBundle\Model\Document\DocumentGroupsEditor;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * Class DocumentGroupsEditorTest
 * @package PortalBundle\Model\Document
 */
class DocumentGroupsEditorTest extends \Codeception\Test\Unit
{
    /**
     * @var \CodeTester
     */
    protected $tester;
    /**
     * @var  DocumentGroupsEditor
     */
    private $object;

    /**
     *
     */
    protected function _before()
    {
        $this->tester->initCurrentPortal($this);
        $this->object = $this->tester->get('dsv_portal.model_document.document_groups_editor');
    }

    /**
     *
     */
    protected function _after()
    {
    }

    // tests

    /**
     *
     */
    public function testGetData()
    {
        $this->assertEmpty($this->object->getData());
    }

    /**
     *
     */
    public function testGetDataReturnsArray()
    {
        $this->tester->haveInRepository(
            'Dsv\PortalBundle\Entity\DocumentsGroups',
            [
                'portal' => $this->tester->getCurrentPortal(),
                'title'  => 'title',
            ]
        );
        $result = $this->object->getData();
        $this->assertArrayHasKey('rows', $result);
        $this->assertArrayHasKey('cell', $result['rows'][0]);
        $this->assertArrayHasKey('id', $result['rows'][0]);
        $this->assertArraySubset($result['rows'][0]['cell'], ['', 'title', 0]);
    }

    /**
     *
     */
    public function testEditSavesEntity()
    {
        $this->tester->haveInRepository(
            'Dsv\PortalBundle\Entity\DocumentsGroups',
            [
                'portal' => $this->tester->getCurrentPortal(),
                'title'  => 'title',
                'position'  => '1',
            ]
        );

        $id = $this->tester->grabFromRepository( 'Dsv\PortalBundle\Entity\DocumentsGroups', 'id', ['title'=>'title']);

        $data = ['id'=>$id, 'title'=>'title2', 'position'=>10];
        $this->object->setParameters($data);
        $this->object->edit();

        $this->tester->seeInRepository('Dsv\PortalBundle\Entity\DocumentsGroups', $data);
    }

    /**
     *
     */
    public function testDeleteDeletesEntity()
    {
        $this->tester->haveInRepository(
            'Dsv\PortalBundle\Entity\DocumentsGroups',
            [
                'portal' => $this->tester->getCurrentPortal(),
                'title'  => 'title',
                'position'  => '1',
            ]
        );

        $id = $this->tester->grabFromRepository( 'Dsv\PortalBundle\Entity\DocumentsGroups', 'id', ['title'=>'title']);
        $data = ['id'=>$id];

        $this->tester->seeInRepository('Dsv\PortalBundle\Entity\DocumentsGroups', $data);
        $this->object->setParameters($data);
        $this->object->del();

        $this->tester->dontSeeInRepository('Dsv\PortalBundle\Entity\DocumentsGroups', $data);
    }

    /**
     *
     */
    public function testAddAddsNewEntity()
    {

        $data = ['title'=>'title', 'position'  => '1' ];

        $this->tester->dontSeeInRepository('Dsv\PortalBundle\Entity\DocumentsGroups', $data);
        $this->object->setParameters($data);
        $this->object->add();

        $this->tester->seeInRepository('Dsv\PortalBundle\Entity\DocumentsGroups', $data + ['portal' => $this->tester->getCurrentPortal()] );
    }


    /**
     * @dataProvider exceptionMethodsProvider
     */
    public function testEditThrowsException($method)
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Could not find a document group');

        $data = ['id'=>'', 'title'=>'title2', 'position'=>10];
        $this->object->setParameters($data);
        $this->object->$method();
    }

    /**
     * @return array
     */
    public function exceptionMethodsProvider()
    {
        return [['edit'],['del']];
    }

    /**
     *
     */
    public function testSetParameters()
    {
       $this->expectException(UndefinedOptionsException::class);
       $this->object->setParameters(['test'=>'']);
    }
}