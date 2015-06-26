<?php

namespace TVS\Login\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        return $builder->add('username', "text", [
                    'label' => 'Nome',
                    'constraints' => [new NotBlank()],
                        ]
                )->add('email', "email", [
                    'constraints' => [new NotBlank(), new Email()],
                        ]
                )->add('password', "password", [
                    'constraints' => [new NotBlank()],
                    'label' => 'Senha',
                        ]
                )->add('ativo', "checkbox", [
                    'label' => 'Ativo?',
                    'required' => false,
                        ]
        );
    }

    public function getName() {
        return 'UserForm';
    }

}
