<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Lista de Clientes</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/toastr.min.css">
<script src="js/toastr.min.js"></script>
<script>
var id_do_cliente = null;

function cadastrar_cliente(){
    $.post("json.php", {cadastrar_cliente: "cadastrar_cliente", nome: $('#add_nome').val(), sobrenome: $('#add_sobrenome').val(), cpf: $('#add_cpf').val(), rg: $('#add_rg').val(), telefone: $('#add_telefone').val(), email: $('#add_email').val(), estado: $('#add_estados option:selected').text(), cidade: $('#add_cidades option:selected').val(), rua: $('#add_rua').val(), numero: $('#add_numero').val(), bairro: $('#add_bairro').val()},
            function (retorno) {
				if(retorno.error == "false"){
					toastr.success(retorno.msg, '');
					$('#Cadastrar_Cliente').modal('hide');
					$('#datatable').DataTable().ajax.reload();
				}else{
					toastr.error(retorno.msg, '');
					$('#Deletar_Cliente').modal('hide');
				}
            }
     );
}

function editar_cliente(){
    $.post("json.php", {editar_cliente: "editar_cliente", nome: $('#edit_nome').val(), sobrenome: $('#edit_sobrenome').val(), cpf: $('#edit_cpf').val(), rg: $('#edit_rg').val(), telefone: $('#edit_telefone').val(), email: $('#edit_email').val(), estado: $('#edit_estados option:selected').text(), cidade: $('#edit_cidades option:selected').val(), rua: $('#edit_rua').val(), numero: $('#edit_numero').val(), bairro: $('#edit_bairro').val()},
            function (retorno) {
				if(retorno.error == "false"){
					toastr.success(retorno.msg, '');
					$('#Editar_Cliente').modal('hide');
					$('#datatable').DataTable().ajax.reload();
				}else{
					toastr.error(retorno.msg, '');
					$('#Deletar_Cliente').modal('hide');
				}
            }
     );
}

async function modal_editar(id){
	
	var datatable = $('#datatable').DataTable();
    var dados = datatable.row($(id).closest('tr')).data();

	$('#edit_nome').val(dados[1]);
	$('#edit_sobrenome').val(dados[2]);
	$('#edit_cpf').val(dados[3]);
	$('#edit_rg').val(dados[4]);
	$('#edit_telefone').val(dados[5]);
	$('#edit_email').val(dados[6]);
	$('#edit_rua').val(dados[9]);
	$('#edit_numero').val(dados[10]);
	$('#edit_bairro').val(dados[11]);
	
	var cidade = dados[7];
	var estado = dados[8];
	
	$("#edit_estados option").filter(function() {return this.text == estado; }).attr('selected', true);
	
	$.getJSON('cidades.json', function (data) {
			$.each(data.estados, function (key, val) {
				if(val.nome == estado) {	
					$("#add_cidades").html(null);
					$.each(val.cidades, function (key_city, val_city) {
						$('#edit_cidades').append($('<option>', {
							value: val_city,
							text: val_city
						}));
					});							
				}
			});
	});
	
	await new Promise(r => setTimeout(r, 100));

	$("#edit_cidades option").filter(function() {return this.text == cidade; }).attr('selected', true);
		
	$('#Editar_Cliente').modal('show');
}

function modal_deletar(id){
	$('#Deletar_Cliente').modal('show');
	id_do_cliente = id;
}

function deletar_cliente(){
    $.post("json.php", {deletar_cliente: "deletar_cliente", id: id_do_cliente},
            function (retorno) {
				if(retorno.error == "false"){
					toastr.success(retorno.msg, '');
					$('#Deletar_Cliente').modal('hide');
					$("#"+id_do_cliente).remove();
				}else{
					toastr.error(retorno.msg, '');
					$('#Deletar_Cliente').modal('hide');
				}
            }
     );
}

