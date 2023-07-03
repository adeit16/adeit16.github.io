<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//class untuk halaman utama

class siswa extends CI_Controller {

	function __construct(){
		parent::__construct();
		if($this->session->userdata('status') != 'login'){
			echo '<script>alert("Anda harus login terlebih dahulu");</script>';
			echo '<script>window.location.href = "'.base_url().'";</script>';
		}else if($this->session->userdata('level') != 'pegawai'){
			echo '<script>alert("Anda tidak diizinkan mengakses halaman ini");</script>';
			echo '<script>window.location.href = "'.base_url().'";</script>';
		}
	}

	public function index(){
		$this->db->from('tb_siswa');
		$this->db->join('tb_kelas', 'tb_kelas.idkelas = tb_siswa.idkelas');
		$data = $this->db->get();
		$data = array(
			'page' => 'template_admin_v2/dashboard_siswa',
			'link' => 'siswa',
			'list' => $data
		);
		$this->load->view('template_admin_v2/template/wrapper', $data);
	}

	public function tambah_siswa(){
		$data = array(
			'page' => 'template_admin_v2/tambah_siswa',
			'link' => 'siswa',
			'kelas' => $this->db->get('tb_kelas')
		);
		$this->load->view('template_admin_v2/template/wrapper', $data);
	}

	public function simpan_siswa(){
		$cek = $this->db->get_where('tb_siswa', array('nis' => $this->input->post('nis', true)));
		$cek_username = $this->db->get_where('tb_siswa', array('username' => $this->input->post('username', true)));

		$data = array(
			// ...
			'username' => $this->input->post('username', true),
			'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
		);
		
		// Konfigurasi upload foto
		$config['upload_path'] = './assets/foto/siswa'; // Ubah dengan path folder upload sesuai kebutuhan
		$config['allowed_types'] = 'jpg|jpeg|png'; // Jenis file yang diperbolehkan
		$config['max_size'] = 2048; // Ukuran maksimum file dalam kilobita (KB)
	
		$this->load->library('upload', $config);
	
		// Proses upload foto
		if ($this->upload->do_upload('foto')) {
			$upload_data = $this->upload->data();
			$file_name = $upload_data['file_name'];
	
			// Lanjutkan dengan menyimpan nama file ke database atau melakukan operasi lainnya
		} else {
			$error = $this->upload->display_errors();
			// Tangani kesalahan upload jika diperlukan
		}
	
		// Lanjutkan dengan operasi lainnya setelah upload foto
		// ...
		
	
		if($cek->num_rows() != 0){
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Maaf!</strong> NIS Sudah Ada !</div>'
			);
			redirect(base_url().'siswa/tambah_siswa'); //location
			exit();
		}

