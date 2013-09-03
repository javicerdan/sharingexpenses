<?php

namespace Javicode\SharingExpensesBundle\Controller;

use Javicode\SharingExpensesBundle\Entity\SharingGroup;
use Javicode\SharingExpensesBundle\Form\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * @Route("/group")
 *
 */
class GroupController extends Controller
{
    /**
     * @Route("/view/{id}", name="view_group")
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction($id)
    {

        $group = $this->getGroupById($id);
        $this->checkUserPermission($group);

        return $this
            ->render(
                "JavicodeSharingExpensesBundle:Group:view.html.twig",
                array(
                    'group' => $group
                )
            );
    }

    /**
     * @Route("/new", name="new_group")
     * @Secure(roles="ROLE_USER")
     */
    public function newAction(Request $request)
    {
        $group = new SharingGroup();
        $group->addUser($this->getUser());
        $form = $this->createForm(new GroupType(), $group,
            array('action' => $this->generateUrl('new_group')));

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            return $this
                ->redirect(
                    $this->generateUrl('view_group',
                    array('id' => $group->getId()))
                );
        }

        return $this
            ->render(
                'JavicodeSharingExpensesBundle:Group:edit.html.twig',
                array('form' => $form->createView())
            );
    }

    /**
     * @Route("/edit/{id}", name="edit_group")
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {

        $group = $this->getGroupById($id);
        $this->checkUserPermission($group);

        $form = $this->createForm(new GroupType(), $group,
            array('action' => $this->generateUrl('edit_group', array('id' => $id))));

        $form->handleRequest($this->getRequest());

        if($form->isValid()){
            //In case someone messed with the form in the client side and erased its own user
            $this->checkUserPermission($group);

            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            return $this
                ->redirect(
                    $this->generateUrl('view_group',
                        array('id' => $group->getId()))
                );
        }

        return $this
            ->render(
                'JavicodeSharingExpensesBundle:Group:edit.html.twig',
                array('form' => $form->createView(), 'id' => $id)
            );
    }

    /**
     * @param $group_id
     * @return SharingGroup
     */
    private function getGroupById($group_id)
    {
        $group_repository = $this->getDoctrine()->getRepository("JavicodeSharingExpensesBundle:SharingGroup");
        return $group_repository->findOneById($group_id);
    }

    /**
     * @param SharingGroup $group
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    private function checkUserPermission( $group)
    {
        if(! $group || false === $group->doesUserBelongToThisGroup($this->getUser())){
            throw new AccessDeniedException();
        }
    }
}
