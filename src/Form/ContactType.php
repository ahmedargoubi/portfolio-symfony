<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your Name',
                    'autocomplete' => 'name',
                    'maxlength' => 100
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your name']),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Your name must be at least {{ limit }} characters',
                        'maxMessage' => 'Your name cannot be longer than {{ limit }} characters',
                    ]),
                    // 🔒 Protection contre injection de caractères spéciaux
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/u',
                        'message' => 'Your name can only contain letters, spaces and hyphens',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your Email',
                    'autocomplete' => 'email',
                    'maxlength' => 255
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your email']),
                    new Email(['message' => 'Please enter a valid email address']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Your email cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Subject',
                    'maxlength' => 255
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a subject']),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'The subject must be at least {{ limit }} characters',
                        'maxMessage' => 'The subject cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your Message',
                    'rows' => 6,
                    'maxlength' => 5000
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your message']),
                    new Length([
                        'min' => 10,
                        'max' => 5000,
                        'minMessage' => 'Your message must be at least {{ limit }} characters',
                        'maxMessage' => 'Your message cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            // 🔒 Protection CSRF activée
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'contact_form',
        ]);
    }
}