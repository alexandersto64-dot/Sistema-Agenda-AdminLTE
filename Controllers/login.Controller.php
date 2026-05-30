<?php

require_once "Models/Conexion.php";

class LoginController{

    public function ctrIngreso(){

        if(session_status() == PHP_SESSION_NONE){

            session_start();

        }

        if(isset($_POST["usuario"])){

            /* =========================================
               VALIDAR TOKEN CSRF
            ========================================== */

            if(

                !isset($_POST["token"]) ||

                !isset($_SESSION["token"]) ||

                !hash_equals(
                    $_SESSION["token"],
                    $_POST["token"]
                )

            ){

                die("Token inválido");

            }

            /* =========================================
               SANITIZAR DATOS
            ========================================== */

            $usuario = trim(
                htmlspecialchars($_POST["usuario"])
            );

            $password = trim($_POST["password"]);

            if(empty($usuario) || empty($password)){

                echo '<div class="alert alert-warning">
                        Complete todos los campos
                      </div>';

                return;

            }

            /* =========================================
               CONEXION
            ========================================== */

            $pdo = Conexion::conectar();

            $stmt = $pdo->prepare(

                "SELECT * FROM usuarios
                 WHERE usuario = :usuario
                 AND estado = 1
                 LIMIT 1"

            );

            $stmt->bindParam(
                ":usuario",
                $usuario,
                PDO::PARAM_STR
            );

            $stmt->execute();

            $usuarioDB = $stmt->fetch();

            /* =========================================
               USUARIO EXISTE
            ========================================== */

            if($usuarioDB){

                /* =========================================
                   CONTROL DE INTENTOS
                ========================================== */

                if($usuarioDB["intentos"] >= 5){

                    $ultimo = strtotime(
                        $usuarioDB["ultimo_intento"]
                    );

                    $actual = time();

                    $minutos = ($actual - $ultimo) / 60;

                    if($minutos < 10){

                        echo '<div class="alert alert-danger">
                                Cuenta bloqueada temporalmente.
                                Espere 10 minutos.
                              </div>';

                        return;

                    }else{

                        /* RESET AUTOMATICO */

                        $reset = $pdo->prepare(

                            "UPDATE usuarios
                             SET intentos = 0
                             WHERE id_usuario = :id"

                        );

                        $reset->bindParam(
                            ":id",
                            $usuarioDB["id_usuario"],
                            PDO::PARAM_INT
                        );

                        $reset->execute();

                    }

                }

                /* =========================================
                   VERIFICAR PASSWORD
                ========================================== */

                if(password_verify(

                    $password,
                    $usuarioDB["password"]

                )){

                    /* =========================================
                       REGENERAR SESION
                    ========================================== */

                    session_regenerate_id(true);

                    //  LIMPIAR SESIÓN COMPLETA (CLAVE PARA EVITAR USUARIO PEGADO)
                    $_SESSION = [];

                    // NUEVO TOKEN
                    $_SESSION["token"] = bin2hex(random_bytes(32));

                    /* NUEVO TOKEN */

                    $_SESSION["token"] =
                        bin2hex(random_bytes(32));

                    /* =========================================
                       VARIABLES DE SESION
                    ========================================== */

                    $_SESSION["id_usuario"] =
                        $usuarioDB["id_usuario"];

                    $_SESSION["nombre"] =
                        $usuarioDB["nombre"];

                    $_SESSION["usuario"] =
                        $usuarioDB["usuario"];

                    $_SESSION["foto"] =
                        $usuarioDB["foto"];

                    $_SESSION["rol"] =
                        $usuarioDB["rol"];

                    /* =========================================
                       RESET INTENTOS
                    ========================================== */

                    $reset = $pdo->prepare(

                        "UPDATE usuarios
                         SET intentos = 0
                         WHERE id_usuario = :id"

                    );

                    $reset->bindParam(
                        ":id",
                        $usuarioDB["id_usuario"],
                        PDO::PARAM_INT
                    );

                    $reset->execute();

                    header("Location: index.php");

                    exit();

                }else{

                    /* =========================================
                       SUMAR INTENTOS
                    ========================================== */

                    $update = $pdo->prepare(

                        "UPDATE usuarios
                         SET intentos = intentos + 1,
                             ultimo_intento = NOW()
                         WHERE id_usuario = :id"

                    );

                    $update->bindParam(
                        ":id",
                        $usuarioDB["id_usuario"],
                        PDO::PARAM_INT
                    );

                    $update->execute();

                    echo '<div class="alert alert-danger">
                            Contraseña incorrecta
                          </div>';

                }

            }else{

                echo '<div class="alert alert-danger">
                        Usuario no encontrado
                      </div>';

            }

        }

    }

}