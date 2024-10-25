<?php

class Bd
{
 private static ?PDO $pdo = null;

 static function pdo(): PDO
 {
  if (self::$pdo === null) {

   self::$pdo = new PDO(
    // cadena de conexión
    "sqlite:srvbd.db",
    // usuario
    null,
    // contraseña
    null,
    // Opciones: pdos no persistentes y lanza excepciones.
    [PDO::ATTR_PERSISTENT => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
   );

   self::$pdo->exec(
    "CREATE TABLE IF NOT EXISTS PASATIEMPO (
      PAS_ID INTEGER,
      PAS_NOMBRE TEXT NOT NULL,
      CONSTRAINT PAS_PK
       PRIMARY KEY(PAS_ID),
      CONSTRAINT PAS_NOM_UNQ
       UNIQUE(PAS_NOMBRE),
      CONSTRAINT PAS_NOM_NV
       CHECK(LENGTH(PAS_NOMBRE) > 0)
     )"
   );

   self::$pdo->exec(
    "CREATE TABLE IF NOT EXISTS LIGAS (
      LIG_ID INTEGER,
      LIG_NOMBRE TEXT NOT NULL,
      LIG_DESCRIPCION TEXT NOT NULL,
      LIG_DIRECCION TEXT NOT NULL,
      LIG_PREMIO TEXT NOT NULL,
      LIG_LOGO TEXT NOT NULL,
      CONSTRAINT LIG_PK
       PRIMARY KEY(LIG_ID),
      CONSTRAINT LIG_NOMBRE_UNIQ
       UNIQUE(LIG_NOMBRE),
      CONSTRAINT LIG_NOMBRE_NV
       CHECK(LENGTH(LIG_NOMBRE) > 0)
     )"
   );
  }

  return self::$pdo;
 }
}
