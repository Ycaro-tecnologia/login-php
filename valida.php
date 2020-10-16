<?php
session_start();
include_once("conexao.php");
$btnLogin = filter_input(INPUT_POST, 'btnLogin', FILTER_SANITIZE_STRING);
if($btnLogin){
	$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
	$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
	//echo "$usuario - $senha";
	if((!empty($cpf)) AND (!empty($senha))){
		//Gerar a senha criptografa
		//echo password_hash($senha, PASSWORD_DEFAULT);
		//Pesquisar o usuário no BD
		$result_cpf = "SELECT id, nome, email, senha FROM usuarios WHERE cpf='$cpf' LIMIT 1";
		$resultado_cpf = mysqli_query($conn, $result_cpf);
		if($resultado_cpf){
			$row_cpf = mysqli_fetch_assoc($resultado_cpf);
			if(password_verify($senha, $row_cpf['senha'])){
				$_SESSION['id'] = $row_cpf['id'];
				$_SESSION['nome'] = $row_cpf['nome'];
				$_SESSION['email'] = $row_cpf['email'];

				header("Location: administrativo.php");
			}else{
				$_SESSION['msg'] = "Login ou senha incorreto!";
				header("Location: login.php");
			}
		}
	}else{
		$_SESSION['msg'] = "Login e senha incorreto!";
		header("Location: login.php");
	}
}else{
	$_SESSION['msg'] = "Página não encontrada";
	header("Location: login.php");
}
