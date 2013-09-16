<?php 
/*
*****************************************************************************************************
*****************************************************************************************************
	File:	MAIN.PHP
	Version:	0.1;
	Date:		Sep 2013;
	Modified:	null;
*****************************************************************************************************
*****************************************************************************************************
*/

/*
	Este archivo se encarga de tomar los datos enviados por el usuario, para poder identificar la 
	accion a realizar, tambien es el encargado de devolver los datos ya procesados al usuario, en
	un formato JSON.
	Si no se pide ninguna accion se muestra la documentacion de la aplicacion.

*/

header('Content-type: application/json');	//	Le decimos al navegador que todos los datos que imprimiremos corresponden a una estructura JSON
header('Access-Control-Allow-Origin: *');	//	Estos datos pueden ser obtenidos desde cualquier servidor.

$fail	=	true;	//	Reservado para errores

require_once('engine/karnel.php'); // Se carga el archivo con las class que maneja la db del sitio.

// Inicio del Sitio.
// Se declara la class.
$web = new Web();

// Cargado de base de datos.
$web->load();



/*
Se usara la variable 'ver' para detectar que es lo que desea realizar el usuario.
Opciones:
$_GET['ver']	=	variable tomada desde la url.
$_POST['ver']	=	variable tomada desde un formulario.
*/






/*
Si el usuario desea ver un listado de las categorias.
Se toma la variable ver desde la url.
Devuelve un JSON con el listado de categorias.

[
{
	"id":	"string id de categoria",
	"name":	"string nombre de categoria"
]
*/

//	Inicio de comparacion
if($_GET['ver'] == 'cat')			//	Comparacion de la variable ver con 'cat'
{
	echo($web->ListCategorys());	//	Mostrando listado, mediante la funcion $web->ListCategorys().
	$fail = false;					//	No hubo errores.
}
//	Fin del mostrado de categorias.














/*
Si el usuario  necesita un listado de los articulos de la web.
Se toma la variable ver desde la url
Devuelve un JSON con todos los avisos (ULTIMOS 10).
Esto se puede editar desde las variables $ini $fin y $orden

[{
	"id"		:	"string id del aviso",
	"category"	:	"int id de categoria del aviso",
	"titulo"	:	"string titulo del aviso",
	"contenido"	:	"string largo del contenido del aviso",
	"img"		:	int con la cantidad de imagenes del aviso (max 4),
	"imguno"	:	"string con la direccion de la imagen 1 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgdos"	:	"string con la direccion de la imagen 2 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgtres"	:	"string con la direccion de la imagen 3 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgcuatro"	:	"string con la direccion de la imagen 4 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"fecha"		:	"int con la fecha de creacion del aviso",
	"visto"		:	"string indicador de aviso publicado, 1 visible, 0 invisible"
}]

*/


//	Inicio de la comparacion.
if($_GET['ver'] == 'avisos')	//	Comparacion de la variable 'ver' con 'avisos'.
{
	/*	
		Esta funcion tambien nos permite ver el listado de avisos de una determinada 
		categoria, asi que tambien le vamos a pasar la variable se el usuario
		necesita ver solamente los avisos de una sola categoria.
	*/
	
	$idaviso	=	@$_GET['cat'];		//	Tomamos la variable 'cat' de la url, no le damos importancia si esta no existe.
	$ini		=	@$_GET['ini'];		//	Tomamos la variable 'ini' de la url, no le damos importancia si esta no existe.
	$fin		=	@$_GET['fin'];		//	Tomamos la variable 'fin' de la url, no le damos importancia si esta no existe.
	$orden		=	@$_GET['orden'];	//	Tomamos la variable 'orden' de la url, no le damos importancia si esta no existe.
	
	echo($web->LoadClasificados($idaviso,$ini,$fin,$orden));	//	Mostramos el listado de avisos, indicando si es que se quiere ver una sola categoria. $web->LoadClasificados(categoria).
	$fail = false;					//	No hubo errores.
}
//	Fin del mostrado de avisos.









/*
Si el usuario quiere insertar un nuevo aviso.
Como tambien se pueden cargar imagenes, aca los datos son enviados atravez
de un formulario, entonces tomamos los datos via $_POST.
Entonces si el usuario envia la variable 'ver' con 'nuevo'
Se toman los arrays $_POST y $_FILES completos.
Se devuelve un JSON con los datos de el aviso nuevo en el caso de que sea insertado correctamente
o con un false en el caso de que no.

[{
	"id"		:		"string id del aviso.",
	"pwd"		:		"string codigo de seguridad del aviso.",
	"titulo"	:		"string titulo del aviso",
	"texto"		:		"string largo con el contenido del aviso",
	"cat"		:		"int con el id de la categoria a la que pertenece el aviso",
	"date"		:		"int fecha de creacion del aviso",
	"err"		:		false,
	"imgs"		:		"int cantidad de imagenes cargadas (MAX 4)",
	"imglink"	:		"array datos de las imagenes cargadas, en el caso que las halla.",
	"imguno"	:		"string con la direccion de la imagen 1 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgdos"	:		"string con la direccion de la imagen 2 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgtres"	:		"string con la direccion de la imagen 3 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgcuatro"	:		"string con la direccion de la imagen 4 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
}]
*/
	
	
//	Inicio de la comparacion
if($_POST['ver'] == 'nuevo')	// Tomamos la variable 'ver' mediante POST y la comparamos con 'nuevo'
{
	echo($web->publicar($_POST,$_FILES));	//	Procesamos todos los datos $_POST y $_FILES mediante la funcion $web->publicar($_POST,$_FILES).
	$fail = false;					//	No hubo errores.
}
//	Fin de la comparacion












