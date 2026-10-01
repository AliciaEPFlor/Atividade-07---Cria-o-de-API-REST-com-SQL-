<?php

$host = "192.168.10.11";
$usuario = "postgres";
$senha = "Senai2822@";           
$banco = "levelup";

try {
    $conexao = new PDO(
        "pgsql:host=$host;dbname=$banco", 
        $usuario,
        $senha
    );
    
    $conexao->exec("SET NAMES 'UTF8'"); 
    
    $conexao->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Falha na conexão: " . $e->getMessage()]);
    exit;
}