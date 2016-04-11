#QuickStart
    1 - Copie la librería a su servidor php
    2 - Edite el fichero config.inc.php y añada sus credenciales para la conexión a Anfix como partner, dichas credenciales le serán enviadas por Anfix
      * Asegúrese que dispone de permiso de escritura en el fichero tokens_temp.php y examples/tokens.php
    3 - Ejecute el script examples/get_token.php, este le redirigirá a la página de login de anfix
      * Una vez haya finalizado este paso podrá visualizar las claves de acceso a su cuenta (token) en pantalla, además se habrán almacenado en el fichero examples/tokens.php
    4 - Copie dichas claves en el apartado default_token del fichero config.inc.php que debe quedar de la siguiente forma: default_token => ['token_obtenido','secret_obtenido']
    Ya puede acceder a su cuenta anfix como se indica en el apartado Operaciones con entidades, recuerde que dispone de muchos más ejemplos en el directorio examples

##Selección de entorno
	Antes de realizar cualquier uso de la librería podrá asignar un entorno de trabajo, (cuenta a la que desea conectarse y fichero de configuración).
	Esta selección se realiza mediante la función Anfix\Anfix::env([array $token] [, string $config_file]);
		[Parámetros]:
		token: Array conteniendo el token y la clave para dicho token, Ex: Array('USER_TOKEN','TOKEN_PASSWORD') Si no se indica se obtendrán de la clave default_token
        del fichero de configuración
		config_file: (Opcional) Path al fichero de configuración, si no se indica se obtendra config.inc.php
	Si se desea utilizar el fichero de configuración por defecto y el token y password indicados en dicho fichero bajo la clave default_token podemos omitir
	la selección de entorno
	Podremos llamar a este método en cualquier momento durante la ejecución para cambiar el entorno, los cambios afectarán a los nuevos Modelos pero no a los
	anteriormente generados. Es decir, todos aquellos Modelos que hayan sido generados con la configuración A seguirán utilizando la configuración A aunque la 
	hayamos modificado. Logicamente las llamadas a métodos estáticos aplicarán la nueva configuración
        	
##Obtención de un token
	El primer paso para poder conectar a una cuenta de Anfix será obtener un token de acceso, para esto puede observar el fichero /examples/new_token.php
	donde se indica como realizar una solicitud y registro de un token. La solicitud se basa en dos pasos bien diferenciados (Oauth1)
	El primer paso será realizar una llamada a Anfix::generateToken(string $identifier, string $returnUrl); que redirigirá a la página de login Anfix
		[Parámetros]:
		identifier: Identificador que sera devuelto junto con el token definitivo, su uso responde únicamente a poder diferenciar entre varias solicitudes
		returnUrl: Url donde deberá encontrarse el script que recibirá y almacenará el token (paso2)

	El segundo paso será esperar que el usuario se loguee en Anfix y que la API Anfix realice una llamada a returnUrl con los datos de acceso
	Para esto crearemos un script que contendrá una llamada a Anfix::onGeneratedToken(callable $function(identifier,token,secret)); 
		[Parámetros]:
		function(identifier,token,secret): Closure que se ejecutará cuando se reciba un token válido, recibe los datos del token y se utiliza para almacenar el 
		token una vez el usuario le haya autorizado el acceso a su cuenta. Recibe el identificador indicado en el paso anterior, el token y la clave del mismo

	Nota importante: Tenga en cuenta que la solicitud de token se realiza en dos pasos, el primero es lanzar la solicitud generateToken que llevará
	al usuario a la página de anfix para loguearse. En cambio la segunda parte será una llamada desde anfix al script indicado en $returnUrl, tenga en
	cuenta que dicho script deberá ser accesible publicamente desde internet, si este se encuentra en una red privada o protegido por contraseña no 
	será posible finalizar el proceso con éxito, cuando Anfix realice dicha llamada a su url le entregará el token definitivo para la cuenta del 
	usuario que se logueó en el paso 1, la función closure indicada en onGeneratedToken recibirá el token y deberá almacenarlo para su uso posterior
	El tiempo máximo para la validación de un token (entre el paso 1 y 2) es de una hora

###Invalidación de un token
    Si desea invalidar un token utilice la funcion Anfix\Anfix::invalidateToken(token,secret)
        [Parámetros]:
        token: Token a invalidar
        secret: Clave del token a invalidar	
		
