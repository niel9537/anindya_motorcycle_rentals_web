<?php

/**
 *
 */
class Sewa extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if (empty($this->session->userdata('is_login'))) {
      echo '<script>alert("Anda harus login terlebih dahulu");window.location.href="' . base_url('login') . '"</script>';
    }
  }

  public function index()
  {
    $this->load->view('sewa_view');
  }

  public function tambah_sewa()
  {
    $kode         = $this->input->post('kode');
    $produk_id    = $this->input->post('produk_id');
    $dari         = $this->input->post('dari');
    $sampai       = $this->input->post('sampai');
    $qty          = $this->input->post('qty');
    $nama         = $this->input->post('nama_produk');
    $harga        = $this->input->post('harga');
    $selisih      = $this->all_model->hitung_selisih($dari, $sampai);
    $tharga       = $harga * $selisih;



    $data = array(
      'id'         => $dari,
      'produk_id'  => $produk_id,
      'qty'        => $qty,
      'dari'       => $dari,
      'sampai'     => $sampai,
      'price'      => $tharga,
      'name'       => $nama,
    );
    $this->back($kode, $produk_id, $data);
    // $in = $this->cart->insert($data);
    // if ($in) {
    //   redirect('sewa');
    // }
  }

  public function hapus($id)
  {
    $in = $this->cart->remove($id);
    if ($in) {
      redirect('sewa');
    }
  }

  public function simpan_sewa()
  {
    $data = $this->cart->contents();
    foreach ($data as $k) {
      $data_simpan = array(
        'user_id'    => $this->session->userdata('user_id'),
        'produk_id'  => $k['produk_id'],
        'dari'       => $k['dari'],
        'sampai'     => $k['sampai'],
        'jumlah'     => $k['qty'],
        'harga'      => $k['subtotal']
      );
      //$this->all_model->updatestok($k['produk_id'], );
      $this->all_model->insert($data_simpan, 'transaksi');
    }

    redirect('sewa/selesai');
  }

  public function selesai()
  {

    $this->load->view('selesai_view');
  }

  public function tutup()
  {
    $this->load->helper('url');
    echo "<script>
    alert('Pembayaran Berhasil');
    window.location.href = '" . base_url() . "';// your redirect path here
</script>";
    $this->cart->destroy();
    // redirect('home');
  }
  public function back($kode, $produk_id, $data)
  {

    if ($kode != $produk_id) {
      $this->load->helper('url');
      echo "<script>
      alert('Kode Motor Salah');
      window.location.href = '" . base_url() . "produk';// your redirect path here
  </script>";
    } else {
      $in = $this->cart->insert($data);
      if ($in) {
        redirect('sewa');
      }
    }

    // redirect('home');
  }
}
