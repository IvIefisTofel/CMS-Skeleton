<?php
namespace Admin\Form;

use TwbBundle\View\Helper\TwbBundleButtonGroup;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Users\Entity\User;

class EditUser extends Form
{
    const NAME              = 'setUserFullName';
    const LOGIN             = 'setUserName';
    CONST EMAIL             = 'setUserEmail';
    const OLD_PASSWORD      = 'old-password';
    const PASSWORD          = 'setUserPassword';
    const CONFIRM_PASSWORD  = 'confirm-password';
    CONST ROLE              = 'setUserRoleID';
    const ACTIVE            = 'setUserActive';

    private $self;
    private $fullRequired;

    private $test;

    public function __construct($options = [], $self = false, $fullRequired = false)
    {
        parent::__construct('edituser', $options);
        $this->self = $self;
        $this->fullRequired = ($fullRequired) ? ' required-label' : null;

        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        $name = new Element\Text(self::NAME);
        $name->setLabel('ФИО ')
            ->setLabelAttributes(['class' => 'col-sm-2' . $this->fullRequired])
            ->setAttributes([
                'id' => 'name',
                'placeholder' => 'ФИО',
            ])->setOption('column-size', 'sm-10');
        if ($this->fullRequired != null)
            $name->setAttribute('required',  true);
        $this->add($name);

        $login = new Element\Text(self::LOGIN);
        $login->setLabel('Логин')
            ->setLabelAttributes(['class' => 'col-sm-2' . $this->fullRequired])
            ->setAttributes([
                'id' => 'login',
                'placeholder' => 'Логин',
            ])->setOption('column-size', 'sm-10');
        if ($this->fullRequired != null)
            $login->setAttribute('required',  true);
        $this->add($login);

        $email = new Element\Email(self::EMAIL);
        $email->setLabel('Email')
            ->setLabelAttributes(['class' => 'col-sm-2' . $this->fullRequired])
            ->setAttributes([
                'id' => 'email',
                'placeholder' => 'Email',
            ])->setOption('column-size', 'sm-10');
        if ($this->fullRequired != null)
            $email->setAttribute('required',  true);
        $this->add($email);

        if ($this->self) {
            $password = new Element\Password(self::OLD_PASSWORD);
            $password->setLabel('Старый пароль')
                ->setLabelAttributes(['class' => 'col-sm-2' . $this->fullRequired])
                ->setAttributes([
                    'id' => 'old-password',
                    'placeholder' => 'Старый пароль',
                ])->setOption('column-size', 'sm-10');
            $this->add($password);
        }

        $password = new Element\Password(self::PASSWORD);
        $password->setLabel('Пароль')
            ->setLabelAttributes(['class' => 'col-sm-2' . $this->fullRequired])
            ->setAttributes([
                'id' => 'password',
                'placeholder' => 'Пароль',
            ])->setOption('column-size', 'sm-10');
        if ($this->fullRequired != null)
            $password->setAttribute('required',  true);
        $this->add($password);

        if ($this->self) {
            $confirmPassword = new Element\Password(self::CONFIRM_PASSWORD);
            $confirmPassword->setLabel('Подтверждение пароля')
                ->setLabelAttributes(['class' => 'col-sm-2' . $this->fullRequired])
                ->setAttributes([
                    'id' => 'password',
                    'placeholder' => 'Подтверждение пароля',
                ])->setOption('column-size', 'sm-10');
            if ($this->fullRequired != null)
                $confirmPassword->setAttribute('required',  true);
            $this->add($confirmPassword);
        }

        if (!$this->self) {
            $role = new Element\Select(self::ROLE);
            $role->setLabel('Права доступа')
                ->setLabelAttributes(['class' => 'col-sm-2' . $this->fullRequired])
                ->setValueOptions([
                    User::ADMIN_ROLE => User::$ROLE_LABEL[User::ADMIN_ROLE],
                    User::MODERATOR_ROLE => User::$ROLE_LABEL[User::MODERATOR_ROLE],
                ])->setAttributes([
                    'id' => 'role',
                ])->setOption('column-size', 'sm-10');
            if ($this->fullRequired != null)
                $role->setAttribute('required', true);
            $this->add($role);

            $active = new Element\Checkbox(self::ACTIVE);
            $active->setLabel('Активен')
                ->setAttributes([
                    'id' => 'active',
                    'class' => 'i-checks',
                ])->setOption('column-size', 'sm-10 col-sm-offset-2');
            $this->add($active);
        }

        $submit = new Element\Button('submit');
        $submit->setLabel('Сохранить')
            ->setAttributes([
                'type' => 'submit',
                'class' => 'btn-primary',
            ])->setOptions([
                'column-size' => 'sm-10 col-sm-offset-2 line-dashed line-dashed-last',
                'glyphicon' => 'ok',
            ]);
        $this->add($submit);
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter();

        $name = new Input(self::NAME);
        $name->setRequired($this->fullRequired != null)
            ->getValidatorChain();
        $inputFilter->add($name);

        $email = new Input(self::LOGIN);
        $email->setRequired($this->fullRequired != null)
            ->getValidatorChain()
            ->attach(new Validator\StringLength(6));
        $inputFilter->add($email);

        $email = new Input(self::EMAIL);
        $email->setRequired($this->fullRequired != null)
            ->getValidatorChain()
            ->attach(new Validator\EmailAddress());
        $inputFilter->add($email);

        $password = new Input(self::PASSWORD);
        $password->setRequired($this->fullRequired != null)
            ->getValidatorChain()
            ->attach(new Validator\StringLength(6));
        $inputFilter->add($password);

        if ($this->self) {
            $confirmPassword = new Input(self::CONFIRM_PASSWORD);
            $confirmPassword->setRequired(true)
                ->setAllowEmpty(true)
                ->getValidatorChain()
                ->attach(new Validator\Identical([
                    'token' => self::PASSWORD
                ]));
            $inputFilter->add($confirmPassword);
        }

        if (!$this->self) {
            $role = new Input(self::ROLE);
            $role->setRequired($this->fullRequired != null)
                ->getValidatorChain();
            $inputFilter->add($role);

            $active = new Input(self::ACTIVE);
            $active->setRequired($this->fullRequired != null)
                ->getValidatorChain();
            $inputFilter->add($active);
        }

        return $inputFilter;
    }
}