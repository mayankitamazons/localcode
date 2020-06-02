<?php

class SectionTable
{
	protected $connection;

	protected $table = 'section_tables';
	protected $fields = ['id', 'section_id', 'name', 'description', 'status'];
	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * get list of section tables.
	 * 
	 * @param  array  $filters: array of filtering params
	 * @param  string $order: order fields
	 * @param  string $orderDir: order by direction.
	 * @return array of section table items.
	 */
	public function get($filters = [], $order = 'name', $orderDir = 'ASC')
	{
		$tableName = $this->table;

		$query = "SELECT $tableName.*, s.name as section_name FROM $tableName LEFT JOIN sections as s on s.id =$tableName.section_id ";
		$query .= "WHERE 1=1 ";

		if($filters) {
			$where = [];

			foreach ($filters as $key => $value) {

				if(in_array($key, $this->fields)) {
					$where[] = "$tableName.$key='$value'";
				}
			}
			$query .= ' AND ' . implode(' AND ', $where);
		}
		$query .= " ORDER BY $tableName.$order $orderDir";
		
		$result = [];
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
	 * get section table by id.
	 * 
	 * @param  $id: integer of table id.
	 * @return array of requested table.
	 */
	public function findById($id = null) {
		$tableName = $this->table;

		$query = "SELECT * FROM $tableName WHERE id=$id";
		$stmt = mysqli_query($this->connection, $query);
		$result = mysqli_fetch_assoc($stmt);
		return $result;
	}

	/**
	 * create new table.
	 * 
	 * @param  array $data: array of table fields data.
	 * @return boolean (true/false)
	 */
	public function create($data)
	{
		$tableName = $this->table;
		$query = "INSERT INTO $tableName (section_id, name, description, status) VALUES(?,?,?,?)";
		$stmt = mysqli_prepare($this->connection, $query);
		
		if ($stmt = mysqli_prepare($this->connection, $query)) {

			$status = false;
			if(isset($data['status']) && ($data['status'] == 'on' || $data['status'] == true)) {
				$status = true;
			}

			$sectionId = $data['section_id'];
			$name = $data['name'];
			$description = $data['description'];
			$status = (bool)$status;
			mysqli_stmt_bind_param($stmt, "issi", $sectionId, $name, $description, $status);
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
	 * update table by id with requested data.
	 * 
	 * @param  integer $id: table id.
	 * @param  array  $data: array of table data.
	 * @return boolean (true/false)
	 */
	public function update($id, $data = [])
	{
		$tableName = $this->table;
		$query = "UPDATE $tableName SET section_id=?, name=?, description=?, status=? WHERE id=?";
		if ($stmt = mysqli_prepare($this->connection, $query)) {

			$status = false;
			if(isset($data['status']) && ($data['status'] == 'on' || $data['status'] == true)) {
				$status = true;
			}

			$sectionId = $data['section_id'];
			$name = $data['name'];
			$description = $data['description'];
			$status = (bool)$status;
			mysqli_stmt_bind_param($stmt, "issii", $sectionId, $name, $description, $status, $id);
			mysqli_stmt_execute($stmt);
			$queryStatus = mysqli_stmt_affected_rows($stmt);
			mysqli_stmt_close($stmt);
			// if($queryStatus) {
				return true;
			// }
		}
		return false;
	}

	/**
	 * toggle table status.
	 * 
	 * @param  integer $id: integer of table id.
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
	 * delete table by id.
	 * 
	 * @param  integer $id: integer of table id.
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