<?php

namespace App\Utils;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class QueryPaginator extends Paginator
{
    protected $builder;

    protected $itemsPerPage;

    public function __construct(QueryBuilder $builder, int $itemsPerPage)
    {
        $this->builder = $builder;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return integer
     */
    public function getPageCount(): int
    {
        $query = $this->builder->getQuery();
        $paginator = new Paginator($query, false);
        return ceil(count($paginator) / $this->itemsPerPage);
    }

    /**
     * @param integer
     * @return array
     */
    public function getPage(int $pageNumber): array
    {
        if (($pageNumber < 1) || ($pageNumber > $this->getPageCount())) {
            return array();
        }

        return $this->builder
            ->setFirstResult(($pageNumber - 1) * $this->itemsPerPage)
            ->setMaxResults($this->itemsPerPage)
            ->getQuery()
            ->getResult();
    }
}
