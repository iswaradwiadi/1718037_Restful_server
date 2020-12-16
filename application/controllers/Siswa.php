<?php
defined('BASEPATH') or exit('No direct script access allowed');
// Don't forget include/define REST_Controller path

/**
 *
 * Controller Siswa
 *
 * This controller for ...
 *
 * @package   CodeIgniter
 * @category  Controller REST
 * @author    Setiawan Jodi <jodisetiawan@fisip-untirta.ac.id>
 * @author    Raul Guerrero <r.g.c@me.com>
 * @link      https://github.com/setdjod/myci-extension/
 * @param     ...
 * @return    ...
 *
 */
use chriskacerguis\RestServer\RestController;

class Siswa extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('siswa_model', 'siswa');
    $this->methods['index_get']['limit'] = 2;
  }

  public function index_get()
  {
    $id = $this->get('id', true);
    if ($id === null) {
      $p = $this->get('page', true);
      $p = (empty($p) ? 1 : $p);
      $total_data = $this->siswa->count();
      $total_page = ceil($total_data / 5);
      $start = ($p - 1) * 5;
      $list = $this->siswa->get(null, 5, $start);
      if ($list) {
        $data = [
          'status' => true,
          'page' => $p,
          'total_data' => $total_data,
          'total_page' => $total_page,
          'data' => $list
        ];
      } else {
        $data = [
          'status' => false,
          'msg' => 'Data tidak ditemukan'
        ];
      }
      $this->response($data, RestController::HTTP_OK);
    } else {
      $data = $this->siswa->get($id);
      if ($data) {
        $this->response(['status' => true, 'data' => $data], RestController::HTTP_OK);
      } else {
        $this->response(['status' => false, 'msg' => $id . ' tidak ditemukan'], RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function index_post()
  {
    $data = [
      'id_siswa' => $this->post('id_siswa', true),
      'nama_siswa' => $this->post('nama_siswa', true),
      'nomor_induk' => $this->post('nomor_induk', true),
      'jurusan' => $this->post('jurusan', true),
      'kelas' => $this->post('kelas', true)
    ];
    $simpan = $this->siswa->add($data);
    if ($simpan['status']) {
      $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data telah ditambahkan'], RestController::HTTP_CREATED);
    } else {
      $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
    }
  }

  public function index_put()
  {
    $data = [
      'id_siswa' => $this->post('id_siswa', true),
      'nama_siswa' => $this->post('nama_siswa', true),
      'nomor_induk' => $this->post('nomor_induk', true),
      'jurusan' => $this->post('jurusan', true),
      'kelas' => $this->post('kelas', true)
    ];
    $id = $this->put('id', true);
    if ($id === null) {
      $this->response(['status' => false, 'msg' => 'Masukkan id siswa yang akan dirubah'], RestController::HTTP_BAD_REQUEST);
    }
    $simpan = $this->siswa->update($id, $data);
    if ($simpan['status']) {
      $status = (int)$simpan['data'];
      if ($status > 0)
        $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data telah dirubah'], RestController::HTTP_OK);
      else
        $this->response(['status' => false, 'msg' => 'Tidak ada data yang dirubah'], RestController::HTTP_BAD_REQUEST);
    } else {
      $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
    }
  }

  public function index_delete()
  {
    $id = $this->delete('id', true);
    if ($id === null) {
      $this->response(['status' => false, 'msg' => 'Masukkan id siswa yang akan dihapus'], RestController::HTTP_BAD_REQUEST);
    }
    $delete = $this->siswa->delete($id);
    if ($delete['status']) {
      $status = (int)$delete['data'];
      if ($status > 0)
        $this->response(['status' => true, 'msg' => $id . ' data telah dihapus'], RestController::HTTP_OK);
      else
        $this->response(['status' => false, 'msg' => 'Tidak ada data yang dihapus'], RestController::HTTP_BAD_REQUEST);
    } else {
      $this->response(['status' => false, 'msg' => $delete['msg']], RestController::HTTP_INTERNAL_ERROR);
    }
  }

}


/* End of file Siswa.php */
/* Location: ./application/controllers/Siswa.php */