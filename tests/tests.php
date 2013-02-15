<?php
include('../Query/Query.php');
include('../Query/Join.php');
include('../Query/Select.php');

use Query\Query;

$Select = Query::Select(['ola', 'adios'])
	->from('saludos')
	->leftJoin('saludos', 'olas')
	->limit(10)
	->where('saludos.ola = mola');

$Select2 = Query::Select(['ola', 'adios'])->from('saludos');

$Select->union($Select2);


echo '<pre>';
echo $Select;
