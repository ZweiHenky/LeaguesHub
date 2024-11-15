<?php

class Sesion
{

 public string $correo;
 public array $rolIds;

 public function __construct(string $correo, array $rolIds)
 {
  $this->correo = $correo;
  $this->rolIds = $rolIds;
 }
}
