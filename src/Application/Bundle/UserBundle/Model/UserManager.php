<?php

namespace Application\Bundle\UserBundle\Model;

use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Container;

class UserManager extends \FOS\UserBundle\Doctrine\UserManager
{

    /**
     * @var ContainerInterface $container
     */
    public $container;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface $usernameCanonicalizer
     * @param CanonicalizerInterface $emailCanonicalizer
     * @param ObjectManager $om
     * @param string $class
     * @param Container $container
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer,
        ObjectManager $om,
        $class,
        Container $container
    ) {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);

        $this->container = $container;
    }

    /**
     * Automatic user registration
     *
     * @param $participant
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function autoRegistration($participant)
    {
        /**
         * @var \Application\Bundle\UserBundle\Entity\User $user
         */
        $user = $this->createUser();
        $user->setEmail($participant['email']);
        $user->setName($participant['name']);
        $user->setSurName($participant['surname']);
        $user->setFullname($participant['surname'].' '.$participant['name']);

        //Generate a temporary password
        $plainPassword = substr(md5(uniqid(mt_rand(), true) . time()), 0, 8);

        $user->setPlainPassword($plainPassword);
        $user->setEnabled(true);
        $this->updateUser($user);

        $body = $this->container->get('stfalcon_event.mailer_helper')->renderTwigTemplate(
            'ApplicationUserBundle:Registration:automatically.html.twig',
            [
                'user' => $user,
                'plainPassword' => $plainPassword
            ]
        );

        $message = $this->container->get('stfalcon_event.mailer_helper')->createMessage(
            "Регистрация на сайте Frameworks Days",
            $user->getEmail(),
            $body
        );

        $this->container->get('mailer')->send($message);

        return $user;
    }
} 