<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'attr' => ['class' => 'form-control p-0 mt-b'],
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Nom',
                'attr' => ['class' => 'form-control p-0 mb-2'],
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control p-0 mb-2'],
            ])
            ->add('etablissement',TextType::class,[
                'label' => 'Etablissement',
                'attr' => ['class' => 'form-control p-0 mb-2'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation',
                'attr' => ['class' => 'form-check p-0 mb-2'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                'required' => true,
                'class' => 'form-control p-0 mb-2'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Vous êtes :',
                'attr' => ['class' => 'p-1 mb-2'],
                'choices' => [
                    'Professeur' => 'ROLE_PROF',
                    'Elève' => 'ROLE_ELEVE',
                ],
                'multiple' => false,
                'expanded' => true, // Affiche les choix sous forme de cases à cocher
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'm-2'];
                },
            ])
        ;
        // Data transformer pour convertir la valeur unique en tableau
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // Transforme le tableau en chaîne (le premier rôle)
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // Transforme la chaîne en tableau
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
