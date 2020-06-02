<?php

class Supplier
{
	protected $connection;

	protected $table = 'supplier_list';
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
	public function get($filters = [], $order = 'supplier_name', $orderDir = 'ASC')
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
		// echo $query;
		// die;
		$result = null;
		if ($stmt = mysqli_query($this->connection, $query)) {
			
			while($row = mysqli_fetch_assoc($stmt)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	public function getList($filters = [], $order = 'supplier_name', $orderDir = 'ASC')
	{
		$tableName = $this->table;

		$query = "SELECT id, supplier_name,supplier_email,supplier_address FROM $tableName WHERE 1=1 ";

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
		print_R($list);
		die;
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
		$query = "INSERT INTO $tableName (user_id, supplier_name, supplier_email, supplier_address, status) VALUES(?,?,?,?,?)";
		$stmt = mysqli_prepare($this->connection, $query);
		
		if ($stmt = mysqli_prepare($this->connection, $query)) {

			$status = false;
			if(isset($data['status']) && ($data['status'] == 'on' || $data['status'] == true)) {
				$status = true;
			}

			$userId = $data['user_id'];
			$supplier_name = $data['supplier_name'];
			$supplier_email = $data['supplier_email'];
			$supplier_address = $data['supplier_address'];
			
			$status = (bool)$status;
			mysqli_stmt_bind_param($stmt, "isssi", $userId, $supplier_name, $supplier_email, $supplier_address, $status);
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

		 $query = "UPDATE $tableName SET supplier_name=?, supplier_email=?, supplier_address=?,status=? WHERE id=?";
		
		if ($stmt = mysqli_prepare($this->connection, $query)) {

			$status = false;
			if(isset($data['status']) && ($data['status'] == 'on' || $data['status'] == true)) {
				$status = true;
			}
			$supplier_name = $data['supplier_name'];
			$supplier_email = $data['supplier_email'];
			$supplier_address = $data['supplier_address'];
			$status = (bool)$status;
			mysqli_stmt_bind_param($stmt, "sssii", $supplier_name, $supplier_email, $supplier_address, $status, $id);
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
		$query = "DELETE FROM $tableName WHERE id = $id";
		if (mysqli_query($this->connection, $query)) {
			return true;
		}
		return false;
	}
}