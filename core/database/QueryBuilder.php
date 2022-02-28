<?php

namespace app\core\database;

use app\core\Application;
use JetBrains\PhpStorm\Pure;

//Database Query Builder
class QueryBuilder
{
    private string $from;
    private array $columns;
    private bool $distinct = false;
    private array $joins;
    private array $wheres;
    private array $groups;
    private array $havings;
    private array $orders;
    private int $limit;
    private int $offset;
    private array $bindValue = [];
    private array $questionMarkValue = [];
    private bool $in = false;

    public function __construct($tableName)
    {
        $this->from = $tableName;
    }

    #[Pure] public static function table($tableName): QueryBuilder
    {
        return new self($tableName);
    }

    public function select($columns = ['*']): static
    {
        $this->columns = is_array ($columns) ? $columns : func_get_args ();
        return $this;
    }

    public function distinct(): static
    {
        $this->distinct = true;
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = 'inner'): static
    {
        $this->joins[] = [$table, $first, $operator, $second, $type];
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second): static
    {
        return $this->join ($table, $first, $operator, $second, 'left');
    }

    public function rightJoin($table, $first, $operator, $second): static
    {
        return $this->join ($table, $first, $operator, $second, 'right');
    }

    public function where($column, $operator, $value, $concatOperator = 'and'): static
    {
        if ($operator === strtolower ('in')) {
            $this->in = true;
        }
        $this->wheres[] = [$concatOperator, $column, $operator, $value];
        return $this;
    }

    public function orWhere($columns, $operator, $value): static
    {
        if ($operator === strtolower ('in')) {
            $this->in = true;
        }
        return $this->where ($columns, $operator, $value, 'or');
    }

    public function groupBy($columns): static
    {
        $this->groups = is_array ($columns) ? $columns : func_get_args ();
        return $this;
    }

    public function having($columns, $operator, $value, $concatOperator = 'and'): static
    {
        $this->havings[] = [$columns, $operator, $value, $concatOperator];
        return $this;
    }

    public function orHaving($columns, $operator, $value): static
    {
        return $this->having ($columns, $operator, $value, 'or');
    }

    public function orderBy($columns, $direction = 'asc'): static
    {
        $this->orders[] = [$columns, $direction];
        return $this;
    }

