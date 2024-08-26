<?php

return [
   'dsn' => 'mysql:host=localhost;dbname=site.loc',
   'user' => 'root',
   'pass' => '',
   'options' => [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   ],
];
