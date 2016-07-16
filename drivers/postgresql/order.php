<?php
namespace Rt\Storage {
	class QueryOrder {
		protected $_query;
		
		protected function checkAgregate($agregate)
		{
			$agr = NULL;
			switch($agregate) {
				case "MIN":
					$agr = "MIN";
					break;
				case "MAX":
					$agr = "MAX";
					break;
				case "AVG":
					$agr = "AVG";
					break;
			}
			return $agr;
		}
		
		public function __construct($val, $order = true, $agregate = NULL)
		{
			$agr = $this->checkAgregate($agregate);
			if($agr != NULL)
				$rval = $agr."(".$val.")";
			else
				$rval = $val;
			$this->_query = " ".$rval." ".($order ? "ASC" : "DESC");
		}
		
		public function &order($val, $order = true, $agregate = NULL)
		{
			$agr = $this->checkAgregate($agregate);
			if($agr != NULL)
				$rval = $agr."(".$val.")";
			else
				$rval = $val;
			$this->_query .= ", ".$rval." ".($order ? "ASC" : "DESC");
			return $this;
		}
		
		public function getQuery()
		{
			return $this->_query;
		}
	}
}
?>