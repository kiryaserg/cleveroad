<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 23.08.17
 * Time: 12:43
 */

namespace Dsv\PortalBundle\Repository;

use Dsv\PortalBundle\Entity\Portal;

/**
 * Class DocumentsGroupsRepository
 * @package Dsv\PortalBundle\Repository
 */
class DocumentsGroupsRepository extends AbstractBaseEntityRepository
{

    /**
     * Base Query Builder for all queries
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseQueryBuilder()
    {
        return $this->createQueryBuilder('dg');
    }

    /**
     * @param Portal $portal
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllPortalDocumentGroupsQuery(Portal $portal)
    {
        return $this->getBaseQueryBuilder()
            ->where('dg.portal = :portal')
            ->setParameter('portal', $portal)
            ->orderBy('dg.position', 'ASC');
    }

    /**
     * @param Portal $portal
     * @return array
     */
    public function getAllPortalDocumentGroups(Portal $portal)
    {
        return $this->getAllPortalDocumentGroupsQuery($portal)->getQuery()->getArrayResult();
    }
}
