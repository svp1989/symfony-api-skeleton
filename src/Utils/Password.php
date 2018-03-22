<?php
namespace App\Utils;

use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Сервис работы с паролями
 * Class Password
 * @package AppBundle\Service
 */
class Password {

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * При обновлении пароля проверка
     * на то что пароль задан и обносление в случае необходимости
     *
     * @param Form $form
     * @param $password
     * @return string
     */
    public function update(Form $form,  $password) {
        $dataForm = $form->getData();
        $newPassword = $dataForm->getPassword();

        if (isset($newPassword)) {
            return $this->passwordEncoder->encodePassword($dataForm, $newPassword);
        }

        return $password;
    }

    /**
     * Encode пароля
     * @param Form $form
     * @param $password
     * @return string
     */
    public function encode(Form $form, $password) {
        $dataForm = $form->getData();
        return $this->passwordEncoder->encodePassword($dataForm, $password);
    }
}