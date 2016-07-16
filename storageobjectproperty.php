<?php
namespace Rt\Storage {
	class StorageObjectProperty {
		public function __construct()
		{

		}

		public function name() : string
		{
			return $this->_name;
		}

		public function value() : string
		{
			return $this->_value;
		}

		public function query() : string
		{
			return $this->_query;
		}


		private string $_name;
		private string $_value;
		private string $_query;
	}
}
?>