function mascara_cpf(i){
   var v = i.value;
   
   if(isNaN(v[v.length-1])){ 
      i.value = v.substring(0, v.length-1);
      return;
   }
   
   i.setAttribute("maxlength", "14");
   if (v.length == 3 || v.length == 7) i.value += ".";
   if (v.length == 11) i.value += "-";
}
</script>
</head>
<body>
<div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2>Lista de <b>Clientes</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#Cadastrar_Cliente" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Cadastrar Cliente</span></a>
					</div>
				</div>
			</div>
			<table id="datatable" class="table table-striped table-hover">
				<thead>
					<tr>
						<th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
						</th>
						<th>Nome</th>
						<th>Sobrenome</th>
						<th>CPF</th>
						<th>RG</th>
						<th>Telefone</th>
						<th>Email</th>
						<th>Cidade</th>
						<th>Estado</th>
						<th>Rua</th>
						<th>Numero</th>
						<th>Bairro</th>
						<th>Ação</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>        
</div>

<div class="modal fade" id="Cadastrar_Cliente">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
				<div class="modal-header">						
					<h4 class="modal-title">Cadastrar cliente</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Nome <span class="red normal">*</span></label>
                                    <input id="add_nome" type="text" class="form-control required" id="nome" value="" name="nome" maxlength="30" aria-required="true" placeholder="Nome" required>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Sobrenome <span class="red normal">*</span></label>
                                     <input id="add_sobrenome" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Sobrenome">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>CPF <span class="red normal">*</span></label>
                                    <input oninput="mascara_cpf(this)" id="add_cpf" type="text" class="form-control required" id="nome" value="" name="nome" maxlength="30" aria-required="true" placeholder="CPF" required>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>RG </label>
                                     <input id="add_rg" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="RG">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Telefone </label>
                                    <input id="add_telefone" type="text" class="form-control required" id="nome" value="" name="nome" maxlength="30" aria-required="true" placeholder="Telefone" required>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Email </label>
                                     <input id="add_email" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Email">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Estado <span class="red normal">*</span></label>
									<select id="add_estados" class="form-control">
										<option value="">Selecione um estado</option>
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
										<option value="CE">Ceará</option>
										<option value="DF">Distrito Federal</option>
										<option value="ES">Espírito Santo</option>
										<option value="GO">Goiás</option>
										<option value="MA">Maranhão</option>
										<option value="MT">Mato Grosso</option>
										<option value="MS">Mato Grosso do Sul</option>
										<option value="MG">Minas Gerais</option>
										<option value="PA">Pará</option>
										<option value="PB">Paraíba</option>
										<option value="PR">Paraná</option>
										<option value="PE">Pernambuco</option>
										<option value="PI">Piauí</option>
										<option value="RJ">Rio de Janeiro</option>
										<option value="RN">Rio Grande do Norte</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="RO">Rondônia</option>
										<option value="RR">Roraima</option>
										<option value="SC">Santa Catarina</option>
										<option value="SP">São Paulo</option>
										<option value="SE">Sergipe</option>
										<option value="TO">Tocantins</option>
										<option value="EX">Estrangeiro</option>
									</select>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Cidade <span class="red normal">*</span></label>
                                    <select id="add_cidades" class="form-control"></select>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Rua <span class="red normal">*</span></label>
                                     <input id="add_rua" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Rua">
                                </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <label>Número <span class="red normal">*</span></label>
                                     <input id="add_numero" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Número">
                                </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>Bairro <span class="red normal">*</span></label>
                                     <input id="add_bairro" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Bairro">
                                </div>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input onclick="cadastrar_cliente();" type="submit" class="btn btn-success" value="Cadastrar">
				</div>
		</div>
	</div>
</div>

