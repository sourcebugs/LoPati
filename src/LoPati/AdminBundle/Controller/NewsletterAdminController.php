<?php
namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\Newsletter;
use LoPati\NewsletterBundle\Entity\NewsletterSend;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class NewsletterAdminController extends Controller
{
    public function enviarAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Newsletter $news */
        $news = $em->getRepository('NewsletterBundle:Newsletter')->findOneBy(array('id' => $id));
        if ($news->getEstat() == null) {
            $news->setEstat('Waiting');
            $news->setIniciEnviament(new \DateTime('now'));
            $query = $em->createQuery(
                'SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.fail >= :fail AND u.active = :actiu '
            );
            $query->setParameter('fail', '4');
            $query->setParameter('actiu', '1');
            $users = $query->getResult();
            foreach ($users as $user) {

                $em->remove($user);

            }
            $em->flush();
            $query = $em->createQuery('SELECT u FROM NewsletterBundle:NewsletterUser u WHERE u.active = :actiu ');
            $query->setParameter('actiu', '1');
            $users = $query->getResult();
            foreach ($users as $user) {
                $newsletterSend = new NewsletterSend();
                $newsletterSend->setUser($user);
                $newsletterSend->setNewsletter($news);
                $em->persist($newsletterSend);
            }
            $news->setSubscrits(count($users));
            $em->flush();
        }

        return $this->redirect('../list');
    }

    public function previewAction($id)
    {
        $visualitzar_correctament = "Clica aquí per visualitzar correctament";
        $baixa = "Clica aquí per donar-te de baixa";
        $lloc = "Lloc";
        $data = "Data";
        $publicat = "Publicat";
        $links = "Enllaços";
        $organitza = "Organitza";
        $suport = "Amb el suport de";
        $follow = "Segueix-nos a";
        $colabora = "Col·labora";
        $butlleti = "Butlletí";

        $em = $this->getDoctrine()->getManager();
        $pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);
        $host = 'dev' == $this->container->get('kernel')->getEnvironment() ? 'http://lopati.local'
            : 'http://lopati.cat';

        //$object->getId();
        return $this->render(
            'AdminBundle:Newsletter:preview.html.twig',
            array(
                'id'                       => $id,
                'host'                     => $host,
                'pagines'                  => $pagines,
                'idioma'                   => 'ca',
                'visualitzar_correctament' => $visualitzar_correctament,
                'baixa'                    => $baixa,
                'lloc'                     => $lloc,
                'data'                     => $data,
                'publicat'                 => $publicat,
                'links'                    => $links,
                'organitza'                => $organitza,
                'suport'                   => $suport,
                'follow'                   => $follow,
                'colabora'                 => $colabora,
                'butlleti'                 => $butlleti
            )
        );
    }

    public function testAction($id)
    {
        $visualitzar_correctament = "Clica aquí per visualitzar correctament";
        $baixa = "Clica aquí per donar-te de baixa";
        $lloc = "Lloc";
        $data = "Data";
        $publicat = "Publicat";
        $links = "Enllaços";
        $organitza = "Organitza";
        $suport = "Amb el suport de";
        $follow = "Segueix-nos a";
        $colabora = "Col·labora";
        $butlleti = "Butlletí";

        $em = $this->getDoctrine()->getManager();
        //$userName = $this->container->get('security.context')->getToken()->getUser()->getUsername();
        //$user = $em->getRepository('ApplicationSonataUserBundle:User')->findOneByUsername($userName);
        $newsletter2 = $em->getRepository('NewsletterBundle:Newsletter')->find($id);
        $pagines = $em->getRepository('NewsletterBundle:Newsletter')->findPaginesNewsletterById($id);

        $host = 'dev' == $this->container->get('kernel')->getEnvironment(
        ) ? 'http://lopati.local' : 'http://lopati.cat';

        $contenido = $this->renderView(
            'NewsletterBundle:Default:mail.html.twig',
            array(
                'host'                     => $host,
                'pagines'                  => $pagines,
                'idioma'                   => 'ca',
                'visualitzar_correctament' => $visualitzar_correctament,
                'baixa'                    => $baixa,
                'lloc'                     => $lloc,
                'data'                     => $data,
                'publicat'                 => $publicat,
                'links'                    => $links,
                'organitza'                => $organitza,
                'suport'                   => $suport,
                'follow'                   => $follow,
                'colabora'                 => $colabora,
                'butlleti'                 => $butlleti
            )
        );

        $message = \Swift_Message::newInstance()
            //->setSubject('Lo Pati - Newsletter ' . $newsletter2->getDataNewsletter()->format('d-m-Y'))
            ->setSubject('[TEST] Butlletí nº ' . $newsletter2->getNumero())
            //->setFrom($config->getEmail())
            ->setFrom(array("butlleti@lopati.cat" => "Centre d'Art Lo Pati"))
            //->setTo($user->getEmail())
            ->setTo(array($this->container->getParameter('newsleterEmailDestination1')))
            ->setCc(
                array(
                    $this->container->getParameter('newsleterEmailDestination2'),
                    $this->container->getParameter('newsleterEmailDestination3')
                )
            )
            ->setBody($contenido, 'text/html');
        $this->get('mailer')->send($message);
        $this->get('session')->getFlashBag()->add(
            'sonata_flash_success',
            'Mail de test enviat correctament a les bústies: ' . $this->container->getParameter(
                'newsleterEmailDestination1'
            ) . ', ' . $this->container->getParameter(
                'newsleterEmailDestination2'
            ) . ' i ' . $this->container->getParameter('newsleterEmailDestination3')
        );
        $news = $em->getRepository('NewsletterBundle:Newsletter')->findOneBy(array('id' => $id));
        $news->setTest('1');
        $em->flush();

        return $this->redirect('../list');
    }
}
