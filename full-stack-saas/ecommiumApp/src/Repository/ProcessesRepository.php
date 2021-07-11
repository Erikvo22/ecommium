<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ProcessesRepository extends DocumentRepository
{
    public function findAllOrderedByCreatedAt()
    {
        return $this->createQueryBuilder()
            ->sort('createdAt', 'ASC')
            ->getQuery()
            ->execute();
    }
}