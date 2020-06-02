<?php

class Subproduct
{
	protected $connection;

	protected $table = 'sub_products';
	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * get list of sections.
	 * 
	 * @param  array  $filters: array of filtering params
	 * @param  string $order: order fields
	 * @param  string $orderDir: order by direction.
	 * @return array of section items.
	 */
	public function get($filters = [], $order = 'name', $orderDir = 'ASC')
	{
		$tableName = $this->table;

		 $query = "SELECT * FROM $tableName WHERE 1=1 ";
      
		if($filters) {
			$where = [];
			foreach ($filters as $key => $value) {
				$where[] = "$key='$value'";
			}
			$query .= ' AND ' . implode(' AND ', $where);
		}
		$query .= " ORDER BY $order $orderDir";
	
		$result = null;
		if ($stmt = mysqli_query($this->connection, $query)) {
			
			while($row = mysqli_fetch_assoc($stmt)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	public function getList($filters = [], $order = 'name', $orderDir = 'ASC')
	{
		$tableName = $this->table;

		$query = "SELECT id, name FROM $tableName WHERE 1=1 ";

		if($filters) {
			$where = [];
			foreach ($filters as $key => $value) {
				$where[] = "$key='$value'";
			}
			$query .= ' AND ' . implode(' AND ', $where);
		}
		$query .= " ORDER BY $order $orderDir";
		
		$list = null;
		if ($stmt = mysqli_query($this->connection, $query)) {
			
			while($row = mysqli_fetch_assoc($stmt)) {
				$list[$row['id']] = $row['name'];
			}
		}
		return $list;
	}

	/**
	 * get section by id.
	 * 
	 * @param  $id: integer of section id.
	 * @return array of requested section.
	 */
	public function findById($id = null) {
		$tableName = $this->table;

		$query = "SELECT * FROM $tableName WHERE id=$id";
		$stmt = mysqli_query($this->connection, $query);
		$result = mysqli_fetch_assoc($stmt);
		return $result;
	}

	/**
	 * create new section.
	 * 
	 * @param  array $data: array of section fields data.
	 * @return boolean (true/false)
	 */
	public function create($data)
	{
		$tableName = $this->table;
		$query = "INSERT INTO $tableName (product_id, name, product_price, status) VALUES(?,?,?,?)";
		$stmt = mysqli_prepare($this->connection, $query);
		
		if ($stmt = mysqli_prepare($this->connection, $query)) {

			$status = false;
			if(isset($data['status']) && ($data['status'] == 'on' || $data['status'] == true)) {
				$status = true;
			}
			// print_R($status);
			// die;
			$product_id = $data['product_id'];
			$name = $data['name'];
			$product_price = $data['product_price'];
			// $printerIp = $data['printer_ip'];
			$status = (bool)$status;
			mysqli_stmt_bind_param($stmt, "isdi", $product_id, $name,$product_price,$status);
			// echo $stmt;
			$updateproduct="UPDATE `products` SET `varient_exit` = 'y' where id='$product_id'";
			$stmt2 = mysqli_query($this->connection, $updateproduct);
			mysqli_stmt_execute($stmt);
			$queryStatus = mysqli_stmt_affected_rows($stmt);
			mysqli_stmt_close($stmt);
			if($queryStatus) {
				return true;
			}
		}
		return false;
	}

	/**
	 * update section by id with requested data.
	 * 
	 * @param  integer $id: section id.
	 * @param  array  $data: array of section data.
	 * @return boolean (true/false)
	 */
	public function update($id, $data = [])
	{
		$tableName = $this->table;

		$query = "UPDATE $tableName SET name=?, product_price=?,status=? WHERE id=?";
		if ($stmt = mysqli_prepare($this->connection, $query)) {

			$status = false;
			if(isset($data['status']) && ($data['status'] == 'on' || $data['status'] == true)) {
				$status = true;
			}
			$name = $data['name'];
			$product_price = $data['product_price'];
			// $printerIp = $data['printer_ip'];
			$status = (bool)$status;
			mysqli_stmt_bind_param($stmt, "sdii", $name, $product_price, $status, $id);
			mysqli_stmt_execute($stmt);
			$queryStatus = mysqli_stmt_affected_rows($stmt);
			mysqli_stmt_close($stmt);
			if($queryStatus) {
				return true;   
			}
		}
		return false;
	}

	/**
	 * toggle section status.
	 * 
	 * @param  integer $id: integer of section id.
	 * @return boolean (true/false)
	 */
	public function toggleStatus($id)
	{
		$tableName = $this->table;

		$item = $this->findById($id);
		$status = !((bool)$item['status'] ?: false);

		$query = "UPDATE $tableName SET status=? WHERE id=?";
		if ($stmt = mysqli_prepare($this->connection, $query)) {

			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$queryStatus = mysqli_stmt_affected_rows($stmt);
			mysqli_stmt_close($stmt);
			if($queryStatus) {
				return true;
			}
		}
		return false;
	}

	/**
	 * delete section by id.
	 * 
	 * @param  integer $id: integer of section id.
	 * @return booleamn (true/false)
	 */
	public function delete($id)
	{
		$tableName = $this->table;
		$query = "SELECT product_id FROM $tableName WHERE id=$id";
		$stmt = mysqli_query($this->connection, $query);
		$result = mysqli_fetch_assoc($stmt);
		$product_id=$result['product_id'];
		$query = "DELETE FROM $tableName WHERE id = $id";
		if (mysqli_query($this->connection, $query)) {
			if($product_id)
			{
				$query = "SELECT count(id) as count FROM $tableName WHERE product_id=$product_id";
				$stmt = mysqli_query($this->connection, $query);
				$result = mysqli_fetch_assoc($stmt);
				if($result['count']==0)
				{
					$query = "UPDATE `products` SET `varient_exit` = 'n' WHERE `products`.`id` ='$product_id'";
					$stmt = mysqli_query($this->connection, $query);
				}
			}
			return true;
		}
		return false;
	}
}