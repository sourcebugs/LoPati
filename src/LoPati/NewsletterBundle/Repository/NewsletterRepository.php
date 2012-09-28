<?php

namespace Lopati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsletterRepository extends EntityRepository
{
	public function findPaginesNewsletter($id){
		
		
		$em = $this->getEntityManager();
		$query = $em->createQuery('SELECT n,p FROM NewsletterBundle:Newsletter n JOIN n.pagines p WHERE n.id = :id ');
		$query->setParameter('id',$id);
		
		return $query->getSingleResult();
	}
}