		if($cek_username->num_rows() != 0){
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Maaf!</strong> Username Sudah Ada !</div>'
			);
			redirect(base_url().'siswa/tambah_siswa'); //location
			exit();
		}

		$data = array(
			'nis' => $this->input->post('nis', true),
			'namasiswa' => $this->input->post('namasiswa', true),
			'alamat' => $this->input->post('alamat', true),
			'telpayah' => $this->input->post('telpayah', true),
			'jk' => $this->input->post('jk', true),
			'idkelas' => $this->input->post('idkelas', true),
			'agama' => $this->input->post('agama', true),
			'tempatlahir' => $this->input->post('tempatlahir', true),
			'namaayah' => $this->input->post('namaayah', true),
			'namaibu' => $this->input->post('namaibu', true),
			'namawali' => $this->input->post('namawali', true),
			'pekerjaanayah' => $this->input->post('pekerjaanayah', true),
			'pekerjaanibu' => $this->input->post('pekerjaanibu', true),
			'pekerjaanwali' => $this->input->post('pekerjaanwali', true),
			'asalsekolah' => $this->input->post('asalsekolah', true),
			'tglmasuksekolah' => $this->input->post('tglmasuksekolah', true),
			'tgllahir' => $this->input->post('tgllahir', true),
			// 'foto' => $_FILES['foto'];
			// 'foto' => $_FILES['foto']['name']
			// if ($foto=''){}else {
			// 	$config['upload_path'] ='./assets/foto'
			// 	$config['allow_types'] ='jpg|png';

			// 	$this->load->library('upload',$config);
			// 	if(!$this->upload->do_upload('foto')){
			// 		echo "Upload Gagal"; die();
			// 	}else{
			// 		$foto=$this->upload->data('file_name')
			// 	}
			// }
			// 'foto' => $this->input->post('', true),
			'username' => $this->input->post('username', true),
			'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
		);
		$simpan = $this->db->insert('tb_siswa', $data);
		if($simpan){
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Success!</strong> Data berhasil disimpan !</div>'
			);
			redirect(base_url().'siswa/tambah_siswa'); //location
		}
		else{
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Peringatan!</strong> Data gagal disimpan !</div>'
			);
			redirect(base_url().'siswa/tambah_siswa'); //location
		}
	}


	public function hapus_siswa($idguru){
		$this->db->where(array('idsiswa' => $idguru));
		$hapus = $this->db->delete('tb_siswa');
		if($hapus){
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Success!</strong> Data berhasil dihapus !</div>'
			);
			redirect(base_url().'siswa'); //location
		}
		else{
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Peringatan!</strong> Data gagal dihapus !</div>'
			);
			redirect(base_url().'siswa'); //location
		}
	}

	public function lihat_siswa($idguru){
		$data = array(
			'page' => 'template_admin_v2/lihat_siswa',
			'link' => 'siswa',
			'list' => $this->db->get_where('tb_siswa', array('idsiswa' => $idguru)),
			'kelas' => $this->db->get('tb_kelas')
		);
		$this->load->view('template_admin_v2/template/wrapper', $data);
	}

	public function update_siswa(){
		if($this->input->post('password', true) != ''){
			$data = array(
				'nis' => $this->input->post('nis', true),
				'namasiswa' => $this->input->post('namasiswa', true),
				'alamat' => $this->input->post('alamat', true),
				'telpayah' => $this->input->post('telpayah', true),
				'jk' => $this->input->post('jk', true),
				'idkelas' => $this->input->post('idkelas', true),
				'agama' => $this->input->post('agama', true),
				'tempatlahir' => $this->input->post('tempatlahir', true),
				'namaayah' => $this->input->post('namaayah', true),
				'namaibu' => $this->input->post('namaibu', true),
				'namawali' => $this->input->post('namawali', true),
				'pekerjaanayah' => $this->input->post('pekerjaanayah', true),
				'pekerjaanibu' => $this->input->post('pekerjaanibu', true),
				'pekerjaanwali' => $this->input->post('pekerjaanwali', true),
				'asalsekolah' => $this->input->post('asalsekolah', true),
				'tglmasuksekolah' => $this->input->post('tglmasuksekolah', true),
				'tgllahir' => $this->input->post('tgllahir', true),
				'username' => $this->input->post('username', true),
				'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
			);
		}else{
			$data = array(
				'nis' => $this->input->post('nis', true),
				'namasiswa' => $this->input->post('namasiswa', true),
				'alamat' => $this->input->post('alamat', true),
				'telpayah' => $this->input->post('telpayah', true),
				'jk' => $this->input->post('jk', true),
				'idkelas' => $this->input->post('idkelas', true),
				'agama' => $this->input->post('agama', true),
				'tempatlahir' => $this->input->post('tempatlahir', true),
				'namaayah' => $this->input->post('namaayah', true),
				'namaibu' => $this->input->post('namaibu', true),
				'namawali' => $this->input->post('namawali', true),
				'pekerjaanayah' => $this->input->post('pekerjaanayah', true),
				'pekerjaanibu' => $this->input->post('pekerjaanibu', true),
				'pekerjaanwali' => $this->input->post('pekerjaanwali', true),
				'asalsekolah' => $this->input->post('asalsekolah', true),
				'tglmasuksekolah' => $this->input->post('tglmasuksekolah', true),
				'tgllahir' => $this->input->post('tgllahir', true),
				'username' => $this->input->post('username', true),
				'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
			);
		}
		$this->db->where(array('idsiswa' => $this->input->post('idsiswa', true)));
		$simpan = $this->db->update('tb_siswa', $data);
		if($simpan){
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Success!</strong> Data berhasil diupdate !</div>'
			);
			redirect(base_url().'siswa/lihat_siswa/'.$this->input->post('idsiswa', true)); //location
		}
		else{
			$this->session->set_flashdata(
			    'msg', 
			    '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" arial-label="close">&times;</a><strong>Peringatan!</strong> Data gagal diupdate !</div>'
			);
			redirect(base_url().'siswa/lihat_siswa/'.$this->input->post('idsiswa', true)); //location
		}
	}

}