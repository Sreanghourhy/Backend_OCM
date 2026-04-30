<?php

namespace App\Utils;

class Helper {

	public const KhmerMonths = [1=>"មករា",2=>"កុម្ភៈ",3=>"មីនា",4=>"មេសា",5=>"ឧសភា",6=>"មិថុនា",7=>"កក្កដា",8=>"សីហា",9=>"កញ្ញា",10=>"តុលា",11=>"វិច្ឆិកា",12=>"ធ្នូ"];

	public static function toKnumber($text){
		return str_replace(array("0","1","2","3","4","5","6","7","8","9"),array("០","១","២","៣","៤","៥","៦","៧","៨","៩"),$text);
	}
	public static function toLnumber($text){
		return str_replace(array("០","១","២","៣","៤","៥","៦","៧","៨","៩"),array("0","1","2","3","4","5","6","7","8","9"),$text);
	}
	public static function toKdate($string_date,$full=false){
		if($string_date==''||$string_date==null)return 'Empty Date';
		$date = '' ;
		$days = array("Mon"=>"ច័ន្ទ","Tue"=>"អង្គារ៍","Wed"=>"ពុធ","Thu"=>"ព្រហស្បត៍","Fri"=>"សុក្រ","Sat"=>"សៅរ៍","Sun"=>"អាទិត្យ");
		$months = array("01"=>"មករា","02"=>"កុម្ភៈ","03"=>"មីនា","04"=>"មេសា","05"=>"ឧសភា","06"=>"មិថុនា","07"=>"កក្កដា","08"=>"សីហា","09"=>"កញ្ញា","10"=>"តុលា","11"=>"វិច្ឆិកា","12"=>"ធ្នូ");
		list($dayName,$dayNumber,$month,$year)=explode("-",date("D-d-m-Y",microtime($string_date)));
		if(!$full){
			$date = Helper::toKnumber($dayNumber) . " " . $months[$month] . " ". Helper::toKnumber($year);
		}else{
			list($dayName,$dayNumber,$month,$year)=explode("-",date("D-d-m-Y",microtime($string_date)));
			$date = "ថ្ងៃ ".$days[$dayName]. " ទី " . Helper::toKnumber($dayNumber) . " ខែ " . $months[$month] . " ឆ្នាំ ". Helper::toKnumber($year);
		}
		return $date ;
	}
	public static function toKdatetime($string_date,$full=false){
		if($string_date==''||$string_date==null)return 'Empty Date';
		$date = '' ;
		$days = array("Mon"=>"ច័ន្ទ","Tue"=>"អង្គារ៍","Wed"=>"ពុធ","Thu"=>"ព្រហស្បត៍","Fri"=>"សុក្រ","Sat"=>"សៅរ៍","Sun"=>"អាទិត្យ");
		$months = array("01"=>"មករា","02"=>"កុម្ភៈ","03"=>"មីនា","04"=>"មេសា","05"=>"ឧសភា","06"=>"មិថុនា","07"=>"កក្កដា","08"=>"សីហា","09"=>"កញ្ញា","10"=>"តុលា","11"=>"វិច្ឆិកា","12"=>"ធ្នូ");

		list($string_date,$string_time) = explode(' ',  $string_date);

		if(!$full){
			$date = Helper::toKnumber($string_date) . " " . Helper::toKnumber($string_time);
		}else{
			list($dayNumber,$month,$year)=explode("-",$string_date);
			$date = Helper::toKnumber($dayNumber) . " " . $months[$month] . " ". Helper::toKnumber($year) . ' ម៉ោង ' . Helper::toKnumber($string_time);
		}
		return $date ;
	}
	// public static function toKdatetime($string_date,$full=false){
	// 	if($string_date==''||$string_date==null)return 'Empty Date';
	// 	$date = '' ;
	// 	$days = array("Mon"=>"ច័ន្ទ","Tue"=>"អង្គារ៍","Wed"=>"ពុធ","Thu"=>"ព្រហស្បត៍","Fri"=>"សុក្រ","Sat"=>"សៅរ៍","Sun"=>"អាទិត្យ");
	// 	$months = array("01"=>"មករា","02"=>"កុម្ភៈ","03"=>"មីនា","04"=>"មេសា","05"=>"ឧសភា","06"=>"មិថុនា","07"=>"កក្កដា","08"=>"សីហា","09"=>"កញ្ញា","10"=>"តុលា","11"=>"វិច្ឆិកា","12"=>"ធ្នូ");

	// 	list($string_date,$string_time) = explode(' ',  $string_date);

	// 	if(!$full){
	// 		$date = Helper::toKnumber($string_date) . " " . Helper::toKnumber($string_time);
	// 	}else{
	// 		list($dayNumber,$month,$year)=explode("-",$string_date);
	// 		$date = Helper::toKnumber($dayNumber) . " " . $months[$month] . " ". Helper::toKnumber($year) . ' ម៉ោង ' . Helper::toKnumber($string_time);
	// 	}
	// 	return $date ;
	// }

	public static function getMonth($month)
	{
		$months = [1=>"មករា",2=>"កុម្ភៈ",3=>"មីនា",4=>"មេសា",5=>"ឧសភា",6=>"មិថុនា",7=>"កក្កដា",8=>"សីហា",9=>"កញ្ញា",10=>"តុលា",11=>"វិច្ឆិកា",12=>"ធ្នូ"];
		return array_key_exists( $month, $months)?$months[$month]:false;
	}

	public static function getDay($day)
	{
		$days = [0=>"អាទិត្យ",1=>"ច័ន្ទ",2=>"អង្គារ៍",3=>"ពុធ",4=>"ព្រហស្បត៍",5=>"សុក្រ",6=>"សៅរ៍"];
		return array_key_exists( $day, $days)?$days[$day]:false;
	}
	public static function validateDate($date, $format = 'Y-m-d')
	{
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	public static function getFileErrorMessage($fileError){
		// Check $_FILES['upfile']['error'] value.
		switch ($fileError) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				return 'No file sent.';
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'Exceeded filesize limit.';
			default:
				return 'Unknown errors.';
		}
	}
}
