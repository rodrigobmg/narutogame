<?php
	if(!isset($_SERVER['HTTP_REFERER']) || (isset($_SERVER['HTTP_REFERER']) && !$_SERVER['HTTP_REFERER'])) {
		die(); 
	}
	
	/*
	$hasEncoding = false;

	if(!(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip") === false)) {
		$hasEncoding = true;
	}

	if(!(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate") === false)) {
		$hasEncoding = true;
	}

	if(!$hasEncoding) {//  && $_SERVER['HTTP_X_FORWARDED_FOR'] == "189.29.163.84"
		header("Content-Type: image/png");
		die(file_get_contents("images/captcha_zuado.png"));
	}
	*/
	
	$hasEncoding = true;

	if(!(stripos($_SERVER['HTTP_USER_AGENT'], "K-Meleon") === false)) {
		$hasEncoding = false;
	}

	if(!$hasEncoding) {
		header("Content-Type: image/png");
		die(file_get_contents("images/captcha_zuado.png"));
	}

	
	//header("Content-Type: image/jpeg");

	$img = new securimage();
	
	if(isset($_GET['s']) || isset($_GET['quick'])) {
		if(isset($_GET['quick'])) {
			$img->ssid = "securimage_code_value_quick";
		} else {
			$img->ssid = "securimage_code_value_login";			
		}

		$img->image_width = 50;
		$img->image_height = 17;
		
		$img->text_maximum_distance = $img->text_minimum_distance = 15;
		
		$img->draw_lines = false;
		$img->line_color = "#FFFFFF";
		$img->draw_lines_over_text = false;
		$img->arc_linethroug = false;
		$img->use_wordlist = false;
		$img->code_length = 3;
		$img->use_multi_text = false;
		$img->text_color = "#ec9e02";
		$img->ttf_file = "include/ttffonts/verdana.ttf";
		$img->use_transparent_text = true;
		$img->use_transparent_bg = true;
		$img->image_bg_color = "#4e2e1e";
		$img->font_size = "9";
	} elseif(isset($_GET['ss']) && $_GET['ss']) {
		switch($_GET['ss']) {
			case 1:
				$img->ssid = "securimage_recuperar_senha";
			
				break;	
				
			case 2:
				$img->ssid = "securimage_codigo_ninja";
			
				break;	
			
			default:
				$img->ssid = decode($_GET['ss']);	
		}
		

		$img->image_width = 120;
		$img->image_height = 35;
		
		$img->text_maximum_distance = $img->text_minimum_distance = 22;
		
		$img->draw_lines = true;
		$img->line_color = "#FFFFFF";
		$img->draw_lines_over_text = false;
		$img->arc_linethroug = false;
		$img->use_wordlist = false;
		$img->code_length = 5;
		$img->text_color = "#ec9e02";
		$img->ttf_file = "include/ttffonts/tahoma.ttf";
		$img->font_size = "16";
	} else {
		$img->image_width = 120;
		$img->image_height = 40;
		
		$img->text_maximum_distance = $img->text_minimum_distance = 22;
		
		$img->draw_lines = true;
		$img->line_color = "#FFFFFF";
		$img->draw_lines_over_text = false;
		$img->arc_linethroug = false;
		$img->use_wordlist = false;
		$img->code_length = 5;
		$img->ttf_file = "include/ttffonts/tahoma.ttf";
		$img->font_size = "16";
	}
		
	$img->show();
