<?php

namespace App\Form;

use App\Entity\Certification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CertificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Certification Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'e.g., RHCSA'],
                'constraints' => [
                    new NotBlank(['message' => 'Name is required']),
                ],
            ])
            ->add('issuer', TextType::class, [
                'label' => 'Issuing Organization',
                'attr' => ['class' => 'form-control', 'placeholder' => 'e.g., Red Hat'],
                'constraints' => [
                    new NotBlank(['message' => 'Issuer is required']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Brief description...'],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Certification Badge/Logo',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPG, PNG)',
                    ])
                ],
            ])
            ->add('credentialId', TextType::class, [
                'label' => 'Credential ID',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Certificate number'],
            ])
            ->add('credentialUrl', UrlType::class, [
                'label' => 'Credential URL',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'https://...'],
            ])
            ->add('issueDate', DateType::class, [
                'label' => 'Issue Date',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('expiryDate', DateType::class, [
                'label' => 'Expiry Date',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('displayOrder', IntegerType::class, [
                'label' => 'Display Order',
                'attr' => ['class' => 'form-control', 'placeholder' => '0'],
                'data' => 0,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active (Visible)',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Certification::class,
        ]);
    }
}