<div class="modal fade" id="Editar_Cliente">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
				<div class="modal-header">						
					<h4 class="modal-title">Editar cliente</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Nome <span class="red normal">*</span></label>
                                    <input id="edit_nome" type="text" class="form-control required" id="nome" value="" name="nome" maxlength="30" aria-required="true" placeholder="Nome" required>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Sobrenome <span class="red normal">*</span></label>
                                     <input id="edit_sobrenome" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Sobrenome">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>CPF <span class="red normal">*</span></label>
                                    <input id="edit_cpf" type="text" class="form-control required" id="nome" value="" name="nome" maxlength="30" aria-required="true" placeholder="CPF" disabled>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>RG </label>
                                     <input id="edit_rg" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="RG">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Telefone </label>
                                    <input id="edit_telefone" type="text" class="form-control required" id="nome" value="" name="nome" maxlength="30" aria-required="true" placeholder="Telefone" required>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Email </label>
                                     <input id="edit_email" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Email">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Estado <span class="red normal">*</span></label>
									<select id="edit_estados" class="form-control">
										<option value="">Selecione um estado</option>
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
										<option value="CE">Ceará</option>
										<option value="DF">Distrito Federal</option>
										<option value="ES">Espírito Santo</option>
										<option value="GO">Goiás</option>
										<option value="MA">Maranhão</option>
										<option value="MT">Mato Grosso</option>
										<option value="MS">Mato Grosso do Sul</option>
										<option value="MG">Minas Gerais</option>
										<option value="PA">Pará</option>
										<option value="PB">Paraíba</option>
										<option value="PR">Paraná</option>
										<option value="PE">Pernambuco</option>
										<option value="PI">Piauí</option>
										<option value="RJ">Rio de Janeiro</option>
										<option value="RN">Rio Grande do Norte</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="RO">Rondônia</option>
										<option value="RR">Roraima</option>
										<option value="SC">Santa Catarina</option>
										<option value="SP">São Paulo</option>
										<option value="SE">Sergipe</option>
										<option value="TO">Tocantins</option>
										<option value="EX">Estrangeiro</option>
									</select>
                                </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Cidade <span class="red normal">*</span></label>
                                    <select id="edit_cidades" class="form-control"></select>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>Rua <span class="red normal">*</span></label>
                                     <input id="edit_rua" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Rua">
                                </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <label>Número <span class="red normal">*</span></label>
                                     <input id="edit_numero" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Número">
                                </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>Bairro <span class="red normal">*</span></label>
                                     <input id="edit_bairro" type="text" class="form-control" id="doc" value="" name="doc" maxlength="100" aria-required="true" placeholder="Bairro">
                                </div>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input onclick="editar_cliente();" type="submit" class="btn btn-success" value="Atualizar">
				</div>
		</div>
	</div>
</div>

<div id="Deletar_Cliente" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">						
					<h4 class="modal-title">Deletar Cliente</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<input id="id_usuario_deletar" type="hidden">
					<h4><p>Deseja realmente deletar esse cliente?</p></h4>
					<p class="text-warning"><small>Essa ação não pode ser desfeita.</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input onclick="deletar_cliente();" type="submit" class="btn btn-danger" value="Deletar">
				</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
  $('#datatable').dataTable({
	"processing": false,
	"serverSide": false,
	'createdRow': function(row, data, dataIndex) {
      $(row).attr('id', data[0]);
    },
	"ajax": {
        "url": "json.php",
        "type": "POST",
		"data": {"listar_clientes":"listar_clientes"}
    },
    "oLanguage": {
	  "sZeroRecords": "Nenhum registro econtrado",
	  "oPaginate": {
	  "sPrevious": "Anterior",
	  "sNext": "Próximo"
	  },
      "sStripClasses": "",
      "sSearch": "",
      "sSearchPlaceholder": " Pesquisar por ID, Nome, CPF...",
      "sInfo": "Mostrando _START_ de _TOTAL_ entradas",
      "sLengthMenu": '<span>Linhas por página: </span><select class="browser-default">' +
        '<option value="10">10</option>' +
        '<option value="20">20</option>' +
        '<option value="30">30</option>' +
        '<option value="40">40</option>' +
        '<option value="50">50</option>' +
        '<option value="-1">Todas</option>' +
        '</select></div>'
    },
	order: [[0, 'desc']],
    bAutoWidth: false
  });
	
  $('#add_estados').change(function () {
		var estado = $('#add_estados').find(":selected").text();
		
		$.getJSON('cidades.json', function (data) {
			$.each(data.estados, function (key, val) {
				if(val.nome == estado) {	
					$("#add_cidades").html(null);
					$.each(val.cidades, function (key_city, val_city) {
						$('#add_cidades').append($('<option>', {
							value: val_city,
							text: val_city
						}));
					});							
				}
			});
		});
	});
	
  $('#edit_estados').change(function () {
		var estado = $('#edit_estados').find(":selected").text();
		
		$.getJSON('cidades.json', function (data) {
			$.each(data.estados, function (key, val) {
				if(val.nome == estado) {	
					$("#edit_cidades").html(null);
					$.each(val.cidades, function (key_city, val_city) {
						$('#edit_cidades').append($('<option>', {
							value: val_city,
							text: val_city
						}));
					});							
				}
			});
		});
	});
});
</script>
</body>
</html>