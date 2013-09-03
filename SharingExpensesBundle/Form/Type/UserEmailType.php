<?php
namespace Javicode\SharingExpensesBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Javicode\SharingExpensesBundle\Form\DataTransformer\UserToEmailTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEmailType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new UserToEmailTransformer($this->om);
        $builder->addViewTransformer($transformer);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'user_email';
    }
}