/*
Si el usuario necesita ver los datos de un aviso en particular
Se toma la variable 'ver' desde la url y se la compara con 'aviso'
Tambien se tiene que tomar la variable 'id', que es el string id del aviso a mostrar.
Si no se incluye la variable id, la funcion no devolvera ningun dato.
Devuelve un JSON:
[{
	"id"		:	"string id del aviso",
	"category"	:	"int id de categoria del aviso",
	"titulo"	:	"string titulo del aviso",
	"contenido"	:	"string largo del contenido del aviso",
	"img"		:	int con la cantidad de imagenes del aviso (max 4),
	"imguno"	:	"string con la direccion de la imagen 1 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgdos"	:	"string con la direccion de la imagen 2 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgtres"	:	"string con la direccion de la imagen 3 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"imgcuatro"	:	"string con la direccion de la imagen 4 del aviso, o solo la direccion del servidor de imagenes si no existe la misma",
	"fecha"		:	"int con la fecha de creacion del aviso",
	"visto"		:	"string indicador de aviso publicado, 1 visible, 0 invisible"
}]
*/

//	Inicio de comparacion
if($_GET['ver'] == 'aviso')	//	Comparacion de variable 'ver' con 'aviso'
{
	$idaviso = @$_GET['id'];			//	Tomamos la variable 'id', pero no le damos importancia.
	echo($web->Articulo($idaviso));		//	Mostramos los datos del aviso mediante la funcion $web->Articulo($idaviso)
	$fail = false;					//	No hubo errores.
}
//	Fin de comparacion





/*
Si el usuario quiere borrar un articulo.
Se toma la variable 'ver' desde la url y se la compara con 'DelArt', tambien se toma la variable 'secret' desde la url.
Devuelve true en el caso de que se complete sin errores la operacion o false en el caso de que se produzca un error, ejemplo si el codigo de seguridad no corrresponde. 
IMPORTANTE: los articulos nunca seran borrados de la base de datos, solo no seran mostrados en el listado publico.
*/

//	Inicio de la comparacion
if($_GET['ver'] == 'DelArt')	//	Comparacion de la variable 'ver' con 'DelArt'
{
	$id = @$_GET['id'];					//	Tomamos la variable id desde la url, esta corresponde al id del articulo a borrar. No le damos importancia si esta falta.
	$secret = @$_GET['secret'];			//	Tomamos la variable secret desde la url, esta corrasponde al codigo secreto del articulo a borrar. No le damos importancia si esta falta.
	echo($web->DelArt($id,$secret));	//	Procesamos los datos con la funcion $web->DelArt($id,$secret).
	$fail = false;					//	No hubo errores.
}
// Fin de la comparacion











/*
Si el usuario quiere publicar un determinado aviso, esto es por que por defecto cuando se inserta un nuevo aviso en la base de datos
este no esta publicado, o tambien para cuando se quiere republicar un aviso anteriormente borrado.

Se toma la variable 'ver' desde la url, para la comparacion.
Se toma la variable 'id' desde la url, id del aviso a publicar.
Se toma la variable 'secret' desde la url, es el codigo secreto del aviso a publicar.

Retorno:
	
	TRUE	:	en el caso de que la operacion se complete con exito.
	
	FALSE	:	en el caso de que existan errores en el trasncurso de la operacion.

*/

//	Inicio de la comparacion
if($_GET['ver'] == 'Publicar')	// Se compara la variable 'ver' con 'Publicar'
{
	$id = @$_GET['id'];						//	Se toma la variable 'id' desde la url, esta corresponde al id del articulo a publicar. No le damos importancia si esta falta.
	$secret = @$_GET['secret'];				//	Se toma la variable 'secret' desde la url, esta corresponde al codigo secreto del articulo a publicar. No le damos importancia si esta falta.
	echo($web->Mostrar($id,$secret));		//	Procesamos los datos con la funcion $web->Mostrar($id,$secret)
	$fail = false;					//	No hubo errores.
}



/*
	Redirigir en caso de que el usuario no sepa usar la apicacion.
*/
if($fail==true)
{
		header( "Status: 301 Moved Permanently", false, 301);
		header("Location: http://docs.161090.com.ar/");
		exit();  
}
