<?php

namespace App\Form;

use App\Entity\Etudiants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class EtudiantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['attr' => ['class' => 'form-control'], 'label' => 'Nom'])
            ->add('prenom',TextType::class,['attr' => ['class' => 'form-control'], 'label' => 'Prénom'])
            ->add('date_de_naissance',DateType::class,['attr' => ['class' => 'form-control'], 'label' => 'Date de naissance'])
            ->add('adresse',TextType::class,['attr' => ['class' => 'form-control'], 'label' => 'Adresse'])
            ->add('telephone',NumberType::class,['attr' => ['class' => 'form-control'], 'label' => 'Téléphone'])
            ->add('email',TextType::class,['attr' => ['class' => 'form-control'], 'label' => 'Email'])
            ->add('document', FileType::class,['attr' => ['class' => 'form-control'],
                'data_class' => null,
                'required' => false, 'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'video/mp4',
                            'application/pdf'
                        ]
                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiants::class,
        ]);
    }
}
