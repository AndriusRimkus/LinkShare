<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('link', 'text', array(
                'data' => 'http://'
            ))
            ->add('category', 'entity', array(
                'class' => 'AppBundle\Entity\Category',
                'property' => 'name',
            ));
    }

    public function getName()
    {
        return 'appbundlelink';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Link',
        ));
    }
}