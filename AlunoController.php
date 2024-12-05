<?php

include_once 'Aluno.php';  // Inclui a classe Aluno

class AlunoController {

    // Método para buscar os dados de um aluno
    public function getAluno($id) {
        $aluno = Aluno::find($id);
        if ($aluno) {
            echo json_encode($aluno);
        } else {
            echo json_encode(['message' => 'Aluno não encontrado']);
        }
    }

    // Método para atualizar os dados de um aluno
    public function updateAluno($id, $desempenho, $nota, $observacao) {
        if (Aluno::update($id, $desempenho, $nota, $observacao)) {
            echo json_encode(['message' => 'Dados atualizados com sucesso']);
        } else {
            echo json_encode(['message' => 'Erro ao atualizar dados']);
        }
    }

    // Método para adicionar um novo aluno
    public function addAluno($nome, $desempenho, $nota, $observacao) {
        $id = Aluno::addAluno($nome, $desempenho, $nota, $observacao);
        if ($id) {
            echo json_encode(['message' => 'Aluno adicionado com sucesso', 'id' => $id]);
        } else {
            echo json_encode(['message' => 'Erro ao adicionar aluno']);
        }
    }
}
?>
