<?php
namespace app\UserModule\FormPrototype;

use framework\core\Forms\FormBuilder;

class UserUpdateForm extends UserForm
{

    public function buildFormPrototype(FormBuilder $builder)
    {
        parent::buildFormPrototype($builder);
        $builder->removeInput('password');
        $builder
            ->addInput('password', 'password', 'New password')
            ->addInput('oldPassword', 'password', 'Old password', array(
                'required' => true,
            ))
        ;
        return $builder;
    }
}