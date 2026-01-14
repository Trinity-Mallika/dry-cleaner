<?php session_start();
include("config.php");

class DataOperation extends Database
{
	public function login_method($table, $username, $password)
	{
		$sql = "SELECT * from $table WHERE username='$username' AND password='$password' AND status='1'";
		$query = mysqli_query($this->con, $sql);
		$count = mysqli_num_rows($query);
		if ($count > 0) {
			//$_SESSION['usertype']="trinity";
			$row = mysqli_fetch_array($query);
			if ($row['usertype'] == 'Admin' || $row['usertype'] == 'admin') {
				$_SESSION['usertype'] = $row['usertype'];
				$_SESSION['userid'] = $row['userid'];
			}

			return $count;
		}
	}

	function shortCode($string)
	{
		$string = trim($string);
		if (strpos($string, ' ') === false) {
			return strtoupper(substr($string, 0, 3));
		}
		$words = preg_split('/\s+/', $string);
		$code = '';
		foreach ($words as $w) {
			$code .= strtoupper(substr($w, 0, 1));
		}
		return $code;
	}


	function software_expire()
	{

		$currentdate = date('Y-m-d');
		$cntrow = $this->getvalfield("software_expired", "count(*)", "'$currentdate' between start_date and expired_date");

		return $cntrow;
	}


	function uploadImage($imgpath, $docname)
	{



		if (1 == 1) {



			$doc_name = $docname['name'];

			$tm = "DOC";

			$tm .= microtime(true) * 1000;

			$ext = pathinfo($doc_name, PATHINFO_EXTENSION);

			$doc_name = $tm . "." . $ext;



			if (move_uploaded_file($docname['tmp_name'], $imgpath . "$doc_name")) {



				return ($doc_name);
			} else {

				return ("");
			}
		} else {

			return ("0");
		}
	}


	public function session_method($table, $username, $password)
	{
		$sql = "SELECT * from $table WHERE username='$username' AND password='$password'";
		$query = mysqli_query($this->con, $sql);
		$row = mysqli_fetch_array($query);
		return $row;
	}


	public function executequery($sql)
	{

		$array = array();

		$query = mysqli_query($this->con, $sql);



		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}

	public function insert_record($table, $fields, $print = 0)
	{

		$sql = "";
		$sql .= "INSERT INTO " . $table;
		$sql .= " (" . implode(",", array_keys($fields)) . ") VALUE ";
		$sql .= "('" . implode("','", array_values($fields)) . "')";
		if ($print == 1) {
			print_r($sql);
			die;
		}
		$query = mysqli_query($this->con, $sql);

		if ($query) {
			return 1;
		}
	}


	public function getcode($tablename, $tablepkey, $cond)
	{
		$num = $this->getvalfield($tablename, "max($tablepkey)", $cond);
		if ($num == NULL)
			$num = 0;
		++$num; // add 1;
		$len = strlen($num);
		for ($i = $len; $i < 5; ++$i) {
			$num = '0' . $num;
		}
		return $num;
	}
	function getIndianCurrency(float $number)
	{
		// echo $number;
		// die;

		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(
			0 => '',
			1 => 'one',
			2 => 'two',
			3 => 'three',
			4 => 'four',
			5 => 'five',
			6 => 'six',
			7 => 'seven',
			8 => 'eight',
			9 => 'nine',
			10 => 'ten',
			11 => 'eleven',
			12 => 'twelve',
			13 => 'thirteen',
			14 => 'fourteen',
			15 => 'fifteen',
			16 => 'sixteen',
			17 => 'seventeen',
			18 => 'eighteen',
			19 => 'nineteen',
			20 => 'twenty',
			30 => 'thirty',
			40 => 'forty',
			50 => 'fifty',
			60 => 'sixty',
			70 => 'seventy',
			80 => 'eighty',
			90 => 'ninety'
		);
		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		while ($i < $digits_length) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
			} else
				$str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
	}


	public function insert_record_lastid($table, $fields, $print = 0)
	{
		//"INSERT INTO table_name ( , , ) VALUE ('', '')";
		$sql = "";
		$sql .= "INSERT INTO " . $table;
		$sql .= " (" . implode(",", array_keys($fields)) . ") VALUE ";

		$sql .= "('" . implode("','", array_values($fields)) . "')";
		if ($print == 1) {
			print_r($sql);
			die;
		}
		// echo $sql;die;
		$query = mysqli_query($this->con, $sql);
		$keyvalue = mysqli_insert_id($this->con);
		if ($query) {
			return $keyvalue;
			//echo $query;die;
		}
	}



	function getvalfield($tablename, $column, $condition, $print = 0)
	{
		$sql = "select $column  from $tablename where $condition";
		if ($print == 1) {
			echo $sql;
			die;
		}
		//echo $sql;
		$res = mysqli_query($this->con, $sql);
		$row = mysqli_fetch_assoc($res);
		//print_r($row);
		if ($row != "") {


			return $row[$column];
		}
	}

	function dateformatindia($date)
	{
		if ($date != "") {
			$ndate = explode("-", $date);
			$year = $ndate[0];
			$day = $ndate[2];
			$month = $ndate[1];

			if ($date == "0000-00-00" || $date == "")
				return "";
			else
				return $day . "-" . $month . "-" . $year;
		} else
			return "";
	}

	public function select_record($table, $where, $print = 0)
	{
		// id = '5' AND m_name = 'something'
		$sql = "";
		$condition = "";
		foreach ($where as $key => $value) {
			$condition .= $key . "='" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		$sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
		$query = mysqli_query($this->con, $sql);
		if ($print == 1) {

			echo $sql;
			die;
		}
		$row = mysqli_fetch_assoc($query);
		if ($row != '')
			return $row;
	}


	public function update_record($table, $where, $fields, $print = 0)
	{
		$sql = "";
		$condition = "";
		foreach ($where as $key => $value) {
			// id = 5 AND m_name = 'something'
			$condition .= $key . "='" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		foreach ($fields as $key => $value) {
			// UPDATE table SET m_name = '', qty = '' WHERE id = '';
			$sql .= $key . "='" . $value . "', ";
		}
		$sql = substr($sql, 0, -2);
		$sql = "UPDATE " . $table . " SET " . $sql . " WHERE " . $condition;
		if ($print == 1) {
			print_r($sql);
			die;
		}
		// echo $sql;
		// die;
		if (mysqli_query($this->con, $sql)) {
			return mysqli_insert_id($this->con);
		}
	}

	public function delete_record($table, $where, $print = 0)
	{
		$sql = '';
		$condition = '';
		foreach ($where as $key => $value) {
			$condition .= $key . "='" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		$sql = "DELETE FROM " . $table . " WHERE " . $condition;
		if ($print == 1) {
			echo $sql;
			die;
		}

		if (mysqli_query($this->con, $sql)) {
			return mysqli_insert_id($this->con);
		}
	}

	function get_client_ip()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}


	function test_input($data)
	{
		$data = trim($data);
		$data = addslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}


$obj = new DataOperation;