    public function limit($limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    //================ Statement ===============
    public function get($firstRowCheck = false, $lastRowCheck = false)
    {
        if (empty($this->from)) {
            return false;
        }

        $sql = $this->distinct ? 'SELECT DISTINCT' : 'SELECT';


        if (isset($this->columns)) {
            $sql .= ' ' . implode (', ', $this->columns);
        } else {
            $sql .= ' *';
        }

        $sql .= ' FROM ' . $this->from;

        if (isset($this->joins)) {
            foreach ($this->joins as $join) {
                //[0] = table, [1] = first value, [2] = operator, [3] = second value, [4] = type join
                $sql .= match (strtolower ($join[4])) {
                    'left' => ' LEFT JOIN',
                    'right' => ' RIGHT JOIN',
                    default => ' INNER JOIN',
                };
                $sql .= " $join[0] ON $join[1] $join[2] $join[3]";
            }
        }

        if (isset($this->wheres)) {
            $sql .= ' WHERE';
            $sql .= $this->handleWhere ();
        }

        if (isset($this->groups)) {
            $sql .= ' GROUP BY' . implode (' ,', $this->groups);
        }

        if (isset($this->havings)) {
            $sql .= ' HAVING';
            foreach ($this->havings as $key => $having) {
                if ($key > 0) {
                    $sql .= (strtolower ($having[0]) === 'and') ? ' AND' : ' OR';
                }
                $sql .= " $having[1] $having[2] :$having[3]";
            }
        }

        if (isset($this->orders)) {
            $sql .= ' ORDER BY';
            foreach ($this->orders as $key => $order) {
                //[0] = columns, [1] = direction
                if ($key > 0) {
                    $sql .= ', ';
                }
                $sql .= " $order[0] " . strtoupper ($order[1]);
            }
        }
        if ($firstRowCheck == true) {
            $sql .= " LIMIT 1";
        } else if (isset($this->limit)) {
            $sql .= " LIMIT $this->limit";
        }

        if (isset($this->offset)) {
            $sql .= " OFFSET $this->offset";
        }
        try {
            $stmt = self::prepare ($sql);
            if ($this->in === true) {
                if ($stmt->execute ($this->questionMarkValue)) {
                    if ($firstRowCheck == true) {
                        return $stmt->fetch ();
                    }
                    return $stmt->fetchAll ();
                } else {
                    return false;
                }
            } else {
                if ($stmt->execute ($this->bindValue)) {
                    if ($firstRowCheck == true) {
                        return $stmt->fetch ();
                    }
                    return $stmt->fetchAll ();
                } else {
                    return false;
                }
            }
        } catch (\Exception $e) {
            die($e);
        }
    }

    public function first($first = true, $last = false)
    {
        return $this->get ($first, $last);
    }

    public function lastInsert()
    {
        return $this->first (false, true);
    }

    public function insert(array $columnsAndValue)
    {
        if (empty($this->from)) {
            return false;
        }

        $sql = "INSERT INTO $this->from";

        $key = array_keys ($columnsAndValue);
        $sql .= ' (' . implode (', ', $key) . ') VALUES (' . implode (', ', array_map (fn($attr) => ":$attr", $key)) . ')';

        try {
            $stmt = self::prepare ($sql);
            $stmt->execute ($columnsAndValue);
            return true;
        } catch (\Exception $e) {
            die($e);
        }
    }

    public function update(array $columnsAndValue): bool
    {
        if (empty($this->from)) {
            return false;
        }
        $key = array_keys ($columnsAndValue);
        $sql = "UPDATE $this->from SET";
        foreach ($key as $keyComma => $item) {
            if ($keyComma == 0) {
                $sql .= " $item = :$item";
            } else {
                $sql .= ", $item = :$item";
            }
        }

        if (isset($this->wheres)) {
            $sql .= ' WHERE';
            $sql .= $this->handleWhere ();
        } else {
            return false;
        }

        $columnsAndValue += $this->bindValue;

        try {
            $stmt = self::prepare ($sql);
            $stmt->execute ($columnsAndValue);
            return true;
        } catch (\Exception $e) {
            die($e);
        }
    }

    public function delete()
    {
        if (empty($this->from)) {
            return false;
        }
        $sql = "DELETE FROM $this->from WHERE";
        try {
            $sql .= $this->handleWhere ();
            $stmt = self::prepare ($sql);
            $stmt->execute ($this->bindValue);
            return true;
        } catch (\Exception $e) {
            die($e);
        }
    }

    public function handleWhere(): string
    {
        $sqlWhere = '';
        foreach ($this->wheres as $key => $where) {
            //[0] = concat operator, [1] = columns, [2] = operator, [3] = value
            $where[2] = strtoupper ($where[2]);
            if ($key > 0) {
                $sqlWhere .= (strtolower ($where[0]) === 'and') ? ' AND' : ' OR';
            }
            if ($where[2] === 'LIKE') {
                $currentBind = ["$where[1]OfWHERE" => "%$where[3]%"];
            } else if ($where[2] === '%LIKE') {
                $currentBind = ["$where[1]OfWHERE" => "%$where[3]"];
            } else if ($where[2] === 'LIKE%') {
                $currentBind = ["$where[1]OfWHERE" => "$where[3]%"];
            } else if (isset($this->joins)) {
                $currentBind = ["$where[1]" => $where['3']];
            } else {
                $currentBind = ["$where[1]OfWHERE" => $where[3]];
            }
            //add Where before named placeholder for not get same key error array when bind
            $this->bindValue += $currentBind;
            if ($where[2] === 'IN') {
                $sqlWhere .= " $where[1] " . $where[2] . '(' . implode (',', array_fill (0, count ($where[3]), '?')) . ')';
                $this->questionMarkValue = $where[3];
            } else if (isset($this->joins)) {
                $sqlWhere .= " $where[1] " . $where[2] . " :$where[1]";
            } else {
                $sqlWhere .= " $where[1] " . $where[2] . " :$where[1]OfWHERE";
            }
        }
        return $sqlWhere;
    }

    public function prepare($sql)
    {
        return Application::$app->db->pdo->prepare ($sql);
    }
}