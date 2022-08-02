# laravel-prueba-xhash

detalles:
- se decidiío generar una base de datos para el manejo del API, para esto se usó el artisan para generar models-migrations y asi tener mejor manejo de los datos.
- Se creo una route en el archivo api.php apuntando a un controlador ubicado en app/http/api/ZipCodeController - fetch(param).
- en la route se implementó un whereNumber manejo de caracteres y asi impedir inyections.
- Para la lectura del excel se hizo un comando llamado php artisan xlsx:read y asi insertar todos los datos a la bd.
- en la function fetch del ZipCodeController es donde se hace lectura de la bd
- para la lectura del excel se implementó el package maatwebsite/excel ya que se hace más facil la lectura de estos archivos usando collections.
- se usaron manejos de try y catch para prevenir errores de queries y de datos no encontrados
- El servidor está montado en digitalocean siguiendo los pasos de configuracion recomendado.
