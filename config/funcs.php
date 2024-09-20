<?php


function getInput($key) {
   if (isset($_SESSION['inputs'][$key])) {
      return h($_SESSION['inputs'][$key]);
   }
}

function h($value) {
   return trim(htmlspecialchars($value, ENT_QUOTES));
}
