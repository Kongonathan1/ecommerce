<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Products;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nom',
                'required' => false
            ])
            ->add('description', options: [
                'label' => 'Description',
                'required' => false
            ])
            ->add('price', MoneyType::class ,options: [
                'label' => 'Prix',
                'required' => false
            ])
            ->add('stock', options: [
                'label' => 'Unités en stock',
                'required' => false
            ])
            ->add('images', FileType::class, [
                'label' => 'Image(s)',
                'mapped' => false,
                'multiple' => true,
                'required' => false,     
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categorie',
                'class' => Category::class,
                'required' => false,
                'choice_label' => 'name',
                'group_by' => 'parent.name',
                'query_builder' => function(CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                                ->where("c.parent IS NOT NULL")
                                ->orderBy('c.name', "ASC");
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
