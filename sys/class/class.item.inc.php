<?php
	class Item{
		public $id;

		public $name;

		public $volume;

		public $time;

		public $desc;

		public $location;

		public function __construct($item=NULL){
			if(is_array($item)){
				$this->id = $item['item_id'];
				$this->name = $item['item_name'];
				$this->volume = $item['item_volume'];
				$this->time = $item['item_time'];
				$this->desc = $item['item_desc'];
				$this->location = $item['item_location'];
			}
			else{
				$this->id = NULL;
				$this->name = 	NULL;
				$this->volume = NULL;
				$this->time = NULL;
				$this->desc = NULL;
				$this->location = NULL;

			}
		}


	}
?>
