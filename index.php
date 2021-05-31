	<form method="GET">
		<label>Origem</label><input type="text" id="origem" name="origem" onkeyup="maskCep(this.id)">
		<label>Destino</label><input type="text" id="destino" name="destino" onkeyup="maskCep(this.id)">
		<input type="submit" name="enviar" value="enviar">
	</form>


	<script type="text/javascript">
		
		function maskCep(id){
			if(document.getElementById(id).value.length == 5){
				document.getElementById(id).value = document.getElementById(id).value + '-';
			}
		}

		
	</script>

<?php
		
	use FlyingLuscas\Correios\Client;
	use FlyingLuscas\Correios\Service;
	
	require 'vendor/autoload.php';

	$correios = new Client;

	if(isset($_GET['enviar'])){
		//echo $_GET['origem'];
		//echo $_GET['destino'];	
	}
	
	$errors = [];
	$precos = [];

	try {
			$correios = $correios->freight()
    		->origin(@$_GET['origem'])
    		->destination(@$_GET['destino'])
    		->services(Service::SEDEX, Service::PAC)
    		->item(16, 16, 16, .3, 1) // largura, altura, comprimento, peso e quantidade
    		->calculate();
    		//print_r($correios);
    		echo '<hr>';
    		foreach ($correios as $key => $value) {
    				echo 'Valor '.$value['name'].' = R$ '.$value['price'];
    				echo '<br>';
    				echo '<hr>';
    				$precos[] = $value;

    			if(isset($value['error']['message'])){
    				$errors[] = $value['error']['message'];
    			}	
    		}
    		$ultimoErro = '';
    		foreach ($errors as $key => $value) {
    			if($ultimoErro == $value){
					continue;    				
    			}
    			$ultimoErro = $value;
    			echo $value.'<br>';
    		}

    		
		}catch(Exception $e) {
			echo $e;	
		};