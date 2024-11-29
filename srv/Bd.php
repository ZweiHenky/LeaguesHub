<?php

require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/usuarios/TABLA_ROLS.php";
require_once __DIR__ . "/usuarios/TABLA_USUARIOS.php";
require_once __DIR__ . "/usuarios/TABLA_USUARIO_ROL.php";
require_once __DIR__ . "/../lib/php/insertBridges.php";

class Bd
{
 private static ?PDO $pdo = null;

 static function pdo(): PDO
 {
  if (self::$pdo === null) {

   self::$pdo = new PDO(
    // cadena de conexión
    "sqlite:../srvbd.db",
    // usuario
    null,
    // contraseña
    null,
    // Opciones: pdos no persistentes y lanza excepciones.
    [PDO::ATTR_PERSISTENT => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
   );

   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS USUARIO (
      USU_ID INTEGER,
      USU_NOM TEXT NOT NULL,
      USU_LAST TEXT,
      USU_EMAIL TEXT NOT NULL,
      USU_TEL ,
      USU_PASS TEXT NOT NULL,
      CONSTRAINT USU_PK
       PRIMARY KEY(USU_ID),
      CONSTRAINT USU_NOM_NV
       CHECK(LENGTH(USU_NOM) > 0),
      CONSTRAINT USU_NOM_UNIQ
       UNIQUE(USU_EMAIL),
      CONSTRAINT USU_TEL_UNIQ
       UNIQUE(USU_TEL),
      CONSTRAINT USU_ID_FK
       FOREIGN KEY (USU_ID) REFERENCES USUARIO(USU_ID)
     )'
   );

  self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS EQUIPOS (
      EQI_ID INTEGER,
      EQI_NOMBRE TEXT NOT NULL,
      EQI_LOGO TEXT NOT NULL,
      USU_ID INTEGER NOT NULL,
      CONSTRAINT EQI_PK
       PRIMARY KEY(EQI_ID),
      CONSTRAINT EQI_NOMBRE_UNIQ
       UNIQUE(EQI_NOMBRE),
      CONSTRAINT EQI_NOMBRE_NV
       CHECK(LENGTH(EQI_NOMBRE) > 0)
     )'
   );

   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS PARTIDOS (
      PAR_ID INTEGER,
      PAR_FECHA INTEGER NOT NULL,
      PAR_GOL_LOCAL TEXT,
      PAR_GOL_VISITANTE TEXT,
      PAR_LOCAL INTEGER NOT NULL,
      PAR_VISITANTE INTEGER NOT NULL,
      CONSTRAINT JOR_PK
       PRIMARY KEY(PAR_ID),
      CONSTRAINT PAR_LOCAL_FK
       FOREIGN KEY (PAR_LOCAL) REFERENCES EQUIPOS(EQI_ID),
      CONSTRAINT PAR_VISITANTE_FK
       FOREIGN KEY (PAR_VISITANTE) REFERENCES EQUIPOS(EQI_ID)
     )'
   );

