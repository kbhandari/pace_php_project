<?php

	abstract class MyEnum
	{
		final public function __construct($value)
		{
			$class = new ReflectionClass($this);
			$constants = $class->getConstants();
			if(!in_array($value, $constants)) {
				throw IllegalArgumentException();
			}
			$this->value = $value;
			$this->name = array_search($this->value, $constants);
		}

		final public function __toString()
		{
			return (string)$this->value;
		}

		public function getName() {
			return $this->name;
		}
	}

  class UserRoles extends MyEnum {
	  const ADMIN = 1;
	  const USER = 2;
  }

?>