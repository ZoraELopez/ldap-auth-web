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
