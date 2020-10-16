<?php
session_start();
ob_start();
$btnCadUsuario = filter_input(INPUT_POST, 'btnCadUsuario', FILTER_SANITIZE_STRING);
if($btnCadUsuario){
	include_once 'conexao.php';
	$dados_rc = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	
	$erro = false;
	
	$dados_st = array_map('strip_tags', $dados_rc);
	$dados = array_map('trim', $dados_st);
	
	if(in_array('',$dados)){
		$erro = true;
		$_SESSION['msg'] = "Necessário preencher todos os campos";
	}elseif((strlen($dados['senha'])) < 6){
		$erro = true;
		$_SESSION['msg'] = "A senha deve ter no minímo 6 caracteres";
	}elseif(stristr($dados['senha'], "'")) {
		$erro = true;
		$_SESSION['msg'] = "Caracter ( ' ) utilizado na senha é inválido";
	}else{
		$result_cpf = "SELECT id FROM usuarios WHERE usuario='". $dados['cpf'] ."'";
		$resultado_cpf = mysqli_query($conn, $result_cpf);
		if(($resultado_cpf) AND ($resultado_cpf->num_rows != 0)){
			$erro = true;
			$_SESSION['msg'] = "Este cpf já está sendo utilizado";
		}
		
		$result_cpf = "SELECT id FROM usuarios WHERE email='". $dados['email'] ."'";
		$resultado_cpf = mysqli_query($conn, $result_cpf);
		if(($resultado_cpf) AND ($resultado_cpf->num_rows != 0)){
			$erro = true;
			$_SESSION['msg'] = "Este e-mail já está cadastrado";
		}
	}
	
	
	//var_dump($dados);
	if(!$erro){
		//var_dump($dados);
		$dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
		
		$result_cpf = "INSERT INTO usuarios (nome, email, cpf, senha) VALUES (
						'" .$dados['nome']. "',
						'" .$dados['email']. "',
						'" .$dados['cpf']. "',
						'" .$dados['senha']. "'
						)";
		$resultado_usario = mysqli_query($conn, $result_cpf);
		if(mysqli_insert_id($conn)){
			$_SESSION['msgcad'] = "Usuário cadastrado com sucesso";
			header("Location: login.php");
		}else{
			$_SESSION['msg'] = "Erro ao cadastrar o usuário";
		}
	}
	
}
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Cadastro</title>
	</head>
	<body>
		<h2>Cadastro</h2>
		<?php
			if(isset($_SESSION['msg'])){
				echo $_SESSION['msg'];
				unset($_SESSION['msg']);
			}
		?>
		<form method="POST" action="">
			<label>Nome</label>
			<input type="text" name="nome" placeholder="Digite o nome e o sobrenome"><br><br>
			
			<label>E-mail</label>
			<input type="text" name="email" placeholder="Digite o seu e-mail"><br><br>
			
			<label>Cpf</label>
			<input type="char" name="cpf" placeholder="Digite o cpf"><br><br>
			
			<label>Senha</label>
			<input type="password" name="senha" placeholder="Digite a senha"><br><br>
			
			<input type="submit" name="btnCadUsuario" value="Cadastrar"><br><br>
			
			Lembrou? <a href="login.php">Clique aqui</a> para logar
		
		</form>
	</body>
</html>