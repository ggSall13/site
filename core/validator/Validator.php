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
      if (strlen($title) <= 2 || strlen($title) > 80) {
         $this->addError('title', "Название должно быть больше 2 символов и меньше 80");
      }

      if (!preg_match('#^[^$)!@(:;]+$#', $title)) {
         $this->addError('title', 'Введите корректное имя');
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
      if (empty($val) || strlen($val) == 0) {
         $this->addError($fieldName, "Поле {$fieldName} должно быть заполнено");
      }
   }

   private function validateName($fieldName, $name)
   {
      if (strlen($name) <= 2 || strlen($name) > 80) {
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
      if (strlen($password) < 6 || strlen($password) > 60) {
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
