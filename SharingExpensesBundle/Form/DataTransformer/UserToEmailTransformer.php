<?php

namespace Javicode\SharingExpensesBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Javicode\SharingExpensesBundle\Entity\User;
use Symfony\Component\Security\Core\Util\SecureRandom;

class UserToEmailTransformer implements DataTransformerInterface
{
    /*
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms object User into string user email
     * @param User|null $user
     * @return string
     */
    public function transform($user)
    {
        return (is_object($user)) ? $user->getEmail() : "";
    }

    /**
     * Transforms a string (user's email) into the User object with that email; or it's handled if nonexistent
     * @param string $email
     * @return User|null
     */
    public function reverseTransform($email)
    {
        $user = $this->om->getRepository("JavicodeSharingExpensesBundle:User")->findOneByEmail($email);
        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername(substr($email, 0, strpos($email, '@')));
            $generator = new SecureRandom();
            $user->setPassword($random = $generator->nextBytes(10));
            //TODO : Set up email account and move this call to an email service
            $message = \Swift_Message::newInstance()
                ->setSubject('A friend has added you to SharingExpenses!')
                ->setFrom('new_user@sharingexpenses.com')
                ->setTo($email)
                ->setBody('Hi!
                    Someone has added you to SharingExpenses! Come and make sharing easy with us.
                    You can use '.$user->getUsername().' as your username and '. $user->getPassword(). 'as password
                    http://sharingexpenses.com'
                )
            ;
            //$this->get('mailer')->send($message);
        }

        return $user;
    }

}