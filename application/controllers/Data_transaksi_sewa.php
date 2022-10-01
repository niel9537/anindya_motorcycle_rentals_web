<?php

/**
 *
 */
class Data_transaksi_sewa extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $data['transaksi'] = $this->all_model->get_transaksi_sewa(array());
    $this->load->view('admin/data_transaksi_sewa_view', $data);
  }

  public function setuju($idt, $idp)
  {
    $transaksi = $this->all_model->get_transaksi_sewa(array('transaksi.transaksi_id' => $idt));
    $produk    = $this->all_model->get_transaksi_sewa(array('produk.produk_id' => $idp));
    if ($this->all_model->update(array('transaksi_id' => $idt), array('status' => '1'), 'transaksi')) {
      $stok = $produk[0]->stok - $transaksi[0]->jumlah;
      $dup = array(
        'stok' => $stok
      );
      $up = $this->all_model->update(array('produk_id' => $idp), $dup, 'produk');
      if ($up) {
        redirect('data_transaksi_sewa');
      }
    }
  }

  public function kembali($idt, $idp, $tgl_sampai, $harga)
  {
    $tgl_estimasi = date("Y-m-d");
    $diff = abs(strtotime($tgl_sampai) - strtotime($tgl_estimasi));
    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $denda = 0;
    if ($days > 1) {
      $denda = $days * 20000;
    }
    $transaksi = $this->all_model->get_transaksi_sewa(array('transaksi.transaksi_id' => $idt));
    $produk    = $this->all_model->get_transaksi_sewa(array('produk.produk_id' => $idp));
    if ($this->all_model->update(array('transaksi_id' => $idt), array('status' => '2'), 'transaksi')) {
      $stok = $produk[0]->stok + $transaksi[0]->jumlah;
      $dup = array(
        'stok' => $stok
      );
      $up = $this->all_model->update(array('produk_id' => $idp), $dup, 'produk');
      if ($up) {
        if ($denda > 0) {
          $this->all_model->insertdenda($denda, $idt);
        }
        redirect('data_transaksi_sewa/');
      }
    }
  }
}
