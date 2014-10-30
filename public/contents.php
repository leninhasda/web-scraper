<?php 

/**
 * content class
 *
 * @author 	: Lenin Hasda
 * @URL 	: http://leninhasda.net
 * @version : 1.0.0
 */

class Content 
{	
	/**
	 * @var id
	 */
	public $id;

	/**
	 * @var name
	 */
	public $name;

	/**
	 * @var link
	 */
	public $link;

	/**
	 * `@var resume
	 */
	public $resume;

	/**
	 * @var is archive
	 */
	public $is_archive;

	/**
	 * @var create data
	 */
	public $created_at;


	/**
	 * the db object
	 *
	 * @var db
	 */
	public $db;

	/**
	 * constructor method
	 *
	 * @param void
	 * @return void
	 */
	function __construct()
	{
		global $db;
		$this->db = $db;
	}

	/**
	 * save new content
	 *
	 * @param void
	 * @return int - insert id
	 */
	public function save() 
	{
		$this->check();

		$sql  = " insert into contents (name, link, resume, is_archive, created_at) values ('";
		$sql .= $this->name . "', '";
		$sql .= $this->link . "', '";
		$sql .= $this->resume . "', '";
		$sql .= $this->is_archive . "', '";
		$sql .= $this->created_at . "')";
		$query = $this->db->query($sql);

		$this->id = $this->db->insert_id();

		return $this->id;
	}

	public function check_duplicate() 
	{
		$obj = new self();

		$sql  = " select * from contents ";
		$sql .= " where name='".$this->name."' or link='".$this->link."'";
		$sql .= " limit 1";
		$query = $obj->db->query($sql);

		return $obj->db->num_rows($query);
	}

	/**
	 * archive a content
	 *
	 * @param void
	 * @return int - affected row
	 */
	public function archived()
	{
		$this->is_archive = 1;
		$sql  = " update contents ";
		$sql .= " set is_archive='".$this->is_archive."'";
		$sql .= " where id=".(int)$this->id;
		$query = $this->db->query($sql);

		return $this->db->affected_rows();	
	}

	/**
	 * find a content by id
	 *
	 * @param int - id
	 * @return object - content object
	 */
	public static function find($id) 
	{
		$obj = new self();

		$sql = " select * from contents where id='".(int)$id."' limit 1";
		$query = $obj->db->query($sql);
		$result = $obj->db->fetch_array($query);

		$obj->id = $result['id'];
		$obj->name = $result['name'];
		$obj->link = $result['link'];
		$obj->resume = $result['resume'];
		$obj->is_archive = $result['is_archive'];
		$obj->created_at = $result['created_at'];

		return $obj;
	}

	/**
	 * retrive multiple contents
	 *
	 * @param array - filter data
	 * @return array - content object array
	 */
	public static function get($data = null) 
	{
		$obj = new self();

		if(isset($data['fields'])) {
			if(is_array($$data['fields']))
				$fields = implode(',', $data['fields']);
		} else {
			$fields = '*';
		}

		$sql  = " select ".$fields." from contents ";
		$sql .= " order by created_at desc ";
		if(isset($data['page']) && isset($data['show']))
			$sql .= " limit ".((int)$data['page'] * (int) $data['show']).", ".(int)$data['show'];
		else
			$sql .= " limit 0, 50";

		$query = $obj->db->query($sql);
		$results = [];
		while($result = $obj->db->fetch_array($query)) {
			$newObj = new self;
			$newObj->id = $result['id'];
			$newObj->name = $result['name'];
			$newObj->link = $result['link'];
			$newObj->resume = $result['resume'];
			$newObj->is_archive = $result['is_archive'];
			$newObj->created_at = $result['created_at'];

			$results[] = $newObj;
		}

		return $results;
	}

	/**
	 * check of archive and date and set them if not given
	 *
	 * @param void
	 * @return void
	 */
	protected function check() 
	{
		$this->is_archive = 0;
		$this->created_at = date('Y-m-d H:i:s');
	}
}