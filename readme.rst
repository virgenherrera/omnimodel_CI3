# omnimodel_CI3
Este modelo es útil si deseas hacer consultas tipo array usando la clase Active Record de codeigniter3

#Requerimentos
CI3
autoload configs
	$autoload['libraries'] = array('database');
	$autoload['model'] = array('omni_model');

	opcionalmente puedes renombrar la llamada a omni_model reemplazando la segunda autoload config a:
	$autoload['model'] = array(array('omni_model'=>'FOO'));

	