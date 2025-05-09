<?php //--- Função padrão para conectar ao banco de dados ---//
$server = 'localhost';
$user = 'root';
$password = '';
$banco = 'ecoframe';
$conexao = mysqli_connect($server, $user, $password, $banco);
if(!$conexao)
{
    echo "Não conectado ao banco de dados"; 
}
?>