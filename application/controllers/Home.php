<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // url & session sudah di-autoload (lihat application/config/autoload.php)
    }

    public function index()
    {
        $this->load->view('home');
    }

    public function katalog()
    {
        $this->load->view('katalog');
    }

    public function detail($id = 1)
    {
        $data['product_id'] = $id;
        $this->load->view('detail', $data);
    }

    public function cart()
    {
        $this->load->view('cart');
    }

    public function login()
    {
        $this->load->view('login');
    }

    public function checkout()
    {
        $this->load->view('checkout');
    }

    public function orders()
    {
        $this->load->view('orders');
    }

    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'user_name', 'user_email', 'user_role']);
        redirect('home');
    }
}
