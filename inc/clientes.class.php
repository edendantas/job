<?php 
class Clientes{
	private $conexao;
	
	public function __construct(){
		try {
			date_default_timezone_set('America/Sao_Paulo');
			$this->conexao = new PDO('mysql:host=localhost;dbname=eden', "root",""); // Config mysql
			$this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $e){
			return null;
		}
	}
	
	public function listar_clientes(){
		try {
			$clientes = $this->conexao->query("SELECT * FROM clientes ORDER BY ID DESC LIMIT 50");
			return $clientes->fetchAll(PDO::FETCH_ASSOC);
		}catch (PDOException $e){
			return null;
		}
	}
	
	public function cadastrar_cliente($nome, $sobrenome, $cpf, $rg, $telefone, $email, $estado, $cidade, $rua, $numero, $bairro){
		try {
			
			$verificar_cliente = $this->conexao->prepare("SELECT COUNT(*) FROM clientes WHERE cpf = :cpf");
			$verificar_cliente->bindParam(':cpf', $cpf);
			$verificar_cliente->execute();
			
			if($verificar_cliente->fetchColumn() > 0){
				return "clientejaexiste";
			}else{
				$cpf = preg_replace("/[^0-9]/", "", $cpf);
				$cadastrar = $this->conexao->prepare("INSERT INTO clientes (nome, sobrenome, cpf, rg, telefone, email, cidade, estado, rua, numero, bairro) VALUES (:nome, :sobrenome, :cpf, :rg, :telefone, :email, :cidade, :estado, :rua, :numero, :bairro)");
				$cadastrar->bindParam(':nome', $nome);
				$cadastrar->bindParam(':sobrenome', $sobrenome);
				$cadastrar->bindParam(':cpf', $cpf);
				$cadastrar->bindParam(':rg', $rg);
				$cadastrar->bindParam(':telefone', $telefone);
				$cadastrar->bindParam(':email', $email);
				$cadastrar->bindParam(':cidade', $cidade);
				$cadastrar->bindParam(':estado', $estado);
				$cadastrar->bindParam(':rua', $rua);
				$cadastrar->bindParam(':numero', $numero);
				$cadastrar->bindParam(':bairro', $bairro);
				$cadastrar->execute();
				
				$id = $this->conexao->lastInsertId();
				
				return "sucesso";
			}
		}catch (PDOException $e){
			return "error";
		}
	}
	
	public function editar_cliente($nome, $sobrenome, $cpf, $rg, $telefone, $email, $estado, $cidade, $rua, $numero, $bairro){
		try {
				$cpf = preg_replace("/[^0-9]/", "", $cpf);
				$dados = $this->conexao->query("SELECT * FROM clientes WHERE cpf = $cpf");
				$dados = $dados->fetch();
				
				if(isset($dados)){
					$atualizar = $this->conexao->prepare("UPDATE clientes SET nome = :nome, sobrenome = :sobrenome, rg = :rg, telefone = :telefone, email = :email, estado = :estado, cidade = :cidade, rua = :rua, numero = :numero, bairro = :bairro WHERE cpf = :cpf");
					$atualizar->bindParam(':nome', $nome);
					$atualizar->bindParam(':sobrenome', $sobrenome);
					$atualizar->bindParam(':cpf', $cpf);
					$atualizar->bindParam(':rg', $rg);
					$atualizar->bindParam(':telefone', $telefone);
					$atualizar->bindParam(':email', $email);
					$atualizar->bindParam(':cidade', $cidade);
					$atualizar->bindParam(':estado', $estado);
					$atualizar->bindParam(':rua', $rua);
					$atualizar->bindParam(':numero', $numero);
					$atualizar->bindParam(':bairro', $bairro);
					$atualizar->execute();
					
					return "sucesso";
				}else{
					return "error";
				}
				
		}catch (PDOException $e){
			return "error";
		}
	}
	
	public function deletar_cliente($id){
		try {
			$deletar = $this->conexao->prepare("DELETE FROM clientes WHERE id = :id");
			$deletar->bindParam(':id', $id);
			$deletar->execute();
			return "sucesso";
		}catch (PDOException $e){
			return "error";
		}
	}
}