  self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS JORNADAS (
      JOR_ID INTEGER,
      JOR_NUMERO INTEGER NOT NULL,
      PAR_ID INTEGER NOT NULL,
      LIG_ID INTEGER NOT NULL,
      CONSTRAINT JOR_PK
       PRIMARY KEY(JOR_ID)
      CONSTRAINT PAR_ID_FK
       FOREIGN KEY (PAR_ID) REFERENCES PARTIDOS(PAR_ID),
      CONSTRAINT LIG_ID_FK
       FOREIGN KEY (LIG_ID) REFERENCES LIGAS(LIG_ID)
     )'
   );

   self::$pdo->exec(
    "CREATE TABLE IF NOT EXISTS LIGAS (
      LIG_ID INTEGER,
      LIG_NOMBRE TEXT NOT NULL,
      LIG_DESCRIPCION TEXT NOT NULL,
      LIG_DIRECCION TEXT NOT NULL,
      LIG_PREMIO TEXT NOT NULL,
      LIG_LOGO TEXT NOT NULL,
      USU_ID INTEGER NOT NULL,
      CONSTRAINT LIG_PK
       PRIMARY KEY(LIG_ID),
      CONSTRAINT LIG_NOMBRE_UNIQ
       UNIQUE(LIG_NOMBRE),
      CONSTRAINT LIG_NOMBRE_NV
       CHECK(LENGTH(LIG_NOMBRE) > 0),
      CONSTRAINT USU_ID_FK
       FOREIGN KEY (USU_ID) REFERENCES USUARIO(USU_ID)
     )"
   );

   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS ROL (
      ROL_ID TEXT NOT NULL,
      ROL_DES TEXT NOT NULL,
      CONSTRAINT ROL_PK
       PRIMARY KEY(ROL_ID),
      CONSTRAINT ROL_ID_NV
       CHECK(LENGTH(ROL_ID) > 0),
      CONSTRAINT ROL_DES_UNQ
       UNIQUE(ROL_DES),
      CONSTRAINT ROL_DES_NV
       CHECK(LENGTH(ROL_DES) > 0)
     )'
   );
   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS USU_ROL (
      USU_ID INTEGER NOT NULL,
      ROL_ID TEXT NOT NULL,
      CONSTRAINT USU_ROL_PK
       PRIMARY KEY(USU_ID, ROL_ID),
      CONSTRAINT USU_ROL_USU_FK
       FOREIGN KEY (USU_ID) REFERENCES USUARIO(USU_ID),
      CONSTRAINT USU_ROL_ROL_FK
       FOREIGN KEY (ROL_ID) REFERENCES ROL(ROL_ID)
     )'
   );

   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS LIGA_EQUIPO (
      LIG_ID INTEGER NOT NULL,
      EQI_ID INTEGER NOT NULL,
      EQI_NPAR INTEGER,
      EQI_NGAN INTEGER,
      EQI_NEMP INTEGER,
      EQI_PUNTOS INTEGER,
      CONSTRAINT LIG_EQI_PK
       PRIMARY KEY(LIG_ID, EQI_ID),
      CONSTRAINT LIG_EQI_LIG_FK
       FOREIGN KEY (LIG_ID) REFERENCES LIGAS(LIG_ID),
      CONSTRAINT LIG_EQI_EQI_FK
       FOREIGN KEY (EQI_ID) REFERENCES EQUIPOS(EQI_ID)
     )'
   );


   if (selectFirst(self::$pdo, ROL, [ROL_ID => "Administrador"]) === false) {
    insert(
     pdo: self::$pdo,
     into: ROL,
     values: [
      ROL_ID => "Administrador",
      ROL_DES => "Administrador de una o mas ligas"
     ]
    );
   }
   if (selectFirst(self::$pdo, ROL, [ROL_ID => "SuperAdmin"]) === false) {
    insert(
     pdo: self::$pdo,
     into: ROL,
     values: [
      ROL_ID => "SuperAdmin",
      ROL_DES => "Administra el sistema"
     ]
    );
   }
   if (selectFirst(self::$pdo, ROL, [ROL_ID => "Capitan"]) === false) {
    insert(
     pdo: self::$pdo,
     into: ROL,
     values: [
      ROL_ID => "Capitan",
      ROL_DES => "Administra uno o mas equipos"
     ]
    );
   }
   if (selectFirst(self::$pdo, ROL, [ROL_ID => "Albitro"]) === false) {
    insert(
     pdo: self::$pdo,
     into: ROL,
     values: [
      ROL_ID => "Albitro",
      ROL_DES => "Asigna los resultados de un partido"
     ]
    );
   }
  }

  if (selectFirst(self::$pdo, USUARIO, [USU_EMAIL => "admin@gmail.com"]) === false) {
    insert(
     pdo: self::$pdo,
     into: USUARIO,
     values: [
      USU_NOM => "admin",
      USU_PASS => password_hash("admin", PASSWORD_DEFAULT),
      USU_EMAIL => "admin@gmail.com"
     ]
    );
    $usuId = self::$pdo->lastInsertId();
    insertBridges(
     pdo: self::$pdo,
     into: USU_ROL,
     valuesDePadre: [USU_ID => $usuId],
     valueDeHijos: [ROL_ID => ["SuperAdmin"]]
    );
   }

  return self::$pdo;
 }
}
