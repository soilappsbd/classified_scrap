<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_common extends CI_Model {

// Data query
	public function databysql($sql){
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return false;
		}
	}
	
	
// Data array	
	public function dataarray($sql){
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	
// count rows	
	public function countrows($sql){
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			return $query->num_rows();
		}else{
			return false;
		}
	}


// Insert data	
	public function insertdata($tablename,$data){
		if($tablename==null || $data==null){
			return false;
		}else{
			$query = $this->db->insert($tablename,$data);
			return $this->db->insert_id();
		}
		
	}

	
// Update data	
	public function updatedata($tablename,$data, $column, $id){
		$this->db->where($column,$id);
		$query = $this->db->update($tablename,$data);
		return $this->db->affected_rows();
	}

	
// Delete Data	
	public function deletedata($tablename,$column, $id){
		$this->db->where($column,$id);
		$query = $this->db->delete($tablename);
		return $this->db->affected_rows();
	}
	
	
// Mail send	
	public function mailsend($fromemail, $fromname, $to, $subject, $message){
		$this->load->library('email');
		$this->email->set_mailtype("html");
		$this->email->from($fromemail, $fromname);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}
	
// Success flash

	public function successFlash($msg){
		$this->session->set_flashdata('msg','<div class="alert alert-success alert-dismissible mb-2" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
							'.
							$msg.'
						</div>');	
		
	}
	
	public function unsuccessFlash($msg){
		$this->session->set_flashdata('msg','<div class="alert alert-danger alert-dismissible mb-2" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>'.
							$msg.'
						</div>');	
		
	}


		
}//end of class

