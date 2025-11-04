<?php

namespace core\entities;

class Wh {
	
	public $field = null;
	public $value = null;
	public $sign = null;
	public $zero = false;
	public $escape = null;
	public $operator = null;
	public $start_operator = null;

    /**
     * Конструктор
     * @param string $field
     * @param mixed $value
     * @param string $sign
     * @param string $operator
     * @param bool $zero
     * @param string $escape
     * @param int $start_operator
     */
	public function __construct($field, $value, $sign = '=', $operator = 'AND', $zero = false, $escape = '`', $start_operator = 1) {
	
		$this->field = $field;
		$this->value = $value;
		$this->sign = $sign;
		$this->zero = $zero;
		$this->escape = $escape;
		$this->operator = $operator;
		$this->start_operator = $start_operator;
		
	}
	
}