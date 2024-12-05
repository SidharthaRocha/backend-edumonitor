<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se os campos obrigatórios estão presentes
    if (isset($_POST['nome'], $_POST['usuario'], $_POST['email'], $_POST['data_nascimento'])) {
        // Recupera os dados do formulário
        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $email = $_POST['email'];
        $dataNascimento = $_POST['data_nascimento'];

        // Inicializa a variável para a imagem
        $imagemNome = null;

        // Verifica se há um arquivo de imagem enviado
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imagem = $_FILES['imagem'];

            // Recupera o nome da imagem e o caminho temporário
            $imagemNome = $imagem['name'];
            $imagemTmp = $imagem['tmp_name'];

            // Define o diretório para onde a imagem será movida
            $imagemPath = 'images/' . basename($imagemNome);

            // Verifica se a imagem tem um tipo válido (jpeg ou png)
            $imagemTipo = mime_content_type($imagemTmp);
            if ($imagemTipo == 'image/jpeg' || $imagemTipo == 'image/png') {
                // Verifica o tamanho do arquivo (exemplo: max 2MB)
                if ($imagem['size'] <= 2 * 1024 * 1024) { // 2MB
                    // Tenta mover a imagem para o diretório 'images' no servidor
                    if (move_uploaded_file($imagemTmp, $imagemPath)) {
                        // Se o upload for bem-sucedido
                        echo json_encode([
                            'status' => 'success',
                            'nome' => $nome,
                            'usuario' => $usuario,
                            'email' => $email,
                            'data_nascimento' => $dataNascimento,
                            'imagem' => $imagemNome
                        ]);
                    } else {
                        // Caso o upload da imagem falhe
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Erro ao mover o arquivo da imagem.'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'A imagem é muito grande. O tamanho máximo permitido é 2MB.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'O arquivo não é uma imagem válida. Aceitamos apenas imagens JPEG ou PNG.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nenhuma imagem foi enviada ou houve um erro no upload.'
            ]);
        }
    } else {
        // Se algum campo obrigatório estiver ausente
        echo json_encode([
            'status' => 'error',
            'message' => 'Todos os campos obrigatórios (nome, usuário, email e data de nascimento) devem ser preenchidos.'
        ]);
    }
}
?>
 