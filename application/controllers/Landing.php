<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

    public function index() {
        $rt = $this->db->get_where('setting', ['kunci' => 'running_text'])->row();
        $data['running_text'] = $rt ? $rt->nilai : 'Selamat Datang di BannerPosko';
        $data['banners'] = $this->db->get_where('banner', ['status' => 'aktif'])->result();
        $this->load->view('landing', $data);
    }

    public function preview($id) {
        $rt = $this->db->get_where('setting', ['kunci' => 'running_text'])->row();
        $data['running_text'] = $rt ? $rt->nilai : 'Selamat Datang di BannerPosko';
        $data['banner'] = $this->db->get_where('banner', ['id' => $id])->row();
        $this->load->view('landing_preview', $data);
    }
}