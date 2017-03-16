<?php
namespace app\UserModule\FormPrototype;


use framework\core\Forms\FormBuilder;
use framework\core\Forms\FormBuilderInterface;

class UserForm implements FormBuilderInterface
{

    public function buildFormPrototype(FormBuilder $builder)
    {
        $builder
            ->addInput('name', 'text', 'Full name', array(
                'required' => true,
            ))
            ->addInput('mail', 'text')
            ->addInput('login', 'text', null, array(
                'required' => true,
            ))
            ->addInput('password', 'password', null, array(
                'required' => true,
            ))
        ;
        
        return $builder;
    }
}
        