#Instalación
    El fichero tokens_temp.php o en su defecto el que se indique en el fichero de configuración debe tener permiso de escritura
    Para el ejemplo get_tokens tambien se necesitará permiso de escritura en el fichero examples/tokens.php
    Deberá indicar en el fichero config.inc.php sus credenciales para la conexión a Anfix como partner, dichas credenciales le serán enviadas por Anfix
    La clave default_token del fichero de configuración le permitirá indicar el token y clave (cuenta anfix) a utilizar en caso de no indicarse ninguno 
    mediante Anfix\Anfix::env();
        	
##Obtención de un token
	El primer paso para poder conectar a una cuenta de Anfix será obtener un token de acceso, para esto puede observar el fichero /Examples/new_token.php
	donde se indica como realizar una solicitud/validación de token. La solicitud se basa en dos pasos bien diferenciados (Oauth1)
	El primer paso será realizar una llamada a Anfix::generateToken(string $identifier, string $returnUrl); que redirigirá a la página de login Anfix
		Parámetros:
		identifier: Identificador que sera devuelto junto con el token definitivo, su uso responde únicamente para poder diferenciar entre varias solicitudes
		returnUrl: Url PÚBLICA donde deberá encontrarse el script que recibirá y almacenará el token (paso2)

	El segundo paso será esperar que el usuario se loguee en Anfix y que la API Anfix realice una llamada a returnUrl con los datos de acceso
	Para esto crearemos un script que contendrá una llamada a Anfix::onGeneratedToken(callable $function); 
		Parámetros:
		function(identifier,token,secret): Closure que se ejecutará cuando se reciba un token válido, recibe los datos del token y se utiliza para almacenar el 
		token una vez el usuario le haya autorizado el acceso a su cuenta. Recibe el identificador indicado en el paso anterior, el token y la clave del mismo

	Nota importante: Tenga en cuenta que la solicitud de token se realiza en dos pasos, el primero es lanzar la solicitud generateToken que llevará
	al usuario a la página de anfix para loguearse. En cambio la segunda parte será una llamada desde anfix al script indicado en $returnUrl, tenga en
	cuenta que dicho script deberá ser accesible publicamente desde internet, si este se encuentra en una red privada o protegido por contraseña no 
	será posible finalizar el proceso con éxito, cuando Anfix realice dicha llamada a su url le entregará el token definitivo para la cuenta del 
	usuario que se logueó en el paso 1, la función closure indicada en onGeneratedToken recibirá el token y deberá almacenarlo para su uso posterior

###Invalidación de un token
    Si desea invalidar un token utilice la funcion Anfix\Anfix::ivalidateToken(token,secret)
        Parámetros:
        token: Token a invalidar
        secret: Secret del token a invalidar

##Obtención de un Modelo
	Para obtener un Modelo llamaremos a la siguiente función estática: Anfix::{_MODELO_}([array $token] [,string $config_path])
	Siendo {_MODELO_} cualquiera de las entidades existentes en el directorio /Entities (Enterprise;IssuedInvoice;SentInvoice;Customer;Supplier) 
		Parámetros:
		token (Opcional): array que contendrá ['token','secret'], para la cuenta anfix a la que deseamos acceder, si no es indicado se utilizará el 
			último valor especificado o en su defecto el valor indicado en el fichero de configuración bajo la clave default
		config_path (Opcional): Path del fichero de configuración, si no es indicado se utilizará el fichero config.inc.php incluido en la librería

###Operaciones con los Modelos
	Los Modelos son los objetos que nos permiten operar con las diferentes entidades, cada modelo devolverá una o varias entidades de su mismo
	tipo, existe un Modelo por cada Entidad, las operaciones que podemos realizar con los modelos son las siguientes:
	Búsquedas:
	Creación:
	Modificación:
	Borrado:

#Ejemplos de uso
	Ejemplo de obtención de todas las empresas disponibles:
		$EnterprisesModel = Anfix::Enterprises(); //Obtención del Modelo`
		$myEnterprises = $EnterprisesModel->all(); //Obtener todas las empresas (entidades)

	Ejemplo de obtención y modificación de una factura:
		$SInvoiceModel = Anfix::SuppliedInvoice(); //Obtención del Modelo
		$myInvoice = $SInvoiceModel->first(['id' => 'invoice_id'],'enterprise_id'); //Obtención de la factura con id invoice_id
		$myInvoice->name = 'new_name'; //Modificación del nombre
		$myInvoice->save(); //Actualización de la entidad

