<?php
// Inicializar a sessão
session_start();
 
// Verifique se o usuário está logado, caso contrário, redirecione para a página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Incluir arquivo de configuração
require_once "config.php";
 
// Defina variáveis ​​e inicialize com valores vazios
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar nova senha
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = Por favor insira a nova senha."";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validar e confirmar a senha
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor confirme a senha.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }
        
    // Verifique os erros de entrada antes de atualizar o banco de dados
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare uma declaração de atualização
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Vincular variáveis ​​à instrução preparada como parâmetros
            $stmt->bind_param("si", $param_password, $param_id);
            
            // Definir parâmetros
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                // Senha atualizada com sucesso. Destrua a sessão e redirecione para a página de login
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            $stmt->close();
        }
    }
    
    // Fechar conexão
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar .::. iFome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <section class="vh-100" style="background-color: #6A5ACD;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                <div class="card-body p-5">

                    <h3 class="text-center mb-0">Crie sua senha do iFome</h3>
                    <p class="mb-5 text-center small color:ff00ff">e <strong>mate</strong> quem está lhe matando!</p>


                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-outline mb-4">
                            <label class="form-label" for="new_password">Nova senha</label>
                            <input type="password" name="new_password" class="form-control form-control-lg <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                            <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                        </div>

                        <div class="form-outline mb-4">
                            <label class="form-label" for="confirm_password">Confirmar nova senha</label>
                            <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>

                        <div class="form-outline mb-4 text-center">
                            <button class="btn btn-danger btn-lg btn-block text-center" type="submit">Entrar no iFome</button>
                        </div>

                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0 text-muted">OU</p>
                        </div>

                        <p class="text-center"><a href="index.php" class="text-decoration-none" type="submit">Voltar para a página inicial</a>.</p>

                    </form>         

                </div>
                </div>
            </div>
            </div>
        </div>
    </section>
</body>
</html>