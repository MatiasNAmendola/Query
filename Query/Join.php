<?php
namespace Query;

class Join {
	protected $type;
	protected $join = array();
	protected $on = array();

	public function __construct ($type = null, $join = null, $on = null) {
		if ($type !== null) {
			$this->type($type);
		}

		if ($join !== null) {
			$this->join($join);
		}

		if ($on !== null) {
			$this->on($on);
		}
	}

	public function __toString () {
		return $this->toString();
	}

	public function type ($type) {
		$this->type = $type;

		return $this;
	}

	public function join ($table) {
		$this->join = array_merge($this->join, (array)$table);

		return $this;
	}

	public function on ($conditions) {
		$this->on = array_merge($this->on, (array)$conditions);

		return $this;
	}

	public function toString () {
		$query = empty($this->type) ? 'JOIN ' : strtoupper($this->type).' JOIN ';
		$query .= implode(', ', $this->join);

		if ($this->on) {
			$query .= ' ON ('.implode(' AND ', $this->on).')';
		}

		return $query;
	}
}