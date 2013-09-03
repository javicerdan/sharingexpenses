<?php

namespace Javicode\SharingExpensesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expense
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Javicode\SharingExpensesBundle\Entity\ExpenseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Expense
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="bill_pic", type="text", nullable=true)
     */
    private $bill_pic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="expenses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SharingGroup", inversedBy="expenses")
     * @ORM\JoinColumn(name="sharingGroup_id", referencedColumnName="id")
     */
    private $sharing_group;


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
     * Set name
     *
     * @param string $name
     * @return Expenses
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
     * Set price
     *
     * @param float $price
     * @return Expenses
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Expenses
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Expenses
     */
    public function setCreatedAt($datetime)
    {
        $this->created_at = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set sharing_group
     *
     * @param \Javicode\SharingExpensesBundle\Entity\SharingGroup $sharingGroup
     * @return Expense
     */
    public function setSharingGroup(\Javicode\SharingExpensesBundle\Entity\SharingGroup $sharingGroup = null)
    {
        $this->sharing_group = $sharingGroup;

        return $this;
    }

    /**
     * Get sharing_group
     *
     * @return \Javicode\SharingExpensesBundle\Entity\User
     */
    public function getSharingGroup()
    {
        return $this->sharing_group;
    }

    /**
     * Set bill_pic
     *
     * @param string $billPic
     * @return Expense
     */
    public function setBillPic($billPic)
    {
        $this->bill_pic = $billPic;

        return $this;
    }

    /**
     * Get bill_pic
     *
     * @return string
     */
    public function getBillPic()
    {
        return $this->bill_pic;
    }

    /**
     * Set user
     *
     * @param \Javicode\SharingExpensesBundle\Entity\User $user
     * @return Expense
     */
    public function setUser(\Javicode\SharingExpensesBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Javicode\SharingExpensesBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt(new \DateTime());
    }
}