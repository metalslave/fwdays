<?php

namespace Application\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProfileFormType.
 */
class ProfileFormType extends BaseProfileFormType
{
    /**
     * @var string
     */
    private $class;

    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label' => 'fos_user_profile_form_email',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('name', null, array(
                'label' => 'fos_user_profile_form_name',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('surname', null, array(
                'label' => 'fos_user_profile_form_surname',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('phone', null, array(
                'required' => false,
                'label' => 'fos_user_profile_form_phone',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('country', null, array(
                'required' => false,
                'label' => 'fos_user_profile_form_country',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('city', null, array(
                'required' => false,
                'label' => 'fos_user_profile_form_city',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('company', null, array(
                'required' => false,
                'label' => 'fos_user_profile_form_company',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('post', null, array(
                'required' => false,
                'label' => 'fos_user_profile_form_post',
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('subscribe', 'checkbox', array(
                'required' => false,
                'label' => 'fos_user_profile_form_subscribe',
                'translation_domain' => 'FOSUserBundle',
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile',
        ));
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return 'application_user_profile';
    }
}
