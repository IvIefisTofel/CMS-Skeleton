<?php
namespace AuthDoctrine\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class LoginForm extends Form
{
    const NAME    = 'name';
    const PASSWORD = 'password';
    const REMEMBER = 'rememberMe';

    public function __construct($options = [])
    {
        parent::__construct('login', $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        $email = new Element\Text(self::NAME);
        $email
            ->setAttributes([
            'placeholder' => 'Имя пользователя',
            'required' => true,
        ]);
        $this->add($email);

        $password = new Element\Password(self::PASSWORD);
        $password->setAttributes([
            'placeholder' => 'Пароль',
            'required' => true,
        ]);
        $this->add($password);

        $remember = new Element\Checkbox(self::REMEMBER);
        $remember->setLabel('Запомнить меня');
        $this->add($remember);

        $submit = new Element\Submit('submit');
        $submit
            ->setLabel('Вход')
            ->setAttributes([
                'class' => 'btn-primary full-width',
            ]);
        $this->add($submit);
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter();

        $email = new Input(self::NAME);
        $email->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\StringLength(3));
        $inputFilter->add($email);

        $password = new Input(self::PASSWORD);
        $password->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\StringLength(6));
        $inputFilter->add($password);

        return $inputFilter;
    }
}