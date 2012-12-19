<?php
namespace LoPati\NewsletterBundle\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use LoPati\NewsletterBundle\Entity\NewsletterUser;

class NewsletterSaveUsersCommand extends ContainerAwareCommand {
	protected function configure() {
		$this->setName('newsletter:save:users')
				->setDefinition(
						array(
								new InputArgument('fitxer',
										InputArgument::OPTIONAL,
										'fitxer'
										),))
				->setDescription('Arxiu amb correus')
				->setHelp(
						<<<EOT
La comanda <info>newsltter:ssave:users</info> importa usuaris a la base de dades.
EOT
				);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$contenedor = $this->getContainer();
		$em = $contenedor->get('doctrine')->getEntityManager();
		
		$max = $input->getArgument('fitxer');

		$filas=file($max);
		
		
		$handle = fopen($max, 'r');
				
		$i=0;
		$numero_fila=0;
		
        $z=0;

		// mientras exista una fila
		while (!feof($handle)){

		$row=fgets($handle, 4096);
			// genero array con por medio del separador "," que es el que tiene el archivo txt
            $sql=str_replace(" ", "", $row);
            $sql=str_replace(",", "", $sql);

			$query = $em
			->createQuery(
					'SELECT u FROM NewsletterBundle:NewsletterUser u  WHERE
					u.email = :mail');
			$query->setParameter('mail', $sql);
			
			$query->setMaxResults('1');
			$existeix = $query->getOneOrNullResult();
			
						if ($existeix->getEmail() == $sql){


                            $output->writeln("No s'ha pogut afegir el email: ".$sql);
                            $z++;



						}else{
                            $user= new newsletterUser();
                            $user->setEmail($sql);
                            $user->setActive('1');
                            $user->setIdioma('ca');
                            $em->persist($user);

                            $output->writeln("S'ha afegit un registre nou amb email: ".$sql);
                            $i++;
                            $numero_fila++;


                        }
			
		}
		$output->writeln('Guardant mails....');
		$em->flush();
        $output->writeln($z. ' mails fallats');
		$output->writeln($i. ' mails guardats');

	}
}

