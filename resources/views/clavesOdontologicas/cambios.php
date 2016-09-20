Sustitutir Archivos completos:
	app/Http/Controllers/GenerateXmlController.php
	app/Http/Controllers/clavesOdontologicas/GenerarController.php
	resource/views/clavesOdontologicas/gestionarDos.blade.php

Modificar Archivos:
	app/Http/routes: Las rutas para generar y procesar los archivos xml son las siguientes
		//Archivo XML
		Route::get('dataXml', 'GenerateXmlController@getData');
		Route::get('procesarResponseXml', 'GenerateXmlController@procesarResponseXml');

	composer.json: complemento para subir archivos al ftp: 
		1.  agregar: "anchu/ftp": "~2.0"
		2. config/app.php agregar la linea: Anchu\Ftp\FtpServiceProvider::class,

crear la tabla ac_transacciones_proveedor


