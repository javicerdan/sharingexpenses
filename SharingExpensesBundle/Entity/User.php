<?php

namespace Javicode\SharingExpensesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\SecurityExtraBundle\Security\Util\String; //TODO: JMS
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Javicode\SharingExpensesBundle\Entity\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var String
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    protected $picture;

    /**
     * @ORM\ManyToMany(targetEntity="SharingGroup", mappedBy="users")
     * @ORM\JoinTable(name="users_sharingGroups")
     */
    protected $sharing_groups;

    /**
     * @ORM\OneToMany(targetEntity="Expense", mappedBy="user")
     */
    protected $expenses;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
        $this->sharing_groups = new ArrayCollection();
        $this->salt = md5(uniqid(null, true));
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
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Users
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Add expenses
     *
     * @param \Javicode\SharingExpensesBundle\Entity\Expense $expenses
     * @return User
     */
    public function addExpense(
        \Javicode\SharingExpensesBundle\Entity\Expense $expenses
    ) {
        $this->expenses[] = $expenses;

        return $this;
    }

    /**
     * Remove expenses
     *
     * @param \Javicode\SharingExpensesBundle\Entity\Expense $expenses
     */
    public function removeExpense(
        \Javicode\SharingExpensesBundle\Entity\Expense $expenses
    ) {
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
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }


    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add sharing_groups
     *
     * @param \Javicode\SharingExpensesBundle\Entity\SharingGroup $sharingGroups
     * @return User
     */
    public function addSharingGroup(\Javicode\SharingExpensesBundle\Entity\SharingGroup $sharingGroups)
    {
        $this->sharing_groups[] = $sharingGroups;

        return $this;
    }

    /**
     * Remove sharing_groups
     *
     * @param \Javicode\SharingExpensesBundle\Entity\SharingGroup $sharingGroups
     */
    public function removeSharingGroup(\Javicode\SharingExpensesBundle\Entity\SharingGroup $sharingGroups)
    {
        $this->sharing_groups->removeElement($sharingGroups);
    }

    /**
     * Get sharing_groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSharingGroups()
    {
        return $this->sharing_groups;
    }

    /**
     * @param int $group_id
     * @return int Amount spent in group by this user
     */
    public function getAmountSpentInGroup($group_id)
    {
        $amount = 0;
        foreach($this->getExpenses() as $expense){
            if($expense->getSharingGroup()->getId() == $group_id){
                $amount += $expense->getPrice();
            }
        }
        return $amount;
    }

    /**
     * @param SharingGroup $group
     * @return array Expense objects from group $group
     */
    public function getExpensesInGroup(SharingGroup $group)
    {
        $expenses = array();
        foreach($this->getExpenses() as $expense){
            if($expense->getSharingGroup() == $group){
                $expenses[] = $expense;
            }
        }
        return $expenses;
    }

    /**
     * Magic function that solves a problem when serializing for a session saving if a related entity has been erased
     * @return array
     */
    public function __sleep(){
        return array('id', 'username', 'email');
    }

}