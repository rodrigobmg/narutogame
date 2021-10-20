<?php
	class MiniPlayer { // Port for class Player
		public $HP = 0;
		public $SP = 0;
		public $STA = 0;
		
		public $MAX_HP = 0;
		public $MAX_SP = 0;
		public $MAX_STA = 0;
		
		public $RYOU = 0;
	
		function __construct($id) {
			$rPlayer = Recordset::query($this->getPlayerView() . " id=" . $id)->row_array();
			
			$this->RYOU = $rPlayer['ryou'];
		}
		
		function getPlayerView() {
			return 	"
				SELECT
					a.id,
					a.less_hp,
					a.less_sp,
					a.less_sta,
					ryou
				
				FROM
					player a
				
				WHERE
			";
		}
	}
