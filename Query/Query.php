<?php
namespace Query;

class Query {
	static public function select ($fields) {
		return new Select($fields);
	}
}