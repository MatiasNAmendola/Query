<?php
namespace Query;

class Select {
	protected $select = array();
	protected $from = array();
	protected $joins = array();
	protected $unions = array();
	protected $where = array();
	protected $groupBy = array();
	protected $orderBy = array();
	protected $offset = null;
	protected $limit = null;

	public function __construct ($fields = null) {
		if ($fields) {
			$this->select($fields);
		}
	}

	public function __toString () {
		return $this->toString();
	}

	public function __clone () {
		foreach ($this->joins as $reference => $joins) {
			foreach ($joins as $k => $join) {
				$this->joins[$reference][$k] = clone $join;
			}
		}
	}

	public function select ($fields) {
		$this->select = array_merge($this->select, (array)$fields);

		return $this;
	}

	public function from ($table) {
		$this->from = array_merge($this->from, (array)$table);

		return $this;
	}

	public function where ($conditions) {
		$this->where = array_merge($this->where, (array)$conditions);

		return $this;
	}

	public function join ($type, $reference, $join, $on = null) {
		if (!isset($this->joins[$reference])) {
			$this->joins[$reference] = [];
		}

		$this->joins[$reference][] = new Join($type, $join, $on);

		return $this;
	}

	public function leftJoin ($reference, $table, $on = null) {
		return $this->join('LEFT', $reference, $table, $on);
	}

	public function innerJoin ($reference, $table, $on = null) {
		return $this->join('INNER', $reference, $table, $on);
	}

	public function union ($query) {
		$this->unions[] = ['', (string)$query];

		return $this;
	}

	public function unionAll ($query) {
		$this->unions[] = ['ALL', (string)$query];

		return $this;
	}

	public function groupBy ($fields) {
		$this->groupBy = array_merge($this->groupBy, (array)$fields);

		return $this;
	}

	public function orderBy ($fields) {
		$this->orderBy = array_merge($this->orderBy, (array)$fields);

		return $this;
	}

	public function limit ($limit) {
		$this->limit = $limit;

		return $this;
	}

	public function offset ($offset) {
		$this->offset = $offset;

		return $this;
	}

	public function toString () {
		$query = 'SELECT '.implode(', ', $this->select);

		if ($this->joins) {
			$query .= ' FROM ';

			foreach ($this->from as $table) {
				if (empty($this->joins[$table])) {
					$query .= $table.', ';
				} else {
					$query .= $table;

					foreach ($this->joins[$table] as $join) {
						$query .= ' '.(string)$join;
					}

					$query .= ', ';
				}
			}

			$query = substr($query, 0, -2);

		} else {
			$query .= ' FROM '.implode(', ', $this->from);
		}

		if (!empty($this->where)) {
			$query .= ' WHERE ('.implode(' AND ', $this->where).')';
		}

		if (!empty($this->unions)) {
			foreach ($this->unions as $union) {
				$query .= (($union[0] === 'ALL') ? ' UNION ALL ' : ' UNION ').$union[1];
			}
		}

		if (!empty($this->groupBy)) {
			$query .= ' GROUP BY '.implode(', ', $this->groupBy);
		}

		if (!empty($this->orderBy)) {
			$query .= ' ORDER BY '.implode(', ', $this->orderBy);
		}

		if (isset($this->limit)) {
			if (isset($this->offset)) {
				$query .= ' LIMIT '.$this->offset.', '.$this->limit;
			} else {
				$query .= ' LIMIT '.$this->limit;
			}
		}

		return $query;
	}
}