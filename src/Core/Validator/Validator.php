<?php

namespace Src\Core\Validator;

class Validator
{
   private $errors = [];

   private $data = [];

   public function validate(array $data)
   {
      $this->data = $data;

      foreach ($data as $key => $val) {
         $method = 'validate' . ucfirst($key);
         if (method_exists($this, $method)) {
            $this->$method($key, $val);
         }
         $this->checkField($key, $val);
      }
   }

   // метод для валидации одного конкретного поля
   public function validateField(string $field, $data)
   {
      $method = 'validate' . ucfirst($field);

      if (method_exists($this, $method)) {
         $this->$method($data);
      }
   }

   public function hasErrors()
   {
      return !empty($this->errors);
   }

   public function getErrors()
   {
      return $this->errors;
   }
   private function validateTitle($fieldName, $title)
   {
      if (mb_strlen($title) <= 2 || mb_strlen($title) > 160) {
         $this->addError('title', "Название должно быть больше 2 символов и меньше 160");
      }

      if (preg_match('#[@;]#', $title)) {
         $this->addError('title', "Заголовок не должен содержать символы @ и ;.");
      } elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z0-9\s!()\'\/\\\\<>=\-.,\'"«»]+$/u', $title)) {
         $this->addError('title', 'Заголовок может содержать только буквы, цифры и следующие символы: !()\'/\\<>=-.,\'".«»');
      }
   }

   private function validateDescription($fieldName, $description)
   {
      if (mb_strlen($description) <= 2 || mb_strlen($description) > 2000) {
         $this->addError('description', "Описание должно быть больше 2 символов и меньше 2000");
      }

      if (!preg_match('#^[^@;]+$#', $description)) {
         $this->addError('description', 'Введите корректное описание');
      }
   }

   private function validateEmail($fieldName, $email)
   {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $this->addError($fieldName, 'Введите корректный email');
      }
   }

   private function checkField($fieldName, $val)
   {
      if (empty($val) || mb_strlen($val) == 0) {
         $this->addError($fieldName, "Поле {$fieldName} должно быть заполнено");
      }
   }

   private function validateName($fieldName, $name)
   {
      if (mb_strlen($name) <= 2 || mb_strlen($name) > 80) {
         $this->addError($fieldName, "Имя должно быть больше 2 символов и меньше 80");
      }

      if (!preg_match('#^[^$)!@(:;0-9]+$#', $name)) {
         $this->addError($fieldName, 'Введите корректное имя');
      }
   }

   private function validatePhone($fieldName, $phone)
   {
      if (!preg_match('#^([0-9]|\+[0-9])\s?(\d{3})\s?(\d{3})\s?(\d{4})$#', $phone)) {
         $this->addError($fieldName, "Номер телефона не должен содержать скобок, тире, введите корректный номер телефона");
      }
   }

   private function validatePassword($fieldName, $password)
   {
      if (mb_strlen($password) < 6 || mb_strlen($password) > 60) {
         $this->addError($fieldName, "Пароль должен быть больше 2 символов и меньше 80");
      }
   }

   private function validatePasswordConfirm($fieldName, $password)
   {
      if ($this->data['password'] !== $this->data['passwordConfirm']) {
         $this->addError($fieldName, "Пароли должны совпадать");
      }
   }

   private function addError($fieldName, string $errorVal)
   {
      $this->errors[$fieldName] = $errorVal;
   }
}
