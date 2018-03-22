<?php
/**
 * Created by PhpStorm.
 * User: Rodrigo Vasco
 * Date: 13/03/2018
 * Time: 12:37
 */

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => "Nome",
                'attr' => [
                    'placeholder' => 'Informe o nome do usuário',
                    'class' => "form-control"
                ]
            ])
            ->add('cpf', TextType::class, [
                'label' => 'CPF' ,
                'attr' => [
                    'placeholder' => 'Informe o CPF do usuário',
                    'class' => 'form-control'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr' => [
                    'placeholder' => 'Informe o username do usuário',
                    'class' => 'form-control'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Senha',
                'attr' => [
                    'placeholder' => 'Informe a senha do usuário',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'Informe o e-mail do usuário',
                    'class' => 'form-control'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Permissão',
                'attr' => [
                    'placeholder' => 'Informe qual vai ser o direito de acesso do usuário',
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Usuário' => 'ROLE_USER',
                    'Gerente' => 'ROLE_GERENTE'
                ],
                'multiple' => true
            ])
            ->add('enviar', SubmitType::class, [
                'label' => "Salvar",
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}