<?php

define("ANALYSIS_TABLE_NAME", "analysis");
define("ANALYSIS_ID", "id");
define("ANALYSIS_PK", ANALYSIS_TABLE_NAME . "." . ANALYSIS_ID);
define("ANALYSIS_PERSON", "person_id");
define("ANALYSIS_PERSON_FK", ANALYSIS_TABLE_NAME . "." . ANALYSIS_PERSON);
define("ANALYSIS_ARTICLE", "item_id");
define("ANALYSIS_ARTICLE_FK", ANALYSIS_TABLE_NAME . "." . ANALYSIS_ARTICLE);
define("ANALYSIS_FEEDBACK_ID", "feedback_id");
define("ANALYSIS_FEEDBACK_TOKEN", "feedback_token");
define("ANALYSIS_FEEDBACK_READ", "feedback_read");
define("ANALYSIS_SCORE", "score");
define("ANALYSIS_COMMENTS", "comments");
define("ANALYSIS_START_DATE", "start_date");
define("ANALYSIS_CREATION_DATE", "creation_date");
define("ANALYSIS_FEEDBACK_DATE", "feedback_date");
define("ANALYSIS_FORMATTED_DATE_ALIAS", "date");
define("ANALYSIS_FORMATTED_DATE", "DATE_FORMAT( " . ANALYSIS_START_DATE .", '%d-%m-%Y' )");


class Analysis extends CI_Model {
	
	/*
	Determines if a given item_id is an item
	*/
	function exists($id)
	{
		$this->db->from(ANALYSIS_TABLE_NAME);
		$this->db->where(ANALYSIS_ID, $id);
		$query = $this->db->get();
		return ($query->num_rows()==1);
	}
	
	/**
	 * Get all analyses with no token, fk_id not null and date before today
	 */
	function get_analysis_by_unsent_feedback() {
		$this->db->select("an1.*");
		$this->db->select("an2.creation_date AS " . ANALYSIS_CREATION_DATE);
		$this->db->select(PERSON_TABLE_NAME . ".*");
	       	$this->db->from(ANALYSIS_TABLE_NAME . " an1", FALSE);
    	   	$this->db->join(PERSON_TABLE_NAME, PERSON_PK . " = an1." . ANALYSIS_PERSON);
    	   	$this->db->join(ANALYSIS_TABLE_NAME . " an2", "an1." . ANALYSIS_FEEDBACK_ID . " = an2." . ANALYSIS_ID);
    		$this->db->where("an1." . ANALYSIS_FEEDBACK_TOKEN, NULL);
    		$this->db->where("an1." . ANALYSIS_FEEDBACK_ID . " IS NOT NULL");
    		$this->db->where("an1." . ANALYSIS_START_DATE ." < NOW()");
    		return $this->db->get()->result_array();
	}
	
	function feedback_exists($feedback_id) 
	{
		$this->db->select(ANALYSIS_TABLE_NAME . ".*");
	    	$this->db->select(ANALYSIS_FORMATTED_DATE, FALSE);
	       	$this->db->from(ANALYSIS_TABLE_NAME);
	    	$this->db->where(ANALYSIS_FEEDBACK_ID, $feedback_id);
	    	return $this->db->get()->row_array();	
	}
	
	function get_analysis_by_token_id($feedback_token) 
	{
		$this->db->select("an1.*");
		$this->db->select("an2.start_date AS " . ANALYSIS_START_DATE);
		$this->db->select(PERSON_TABLE_NAME . ".*");
    	   	$this->db->from(ANALYSIS_TABLE_NAME . " an1", FALSE);
    	   	$this->db->join(PERSON_TABLE_NAME, PERSON_PK . " = an1." . ANALYSIS_PERSON);
    	   	$this->db->join(ANALYSIS_TABLE_NAME . " an2", "an1." . ANALYSIS_FEEDBACK_ID . " = an2." . ANALYSIS_ID);
    		$this->db->where("an1." . ANALYSIS_FEEDBACK_TOKEN, $feedback_token);
    		$this->db->where("an1." . ANALYSIS_START_DATE ." <", "NOW()");
    		return $this->db->get()->row_array();
	}
	
	function get_analysis_by_feedback_id($feedback_id) 
	{
		$this->db->select("an1.*");
		$this->db->select("an2.start_date AS " . ANALYSIS_START_DATE);
		$this->db->select(PERSON_TABLE_NAME . ".*");
        $this->db->from(ANALYSIS_TABLE_NAME . " an1", FALSE);
        $this->db->join(ANALYSIS_TABLE_NAME . " an2", "an1." . ANALYSIS_FEEDBACK_ID . " = an2." . ANALYSIS_ID);
        $this->db->join(PERSON_TABLE_NAME, PERSON_PK . " = an1." . ANALYSIS_PERSON);
    	$this->db->where("an1." . ANALYSIS_FEEDBACK_ID, $feedback_id);
    	return $this->db->get()->row_array();
	}
	
	function get_analysis_by_day($sale_time) 
	{
		$this->db->from(ANALYSIS_TABLE_NAME);
		$this->db->where(ANALYSIS_ARTICLE_FK . " IS NULL");
		$this->db->where(ANALYSIS_COMMENTS . " IS NOT NULL");
		$this->db->where(ANALYSIS_FEEDBACK_ID . " IS NULL");
		$this->db->where("DATE_FORMAT(" .ANALYSIS_START_DATE. ",'%Y-%m-%d')", date('Y-m-d', strtotime($sale_time)));
		$this->db->order_by(ANALYSIS_START_DATE, "desc");
		return $this->db->get()->result_array();
	}

    function get_analysis($id) 
    {    	
    	$this->db->select(ANALYSIS_TABLE_NAME . ".*");
    	$this->db->select(ANALYSIS_FORMATTED_DATE, FALSE);
       	$this->db->from(ANALYSIS_TABLE_NAME);
    	$this->db->where(ANALYSIS_ID, $id);
    	return $this->db->get()->row_array();
    }
    
    function get_total_rows() 
	{
		return $this->db->count_all(ANALYSIS_TABLE_NAME);	
	}
    
    function save(&$analysis_data, $id=false)
    {	
    	
    	if (!$id or !$this->exists($id))
    	{
    		if ($this->db->insert('analysis',$analysis_data))
    		{
    			$analysis_data['id']=$this->db->insert_id();
    			return true;
    		}
    	
    		return false;
    	}
		$this->db->where('id', $id);
		return $this->db->update('analysis', $analysis_data);
    }
    
    function save_by_appointment_extref(&$analysis_data, $appointment_extref)
    {
    	$this->db->where('appointment_extref', $appointment_extref);
    	return $this->db->update('analysis', $analysis_data);
    }

}
?>
