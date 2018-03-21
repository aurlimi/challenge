<?php

namespace AppBundle\Form;

use AppBundle\Entity\Supplier;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.title',
            ])
            ->add('description',null,[
                'attr' => ['rows' => 10],
                'label' => 'label.description',
            ])
            ->add('mark',null,[
                'label' =>'label.mark'
            ])
            ->add('quantity',null,[
                'label' => 'label.quantity'
            ])
            ->add('price',null,[
                'label' =>'label.price'
            ])
            ->add('supplier',EntityType::class , array(
                'class' => 'AppBundle:Supplier',
                'label' =>'label.supplier',
                'multiple' => false
    ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