#Operaciones con entidades
	El sistema trabaja con todas las entidades que se encuentran dentro del directorio /Entities
	La mayoría de estas entidades disponen de los siguientes métodos comunes:
	
	###Creación de entidades:
	Para crear nuevas entidades utilizaremos el método estático ::create(array $params [, $companyId = null] [, $path = 'create'])
		[Parámetros]:
		params: Array con los datos a introducir en la nueva entidad
		companyId: Identificador de la empresa con la que trabajar, obligatorio en algunas entidades (aquellas que guardan una relacción con una empresa determinada
                en anfix)
		path: Método anfix al que llamar remotamente (última parte del punto de acceso anfix), por defecto suele ser create pero puede especificarse otro
	Si no estamos seguros de si la entidad existe deberemos utilizar el método firstOrCreate descrito más adelante	
	
	###Búsquedas:
	Estos son los métodos estáticos para realizar búsquedas simples:
	::first(array $params [,$companyId = null]) Devuelve el primer elemento coincidente con params o null
	::firstOrCreate(array $params [,$companyId = null]) Devuelve el primer elemento coincidente con params o lo crea en anfix
	::firstOrNew(array $params [,$companyId = null]) Devuelve el primer elemento coincidente con params o devuelve un modelo con los datos, pero no lo crea en anfix     
          (podremos guardarlo manualmente si lo deseamos)
	::firstOrFail(array $params [,$companyId = null]) Devuelve el primer elemento coincidente con params o genera una excepción Anfix\Exceptions\AnfixException
	::all([$companyId = null]) Devuelve todas las entidades
	::where(array $params, [,$companyId = null]) Realiza una búsqueda de todas las entidades que cumplan los [Parámetros] indicados, debe llamarse a ->get() para obtener el
          resultado
		[Parámetros]:
		params: Array con los datos a buscar, Ejemplo: ['province' => 'Madrid', 'telephone' => '91123456']
		companyId: Identificador de la empresa con la que trabajar, obligatorio en algunas entidades (aquellas que guardan una relacción con una empresa determinada
                en anfix)
	####El método no estático get():
	El método ->get([array $fields = Array()] [, $maxRows = null] [, $minRowNumber = null] [, array $order = Array()] [, $orderTypes = 'ASC'] [, $path = 'search']) genera una búsqueda de entidades, combinado con where podremos definir unos filtros
        y después ejecutar la búsqueda
		[Parámetros]:
		fields: Array que contendrá los campos que deseamos obtener, todos si se indica un array vacío
		maxRows: Número máximo de resultados a retornar
		minRowNumber: Primera entrada a devolver como resultado del conjunto total de entradas devueltas.
		order: Lista con los campos por los que se quiere ordenar los resultados
        orderTypes: Tipo de ordenación ”ASC” o ”DESC”
		path: Método anfix al que llamar remotamente (última parte del punto de acceso anfix), por defecto suele ser search pero puede especificarse otro
		
	###Operaciones con entidades	
	Todos los métodos de búsquedas devolverán un array de entidades o una entidad, a continuación se describen las operaciones que podemos realizar con cada una de estas
        entidades
	->save() Guarda la entidad en anfix, el sistema sabrá si debe crear o actualizar en cada momento en función de si existe o no un id para la entidad
	->delete() Borra una entidad en anfix
	->update(array $params [, $path = 'update']) Actualiza la entidad con los datos indicados indicados en params. (Se recomienda utilizar save() en lugar de update())
	El método estático ::destroy($id [, $companyId = null] [, $path = 'delete']) permite destruir una entidad de la que conocemos su id
		[Parámetros]:
		id: Identificador de la entidad a destruir
		companyId: Identificador de la empresa con la que trabajar, obligatorio en algunas entidades (aquellas que guardan una relacción con una empresa determinada
                en anfix)
		path: Método anfix al que llamar remotamente (última parte del punto de acceso anfix), por defecto suele ser delete pero puede especificarse otro
		
##Otras utilidades
    Algunas entidades disponen de métodos extra, consulte la documentación para conocer dichos métodos y su uso
    También existen entidades que no implementan ninguno de los métodos básicos, consulte la documentación para conocerlas
		
#Ejemplos de uso
	Ejemplo de obtención de todas las empresas disponibles para la cuenta por defecto:
		$myEnterprises = Anfix\Company::all();
		
	Ejemplo de obtención de todas las empresas disponibles para una cuenta determinada:
		Anfix\Anfix::env(['otro_token','clave_otro_token']); //Selección de entorno
		$myEnterprises = Anfix\Company::all();	

	Ejemplo de obtención y modificación de una factura:
		$myInvoice = Anfix\SuppliedInvoice::first(['id' => 'invoice_id'],'enterprise_id'); //Obtención de la factura con id invoice_id
		$myInvoice->name = 'new_name'; //Modificación del nombre
		$myInvoice->save(); //Actualización de la factura

	Ejemplo de borrado de una factura:
		$myInvoice = Anfix\SuppliedInvoice::destroy('invoice_id' ,'enterprise_id'); //Eliminación de la factura con id invoice_id
		
	Ejemplo de obtención y borrado de una factura:
		$myInvoice = Anfix\SuppliedInvoice::first(['SuppliedInvoiceId' => 'invoice_id'],'enterprise_id'); //Obtención de la factura con id invoice_id
		$myInvoice->delete(); //Eliminación de la factura obtenida
		
	Ejemplo de obtención de búsqueda de clientes:
		$customers = Anfix\Customer::where(['CustomerName' => 'Pedro'])->get();
		
	Ejemplo de obtención de búsqueda de hasta 10 clientes:
		$customers = Anfix\Customer::where(['CustomerName' => 'Pedro'])->get([],10);