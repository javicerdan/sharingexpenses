<?php

namespace Javicode\SharingExpensesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SharingGroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Javicode\SharingExpensesBundle\Entity\SharingGroupRepository")
 */
class SharingGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="sharing_groups", cascade={"persist"})
     * @ORM\JoinTable(name="users_sharingGroups")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Expense", mappedBy="sharing_group")
     */
    private $expenses;

    /**
     * @var number Cache var for the total amount spent
     */
    private $total_amount_spent;

    /**
     * @var number Cache var for the least amount a user in this group has spent
     */
    private $min_spent_amount;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add users
     *
     * @param \Javicode\SharingExpensesBundle\Entity\User $user
     * @return SharingGroup
     */
    public function addUser(\Javicode\SharingExpensesBundle\Entity\User $user)
    {
        $user->addSharingGroup($this);
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Javicode\SharingExpensesBundle\Entity\User $users
     */
    public function removeUser(\Javicode\SharingExpensesBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
        //TODO: Email to let the user know he's been removed from the group
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add expenses
     *
     * @param \Javicode\SharingExpensesBundle\Entity\Expense $expenses
     * @return SharingGroup
     */
    public function addExpense(\Javicode\SharingExpensesBundle\Entity\Expense $expenses)
    {
        $this->expenses[] = $expenses;

        return $this;
    }

    /**
     * Remove expenses
     *
     * @param \Javicode\SharingExpensesBundle\Entity\Expense $expenses
     */
    public function removeExpense(\Javicode\SharingExpensesBundle\Entity\Expense $expenses)
    {
        $this->expenses->removeElement($expenses);
    }

    /**
     * Get expenses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SharingGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param User $user
     * @return bool true if user belongs to this group
     */
    public function doesUserBelongToThisGroup(User $user)
    {
        return $this->users->contains($user);
    }

    /**
     * @return number Total amount spent by this group
     */
    public function getTotalExpenses()
    {
        $expenses = array();
        foreach($this->getExpenses() as $expense){
            $expenses[] = $expense->getPrice();
        }
        return array_sum($expenses);
    }

    /**
     * @return float Minimum amount spent by one of the users in the group
     */
    public function getMinSpentAmountFromUsers()
    {
        $min_amount_spent = PHP_INT_MAX;
        foreach($this->getUsers() as $user){
            $amount_spent = 0;
            foreach($user->getExpensesInGroup($this) as $expense){
                $amount_spent += $expense->getPrice();
            }
            $min_amount_spent = min($min_amount_spent, $amount_spent);
        }
        if($min_amount_spent == PHP_INT_MAX){
            return 0;
        }
        return $min_amount_spent;
    }

    public function getExpensesNewestFirst()
    {
        return array_reverse($this->getExpenses()->toArray());
    }
}