<?php

namespace Astina\Bundle\RedirectManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class MapRepository
 *
 * @package   Astina\Bundle\RedirectManagerBundle\Entity
 * @author    Philipp Kräutli <pkraeutli@astina.ch>
 * @copyright 2014 Astina AG (http://astina.ch)
 */
class MapRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.group', 'g')
            ->orderBy('g.priority')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $url
     * @param string $path
     *
     * @return Map[]
     */
    public function findForUrlOrPath($url, $path)
    {
        return $this->createQueryBuilder('m')
            ->where('m.urlFrom = :path')
            ->orWhere('m.urlFrom = :url')
            ->setParameter('path', $path)
            ->setParameter('url', $url)
            ->leftJoin('m.group', 'g')
            ->orderBy('g.priority')
            ->addOrderBy('m.urlFrom', 'desc') // urls starting with "http" will be sorted before urls starting with "/"
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $url
     * @param string $path
     *
     * @return Map
     */
    public function findOneForUrlOrPath($url, $path)
    {
        $maps = $this->findForUrlOrPath($url, $path);

        if (empty($maps)) {
            return null;
        }

        return current($maps);
    }
}
