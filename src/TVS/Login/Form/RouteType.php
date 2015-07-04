<?php

namespace TVS\Login\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RouteType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder->add('route', "text", array(
                        'constraints' => array(new NotBlank(), new Length(array('max' => 30))),
                        'label' => 'Rota',
                            )
                    )->add('label', "text", array(
                        'constraints' => array(new NotBlank(), new Length(array('max' => 30))),
                        'label' => 'Tag',
                            )
                    );
                   
    }

    public function getName()
    {
        return 'RouteForm';
    }
}
