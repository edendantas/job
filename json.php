<?php
header('Access-Control-Allow-Origin: *');
header('Content-type:application/json;charset=utf-8');

if (!empty($_POST)) {
	
	require("inc/clientes.class.php");
	
    switch ($_POST) {
        case isset($_POST['listar_clientes']):

			$clientes = new Clientes();
			
			if(empty($clientes->listar_clientes())){
				echo json_encode(null);
				exit;
			}

			$juntar_clientes = array();
			
			foreach($clientes->listar_clientes() as $cliente){
				$btn_acao = '<a onclick="modal_editar(this);" href="#" class="edit"><i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a><a onclick="modal_deletar($(this).closest(\'tr\').attr(\'id\'));" href="#" class="delete"><i class="material-icons" data-toggle="tooltip" title="Deletar">&#xE872;</i></a>';
				$juntar_clientes[] = array($cliente['id'],$cliente['nome'],$cliente['sobrenome'],$cliente['cpf'],$cliente['rg'],$cliente['telefone'],$cliente['email'],$cliente['cidade'],$cliente['estado'],$cliente['rua'],$cliente['numero'],$cliente['bairro'],$btn_acao);
			}
			
			echo json_encode(array("data"=>$juntar_clientes));
			
		break;
		case isset($_POST['cadastrar_cliente']):
		
			if(!isset($_POST['nome'])){
				echo json_encode(array("error"=>"true", "msg"=>"Nome inválido!"));
				exit;
			}else if(!isset($_POST['sobrenome'])){
				echo json_encode(array("error"=>"true", "msg"=>"Sobrenome inválido!"));
				exit;
			}else if(!isset($_POST['cpf'])){
				echo json_encode(array("error"=>"true", "msg"=>"CPF inválido!"));
				exit;
			}else if(strlen(preg_replace("/[^0-9]/", "", $_POST['cpf'])) !== 11){
				echo json_encode(array("error"=>"true", "msg"=>"CPF inválido!"));
				exit;
			}else if(!isset($_POST['estado'])){
				echo json_encode(array("error"=>"true", "msg"=>"O estado é inválido!"));
				exit;
			}else if(!isset($_POST['cidade'])){
				echo json_encode(array("error"=>"true", "msg"=>"Informe a cidade"));
				exit;
			}else if(!isset($_POST['rua'])){
				echo json_encode(array("error"=>"true", "msg"=>"Rua inválida!"));
				exit;
			}else if(!isset($_POST['numero'])){
				echo json_encode(array("error"=>"true", "msg"=>"Número da rua inválida!"));
				exit;
			}else if(!isset($_POST['bairro'])){
				echo json_encode(array("error"=>"true", "msg"=>"Bairro inválido!"));
				exit;
			}
			
			$rg = isset($_POST['rg']) ? $_POST['rg'] : null;
			$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;
			$email = isset($_POST['email']) ? $_POST['email'] : null;
			
			$clientes = new Clientes();
			
			$retorno = $clientes->cadastrar_cliente($_POST['nome'], $_POST['sobrenome'], $_POST['cpf'], $rg, $telefone, $email, $_POST['estado'], $_POST['cidade'], $_POST['rua'], $_POST['numero'], $_POST['bairro']);
			
			if($retorno == "clientejaexiste"){
				echo json_encode(array("error"=>"true", "msg"=>"ID inválido!"));
			}else if(strpos($retorno,"sucesso") !== false){
				echo json_encode(array("error"=>"false", "msg"=>"Cadastrado com sucesso!"));
			}else{
				echo json_encode(array("error"=>"true", "msg"=>"Falhou ao cadastrar o cliente!"));
			}
			
		break;
		case isset($_POST['editar_cliente']):
		
			if(!isset($_POST['nome'])){
				echo json_encode(array("error"=>"true", "msg"=>"Nome inválido!"));
				exit;
			}else if(!isset($_POST['sobrenome'])){
				echo json_encode(array("error"=>"true", "msg"=>"Sobrenome inválido!"));
				exit;
			}else if(!isset($_POST['cpf'])){
				echo json_encode(array("error"=>"true", "msg"=>"CPF inválido!"));
				exit;
			}else if(strlen(preg_replace("/[^0-9]/", "", $_POST['cpf'])) !== 11){
				echo json_encode(array("error"=>"true", "msg"=>"CPF inválido!"));
				exit;
			}else if(!isset($_POST['estado'])){
				echo json_encode(array("error"=>"true", "msg"=>"O estado é inválido!"));
				exit;
			}else if(!isset($_POST['cidade'])){
				echo json_encode(array("error"=>"true", "msg"=>"Informe a cidade"));
				exit;
			}else if(!isset($_POST['rua'])){
				echo json_encode(array("error"=>"true", "msg"=>"Rua inválida!"));
				exit;
			}else if(!isset($_POST['numero'])){
				echo json_encode(array("error"=>"true", "msg"=>"Número da rua inválida!"));
				exit;
			}else if(!isset($_POST['bairro'])){
				echo json_encode(array("error"=>"true", "msg"=>"Bairro inválido!"));
				exit;
			}
			
			$rg = isset($_POST['rg']) ? $_POST['rg'] : null;
			$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;
			$email = isset($_POST['email']) ? $_POST['email'] : null;
			
			$clientes = new Clientes();
			
			$retorno = $clientes->editar_cliente($_POST['nome'], $_POST['sobrenome'], $_POST['cpf'], $rg, $telefone, $email, $_POST['estado'], $_POST['cidade'], $_POST['rua'], $_POST['numero'], $_POST['bairro']);
			
			if(strpos($retorno,"sucesso") !== false){
				echo json_encode(array("error"=>"false", "msg"=>"Editado com sucesso!"));
			}else{
				echo json_encode(array("error"=>"true", "msg"=>"Falhou ao atualizar o cliente!"));
			}
			
		break;
		case isset($_POST['deletar_cliente']):
		
			if(!isset($_POST['id'])){
				echo json_encode(array("error"=>"true", "msg"=>"ID inválido!"));
				exit;
			}
			
			if(!is_numeric($_POST['id'])){
				echo json_encode(array("error"=>"true", "msg"=>"ID inválido!"));
				exit;
			}
		
			$clientes = new Clientes();
			
			$retorno = $clientes->deletar_cliente($_POST['id']);
			
			if($retorno == "sucesso"){
				echo json_encode(array("error"=>"false", "msg"=>"Cadastro do cliente deletado com sucesso!"));
			}else{
				echo json_encode(array("error"=>"true", "msg"=>"Falhou ao deletar o cliente!"));
			}
			
		break;
	}
}