<?php
	class Vila {
		private static $reqs	= array();
		
		public static function hasRequirements($vila, $item) {
			$ok			= true;
			Vila::$reqs	= array();

			$style_s_ok = "<li><span style='text-decoration: line-through'>";
			$style_e_ok = "</span></li>";

			$style_s_no = "<li><span style='color: #F00'>";
			$style_e_no = "</span></li>";

			if(!is_array($vila)) {
				$vila	= Recordset::query('SELECT * FROM vila WHERE id=' . $vila)->row_array();
			}
			
			if(!is_array($item)) {
				$item	= Recordset::query('SELECT * FROM item WHERE id=' . $item)->row_array();
			}

			$items		= Recordset::query('
				SELECT 
					SUM(CASE WHEN ordem=1 THEN 1 ELSE 0 END) AS item1,
					SUM(CASE WHEN ordem=2 THEN 1 ELSE 0 END) AS item2,
					SUM(CASE WHEN ordem=3 THEN 1 ELSE 0 END) AS item3,
					SUM(CASE WHEN ordem=4 THEN 1 ELSE 0 END) AS item4,
					SUM(CASE WHEN ordem=5 THEN 1 ELSE 0 END) AS item5
					
				FROM
					vila_item a JOIN item b ON b.id=a.item_id
					
				WHERE
					a.vila_id=' . $vila['id'])->row_array();
			
			$msg	= t('vila.v10');

			if($vila['nivel_ok']) {
				Vila::$reqs[]	= $style_s_ok . $msg . $style_e_ok;
			} else {
				Vila::$reqs[]	= $style_s_no . $msg . $style_e_no;
				$ok				= false;
			}
			
			if($item['req_level']) {
				$msg	= t('vila.v11') . $item['req_level'];
				
				if($vila['nivel_vila'] >= $item['req_level']) {
					Vila::$reqs[]	= $style_s_ok . $msg . $style_e_ok;
				} else {
					Vila::$reqs[]	= $style_s_no . $msg . $style_e_no;
					$ok				= false;
				}
			}

			if($item['req_item']) {
				$msg	= t('classes.c10') . ': ' . Recordset::query('SELECT nome_' . Locale::get() . ' FROM item WHERE id=' . $item['req_item'])->row()->{'nome_' . Locale::get()};
				
				if(Recordset::query('SELECT id FROM vila_item WHERE vila_id=' . $vila['id'] . ' AND item_id=' . $item['req_item'])->num_rows) {
					Vila::$reqs[]	= $style_s_ok . $msg . $style_e_ok;
				} else {
					Vila::$reqs[]	= $style_s_no . $msg . $style_e_no;
					$ok				= false;
				}
			}

			if($item['req_tai']) {
				$msg	= t('vila.v16') .' '. $item['req_tai'] .' '. t('vila.v12');
				
				if($items['item1'] >= $item['req_tai']) {
					Vila::$reqs[]	= $style_s_ok . $msg . $style_e_ok;
				} else {
					Vila::$reqs[]	= $style_s_no . $msg . $style_e_no;
					$ok				= false;
				}
			}

			if($item['req_nin']) {
				$msg	=  t('vila.v16') .' '. $item['req_nin'] .' '.  t('vila.v13');
				
				if($items['item2'] >= $item['req_nin']) {
					Vila::$reqs[]	= $style_s_ok . $msg . $style_e_ok;
				} else {
					Vila::$reqs[]	= $style_s_no . $msg . $style_e_no;
					$ok				= false;
				}
			}

			if($item['req_gen']) {
				$msg	=  t('vila.v16') .' '. $item['req_gen'] .' '.  t('vila.v14');
				
				if($items['item3'] >= $item['req_gen']) {
					Vila::$reqs[]	= $style_s_ok . $msg . $style_e_ok;
				} else {
					Vila::$reqs[]	= $style_s_no . $msg . $style_e_no;
					$ok				= false;
				}
			}

			if($item['req_agi']) {
				$msg	=  t('vila.v16') .' '. $item['req_agi'] .' '.  t('vila.v15');
				
				if($items['item4'] >= $item['req_agi']) {
					Vila::$reqs[]	= $style_s_ok . $msg . $style_e_ok;
				} else {
					Vila::$reqs[]	= $style_s_no . $msg . $style_e_no;
					$ok				= false;
				}
			}
			
			return $ok;
		}
		
		public static function getRequirementLog() {
			return Vila::$reqs;	
		}
	}