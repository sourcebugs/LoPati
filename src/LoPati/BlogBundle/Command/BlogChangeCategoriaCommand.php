<?php

namespace LoPati\BlogBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use LoPati\BlogBundle\Entity\Pagina;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class BlogChangeCategoriaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('blog:change:categoria')
            ->setDescription('assigna categoria Arxiu a pagines caducades')
            ->setHelp(
                <<<EOT
La comanda <info>blog:change:categoria</info> assigna categoria Arxiu a pagines caducades.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contenedor = $this->getContainer();
        /** @var EntityManager $em */
        $em = $contenedor->get('doctrine')->getManager();
        /** @var Query $query */
        $query = $em
            ->createQuery(
                'SELECT p FROM BlogBundle:Pagina p JOIN p.categoria cat LEFT JOIN p.subCategoria sub  WHERE
             p.data_caducitat <= :avui AND p.data_caducitat IS NOT NULL and p.actiu = TRUE'
            );
        $query->setParameter('avui', new \DateTime('today'));
        $pagines = $query->getResult();
        $query = $em
            ->createQuery(
                'SELECT c FROM MenuBundle:Categoria c WHERE
                c.nom = :cat'
            );
        $query->setParameter('cat', 'Arxiu');
        $categoria = $query->getSingleResult();
        /** @var Pagina $pagina */
        foreach ($pagines as $pagina) {
            //$output->writeln('canvi estat:' . count($newsletter));
            $pagina->setCategoria($categoria);
            $pagina->setSubcategoria(null);
            $em->persist($pagina);
        }
        $em->flush();
    }
}
