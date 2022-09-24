<?php

/**
 *
 */
class Dashboard extends CI_Controller
{

  public function __construct()   {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      echo '<script>alert("Anda harus login terlebih dahulu");window.location.href="'.base_url('login').'"</script>';
    }
  }

  public function index() {
    $this->load->view('admin/dashboard_view');
  }
}
