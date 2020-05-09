<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent:: __construct();
		$this->load->library('template');
	}

	public function index()
	{
		$this->template->template('upload_file');
	}

	public function upload($token)
	{
		header('Content-Type: application/json');
		$uploaded = array();

		// if(!empty($_FILES['file']['name'][0])){
		// 	foreach ($_FILES['file']['name'] as $position => $name) {
		// 		if (move_uploaded_file($_FILES['file']['tmp_name'][$position], 'assets/uploads/'.$name)) {
		// 			$uploaded[] = array(
		// 				'name' => $name,
		// 				'file' => 'assets/uploads/'.$name
		// 			);
		// 		}
		// 	}
		// }
		if ($_FILES['file']['tmp_name']) {
			$fileName = $_FILES['file']['name'];
			$fileTmpLoc = $_FILES['file']['tmp_name'];
			$fileType = $_FILES['file']['type'];
			$fileSize = $_FILES['file']['size'];
			$fileErrorMsg = $_FILES['file']['error'];
			// var_dump($_FILES);

			if(move_uploaded_file($fileTmpLoc, 'assets/uploads/'.$fileName)){
				// echo "$fileName upload is complete";
				// echo "complete";
				$uploaded[] = array(
							'token' => $token,
							'size' => $fileSize,
							'name' => $fileName,
							'file' => 'assets/uploads/'.$fileName,
							'msg' => 'complete'
						);
			}else{
				// echo "move_uploaded_file function failed";
				$uploaded[] = array(
							'name' => 'no file',
							'file' => 'no file in directory',
							'msg' => 'move_uploaded_file function failed'
						);
			}
			
		}else{
			$uploaded[] = array(
							'name' => 'no file',
							'file' => 'no file in directory',
							'msg' => 'ERROR: Please browse for afile before clicking the upload buttton.'
						);
		}

		echo json_encode($uploaded);
	}

	public function v_upload_progress()
	{
		$this->template->template('upload_progress');
	}

	public function upload_progress()
	{
		$fileName = $_FILES['file']['name'];
		$fileTmpLoc = $_FILES['file']['tmp_name'];
		$fileType = $_FILES['file']['type'];
		$fileSize = $_FILES['file']['size'];
		$fileErrorMsg = $_FILES['file']['error'];
		// var_dump($_FILES);

		if(!$fileTmpLoc){
			echo "ERROR: Please browse for afile before clicking the upload buttton.";
			exit();
		}else if(move_uploaded_file($fileTmpLoc, 'assets/uploads/'.$fileName)){
			// echo "$fileName upload is complete";
			echo "complete";
		}else{
			echo "move_uploaded_file function failed";
		}

	}

	function remove_upload($token){

		//Ambil token foto
		$token=$this->input->post('token');

		
		$foto=$this->db->get_where('tbl_gallery',array('f_token'=>$token));


		if($foto->num_rows()>0){
			$hasil=$foto->row();
			$nama_foto=$hasil->f_nama_foto;
			if(file_exists($file='assets/upload/'.$nama_foto)){
				unlink($file);
			}
			$this->db->delete('tbl_gallery',array('f_token'=>$token));

		}


		echo "{}";
	}
}
