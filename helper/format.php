<?php 


	class Format{
		public static function formatDate($date){
			return date('F j, Y, g:i a',strtotime($date));
		}
		public static function textShorten($text, $limit = 100){
			if (strlen($text) <= $limit) {
				return $text;
			}
			return substr($text, 0, $limit) . '...';
		}
		public static function validation($data){
			$data = trim($data);
			$data = stripcslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		public static function title(){
			$path = $_SERVER['SCRIPT_FILENAME'];
			$title = basename($path, '.php');
			if ($title == 'index') {
				$title = 'home';
			}elseif ($title == 'contact') {
				$title = 'contact';
			}
			return ucfirst($title);
		}
	}

?>