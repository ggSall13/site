<?php

namespace Src\Core\Auth;


class User
{
   public function __construct(
      private $id,
      private $name,
      private $email,
      private $phone,
   ) {}

   public function id()
   {
      return $this->id;
   }

   public function phone()
   {
      return $this->phone;
   }

   public function name()
   {
      return $this->name;
   }

   public function email()
   {
      return $this->email;
   }
}
