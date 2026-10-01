<?php

header("Content-Type: application/json; charset=utf-8");
require_once "conexao.php";

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'POST') {

    // Lê o corpo da requisição (JSON)
    $dados = json_decode(file_get_contents("php://input"), true);

    if (
        empty($dados['titulo']) ||
        empty($dados['plataforma']) ||
        empty($dados['genero']) ||
        empty($dados['desenvolvedora']) ||
        !isset($dados['ano_lancamento']) ||
        !isset($dados['preco']) ||
        !isset($dados['estoque'])
    ) {
        http_response_code(400);
        echo json_encode(["erro" => "Todos os campos são obrigatórios."]);
        exit;
    }

    try {
        $sql = "INSERT INTO jogos 
                (titulo, plataforma, genero, desenvolvedora, ano_lancamento, preco, estoque)
                VALUES (:titulo, :plataforma, :genero, :desenvolvedora, :ano, :preco, :estoque)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':titulo',         $dados['titulo']);
        $stmt->bindValue(':plataforma',     $dados['plataforma']);
        $stmt->bindValue(':genero',         $dados['genero']);
        $stmt->bindValue(':desenvolvedora', $dados['desenvolvedora']);
        $stmt->bindValue(':ano',            $dados['ano_lancamento'], PDO::PARAM_INT);
        $stmt->bindValue(':preco',          $dados['preco']);
        $stmt->bindValue(':estoque',        $dados['estoque'], PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["mensagem" => "Jogo cadastrado com sucesso!"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao cadastrar: " . $e->getMessage()]);
    }

} elseif ($metodo === 'GET') {

    try {
        $sql = "SELECT * FROM jogos ORDER BY titulo ASC";
        $stmt = $conexao->query($sql);
        $jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($jogos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao listar: " . $e->getMessage()]);
    }

} else {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido."]);
}