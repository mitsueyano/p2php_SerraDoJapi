<?php //--- Função padrão para conectar ao database de dados ---//
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'ecoframe';
    $conn = mysqli_connect($server, $user, $password, $database);
if(!$conn)
{
    echo "Não conectado ao database de dados"; 
}
?>