<?php

include 'config.php'; // Para utilizar a conexão com o banco

class Aluno {
    public $id;
    public $nome;
    public $desempenho;
    public $nota;
    public $observacao;
    public $historico_notas;

    // Construtor da classe
    public function __construct($id, $nome, $desempenho, $nota, $observacao, $historico_notas) {
        $this->id = $id;
        $this->nome = $nome;
        $this->desempenho = $desempenho;
        $this->nota = $nota;
        $this->observacao = $observacao;
        $this->historico_notas = json_decode($historico_notas); // Converte de string JSON para array
    }

    // Método para buscar um aluno por ID
    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM desempenho_dos_alunos WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Aluno($result['id'], $result['nome'], $result['desempenho'], $result['nota'], $result['observacao'], $result['historico_notas']);
        }
        return null;
    }

    // Método para atualizar a nota de um aluno e salvar no banco de dados
    public function atualizarNota($nota) {
        $this->historico_notas[] = $nota;  // Adiciona a nova nota ao histórico
        $this->nota = $nota;

        // Atualiza o banco de dados com a nova nota e o histórico
        global $pdo;
        $historico_json = json_encode($this->historico_notas);  // Converte o histórico para JSON
        $stmt = $pdo->prepare("UPDATE desempenho_dos_alunos SET nota = ?, historico_notas = ? WHERE id = ?");
        $stmt->execute([$this->nota, $historico_json, $this->id]);
    }

    // Método para atualizar o desempenho, nota, observação e histórico
    public static function update($id, $desempenho, $nota, $observacao) {
        global $pdo;
        // Primeiro, atualiza a tabela principal
        $stmt = $pdo->prepare("UPDATE desempenho_dos_alunos SET desempenho = ?, nota = ?, observacao = ? WHERE id = ?");
        $stmt->execute([$desempenho, $nota, $observacao, $id]);

        // Agora, atualiza o histórico de notas
        $aluno = self::find($id);
        $historico_notas = $aluno->historico_notas;
        $historico_notas[] = $nota;  // Adiciona a nova nota ao histórico
        $historico_json = json_encode($historico_notas);

        // Atualiza o histórico no banco de dados
        $stmt = $pdo->prepare("UPDATE desempenho_dos_alunos SET historico_notas = ? WHERE id = ?");
        return $stmt->execute([$historico_json, $id]);
    }

    // Método para adicionar um novo aluno
    public static function addAluno($nome, $desempenho, $nota, $observacao) {
        global $pdo;
        $historico_notas = json_encode([$nota]);  // Começa com a primeira nota no histórico
        $stmt = $pdo->prepare("INSERT INTO desempenho_dos_alunos (nome, desempenho, nota, observacao, historico_notas) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $desempenho, $nota, $observacao, $historico_notas]);

        // Retorna o último ID inserido
        return $pdo->lastInsertId();
    }
}
?>
