# Autenticación LDAP Web

## 1. ¿Qué es autenticación LDAP?

LDAP (Lightweight Directory Access Protocol) es un protocolo que permite validar usuarios 
contra un servidor centralizado. Esto permite que múltiples sistemas utilicen las mismas 
credenciales para autenticación.

---

## 2. Flujo de autenticación

El proceso de autenticación funciona de la siguiente manera:

1. El usuario ingresa su usuario y contraseña en la página web.
2. El sistema PHP recibe los datos mediante un formulario.
3. PHP construye el DN del usuario en LDAP.
4. Se realiza una conexión al servidor LDAP.
5. Se intenta autenticar con `ldap_bind`.
6. Si las credenciales son correctas, se permite el acceso.
7. Si son incorrectas, se rechaza el acceso.

---

## 3. Código PHP

El sistema utiliza PHP para conectarse a LDAP y validar usuarios en múltiples unidades organizativas.

<?php
$ldapconn = ldap_connect("ldap://localhost");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

$user = $_POST['user'];
$pass = $_POST['pass'];

$dn = "uid=$user,ou=IT,dc=empresa,dc=com";

if (@ldap_bind($ldapconn, $dn, $pass)) {
        echo "Autenticacion exitosa";
} else {
        echo "Error de autenticacion";
}
?>
---

## 4. Extensión implementada

Se modificó el sistema para permitir autenticación desde dos unidades organizativas diferentes:

* IT
* Soporte

El sistema intenta autenticarse primero en IT y, si falla, intenta en Soporte.

Nuevo Login:

<?php
$ldapconn = ldap_connect("ldap://localhost");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

// Validar entrada
if (!isset($_POST['user']) || !isset($_POST['pass'])) {
    echo "Acceso inválido";
    exit;
}

$user = $_POST['user'];
$pass = $_POST['pass'];

// Lista de OU
$ous = ["IT", "Soporte"];

$autenticado = false;

foreach ($ous as $ou) {
    $dn = "uid=$user,ou=$ou,dc=empresa,dc=com";

    if (ldap_bind($ldapconn, $dn, $pass)) {
        echo "✅ Autenticación exitosa en $ou: $user";
        $autenticado = true;
        break;
    }
}

if (!$autenticado) {
    echo "❌ Error de autenticación";
}
?>

---

## 5. Evidencias

Se incluyen capturas de:

* Ejecución de ldapwhoami (correcto)
* Ejecución de ldapwhoami (incorrecto)
* Formulario web
* Login exitoso
* Login fall

![1](evidencias/Captura174439.png)
![2](evidencias/Captura174731.png)
![3](evidencias/Captura175530.png)
![4](evidencias/Captura175931.png)
![5](evidencias/Captura185737.png)
![6](evidencias/Captura174532.png)
![7](evidencias/Captura174857.png)
![8](evidencias/Captura175602.png)
![9](evidencias/Captura175955.png)
![10](evidencias/Captura185753.png)
![11](evidencias/Captura174659.png)
![12](evidencias/Captura175132.png)
![13](evidencias/Captura175907.png)
![14](evidencias/Captura180228.png)
![15](evidencias/Captura85724.png)

