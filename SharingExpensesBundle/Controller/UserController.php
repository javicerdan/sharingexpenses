<?php

namespace Javicode\SharingExpensesBundle\Controller;

use Javicode\SharingExpensesBundle\Entity\User;
use Javicode\SharingExpensesBundle\Form\ExpenseType;
use Javicode\SharingExpensesBundle\Entity\Expense;
use Javicode\SharingExpensesBundle\Form\GroupType;
use Javicode\SharingExpensesBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;

class UserController extends Controller
{

    /**
     * @Route("/user/view", name="view_user")
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction()
    {

        return $this
            ->render(
                "JavicodeSharingExpensesBundle:User:view.html.twig"
            );
    }

    /**
     * @Route("/user/new", name="new_user")
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            //Encode password before storing it
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $encoded_password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encoded_password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this
                ->redirect(
                    $this->generateUrl('view_user',
                        array('id' => $user->getId()))
                );
        }

        return $this
            ->render(
                "JavicodeSharingExpensesBundle:User:new.html.twig",
                array('form' => $form->createView())
            );
    }

    /**
     * @Route("/expense/new/{group_id}/{user_id}", name="new_expense")
     * @Secure(roles="ROLE_USER")
     */
    public function newExpenseAction($group_id, $user_id)
    {
        $expense = new Expense();
        //retrieve group and user
        $user_repository = $this->getDoctrine()->getRepository("JavicodeSharingExpensesBundle:User");
        $group_repository = $this->getDoctrine()->getRepository("JavicodeSharingExpensesBundle:SharingGroup");
        $user = $user_repository->findOneById($user_id);
        $group = $group_repository->findOneById($group_id);

        //check the user didn't do anything naughty to the form
        if(! $group || ! $user ||
            !$group->doesUserBelongToThisGroup($this->getUser()) || ! $group->doesUserBelongToThisGroup($user)){
            throw new AccessDeniedException();
        }
        $expense->setUser($user);
        $expense->setSharingGroup($group);
        $form = $this->createForm(new ExpenseType(), $expense);

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($expense);
            $em->flush();

            return $this
                ->redirect($this->generateUrl('view_group',
                    array('id' => $group_id))
                );
        }
        return $this
            ->render(
                "JavicodeSharingExpensesBundle:User:new_expense.html.twig",
                array('form' => $form->createView(), 'user_id' => $user_id, 'group_id' => $group_id)
            );
    }

    /**
    * @Secure(roles="ROLE_USER")
    */
    public function lastExpensesAction()
    {
        return $this
            ->render(
                "JavicodeSharingExpensesBundle:User:view_expenses.html.twig"
            );
    }

    private function isUserAllowedToAddExpense($group, $user)
    {
        return $group->doesUserBelongToThisGroup($this->getUser()) && doesUserBelongToThisGroup($user);
    }
}
