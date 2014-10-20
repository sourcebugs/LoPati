<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use Lopati\NewsletterBundle\Repository\NewsletterUserRepository;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NewsletterUserAdminController extends Controller
{
    /**
     * Export action
     *
     * @param Request $request request
     *
     * @return Response|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \RuntimeException
     * @throws AccessDeniedException
     */
    public function exportAction(Request $request)
    {
        if (false === $this->admin->isGranted('EXPORT')) {
            throw new AccessDeniedException();
        }
        $what = Array(
            'id',
            'email',
            'idioma',
            'active',
            'fail',
            'createdString'
        );
        $format = $request->get('format');
        $allowedExportFormats = (array)$this->admin->getExportFormats();
        if (!in_array($format, $allowedExportFormats)) {
            throw new \RuntimeException(
                sprintf(
                    'Export in format `%s` is not allowed for class: `%s`. Allowed formats are: `%s`',
                    $format,
                    $this->admin->getClass(),
                    implode(', ', $allowedExportFormats)
                )
            );
        }
        $filename = sprintf(
            'export_%s_%s.%s',
            strtolower(substr($this->admin->getClass(), strripos($this->admin->getClass(), '\\') + 1)),
            date('Y_m_d_H_i_s', strtotime('now')),
            $format
        );
        $datagrid = $this->admin->getDatagrid();
        $datagrid->buildPager();
        $flick = $this->admin->getModelManager()->getDataSourceIterator($datagrid, $what);

        return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $flick);
    }

    /**
     * Batch pre set group action
     *
     * @param ProxyQueryInterface $selectedModelQuery
     *
     * @return RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function batchActionGroup(ProxyQueryInterface $selectedModelQuery)
    {
        if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException();
        }
        /** @var Router $router */
        $router = $this->get('router');
        /** @var Session $session */
        $session = $this->get('session');
        /** @var Request $request */
        $request = $this->get('request');
        $session->getFlashBag()->add('setgroupuids', $request->get('idx'));

        return new RedirectResponse($router->generate('admin_lopati_newsletter_newsletteruser_group'));
    }

    /**
     * Pre set group action
     *
     * @return Response
     */
    public function groupAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('NewsletterBundle:NewsletterGroup')->getActiveItemsSortByNameQuery()->getResult();
        $setgroupuids = $this->get('session')->getFlashBag()->get('setgroupuids');
        $users = new ArrayCollection();
        foreach ($setgroupuids[0] as $uid) {
            $user = $em->getRepository('NewsletterBundle:NewsletterUser')->find($uid);
            if ($user) {
                $users->add($user);
            }
        }

        return $this->render('AdminBundle:Newsletter:AddUserToGroup/preset_group_form.html.twig', array(
                'users'  => $users,
                'groups' => $groups,
            ));
    }

    /**
     * Final set group action
     *
     * @return Response
     */
    public function setgroupAction()
    {

    }
}
