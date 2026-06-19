<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('user_model');
    }

    public function login()
    {
        if ($this->session->userdata('user_role') === 'admin') {
            redirect('admin/dashboard');
        }

        if ($this->input->method() === 'post') {
            $user = $this->user_model->verify(
                $this->input->post('email', true),
                $this->input->post('password', true)
            );

            if ($user) {
                $role = $user['role'] ?? 'user';
                $this->session->set_userdata([
                    'user_id'    => $user['id'],
                    'user_name'  => $user['name'],
                    'user_email' => $user['email'],
                    'user_role'  => $role,
                ]);

                if ($role === 'admin') {
                    redirect('admin/dashboard');
                }

                redirect('login');
            }

            $this->session->set_flashdata('error', 'Email atau password salah.');
            redirect('admin/login');
        }

        $this->load->view('admin/login');
    }

    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'user_name', 'user_email', 'user_role']);
        $this->session->set_flashdata('success', 'Anda sudah keluar dari panel admin.');
        redirect('admin/login');
    }

    public function dashboard()
    {
        $this->require_admin();
        $this->render('admin/dashboard', [
            'title' => 'Dashboard',
            'stats' => $this->admin_model->counts(),
            'latest_orders' => $this->admin_model->latest_orders(),
        ]);
    }

    public function products()
    {
        $this->require_admin();
        $this->render('admin/products', [
            'title' => 'Produk',
            'products' => $this->admin_model->products(),
        ]);
    }

    public function product_create()
    {
        $this->require_admin();
        if ($this->input->method() === 'post') {
            $this->admin_model->save_product($this->input->post());
            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
            redirect('admin/products');
        }
        $this->render('admin/product_form', [
            'title' => 'Tambah Produk',
            'product' => null,
            'categories' => $this->admin_model->categories(),
        ]);
    }

    public function product_edit($id)
    {
        $this->require_admin();
        if ($this->input->method() === 'post') {
            $this->admin_model->save_product($this->input->post(), $id);
            $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
            redirect('admin/products');
        }
        $this->render('admin/product_form', [
            'title' => 'Edit Produk',
            'product' => $this->admin_model->product($id),
            'categories' => $this->admin_model->categories(),
        ]);
    }

    public function product_delete($id)
    {
        $this->require_admin();
        $this->admin_model->delete_product($id);
        $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        redirect('admin/products');
    }

    public function orders()
    {
        $this->require_admin();
        $this->render('admin/orders', [
            'title' => 'Order',
            'orders' => $this->admin_model->orders(),
        ]);
    }

    public function order_detail($id)
    {
        $this->require_admin();
        $this->render('admin/order_detail', [
            'title' => 'Detail Order',
            'order' => $this->admin_model->order($id),
            'items' => $this->admin_model->order_items($id),
        ]);
    }

    public function order_status($id)
    {
        $this->require_admin();
        $this->admin_model->update_order_status($id, $this->input->post('status', true));
        $this->session->set_flashdata('success', 'Status order berhasil diperbarui.');
        redirect('admin/orders/' . $id);
    }

    public function returns()
    {
        $this->require_admin();
        $this->render('admin/returns', [
            'title' => 'Return',
            'returns' => $this->admin_model->returns(),
        ]);
    }

    public function return_detail($id)
    {
        $this->require_admin();
        $detail = $this->admin_model->return_detail($id);
        $this->render('admin/return_detail', [
            'title' => 'Detail Return',
            'return' => $detail['return'],
            'items' => $detail['items'],
        ]);
    }

    public function return_status($id)
    {
        $this->require_admin();
        $this->admin_model->update_return_status(
            $id,
            $this->input->post('status', true),
            $this->input->post('admin_note', true)
        );
        $this->session->set_flashdata('success', 'Status return berhasil diperbarui.');
        redirect('admin/returns/' . $id);
    }

    public function users()
    {
        $this->require_admin();
        $this->render('admin/users', [
            'title' => 'User',
            'users' => $this->admin_model->users(),
        ]);
    }

    public function user_delete($id)
    {
        $this->require_admin();
        if ((int) $id === (int) $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Akun admin yang sedang dipakai tidak bisa dihapus.');
            redirect('admin/users');
        }
        $this->admin_model->delete_user($id);
        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('admin/users');
    }

    public function categories()
    {
        $this->require_admin();
        if ($this->input->method() === 'post') {
            $this->admin_model->save_category($this->input->post('name', true), $this->input->post('id', true));
            $this->session->set_flashdata('success', 'Kategori berhasil disimpan.');
            redirect('admin/categories');
        }
        $this->render('admin/categories', [
            'title' => 'Kategori',
            'categories' => $this->admin_model->categories(),
        ]);
    }

    public function category_create()
    {
        $this->categories();
    }

    public function category_delete($id)
    {
        $this->require_admin();
        $this->admin_model->delete_category($id);
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        redirect('admin/categories');
    }

    public function couriers()
    {
        $this->require_admin();
        if ($this->input->method() === 'post') {
            $this->admin_model->save_courier($this->input->post(), $this->input->post('id', true));
            $this->session->set_flashdata('success', 'Kurir berhasil disimpan.');
            redirect('admin/couriers');
        }
        $this->render('admin/couriers', [
            'title' => 'Kurir',
            'couriers' => $this->admin_model->couriers(),
        ]);
    }

    public function courier_delete($id)
    {
        $this->require_admin();
        $this->admin_model->delete_courier($id);
        $this->session->set_flashdata('success', 'Kurir berhasil dihapus.');
        redirect('admin/couriers');
    }

    public function promos()
    {
        $this->require_admin();
        if ($this->input->method() === 'post') {
            $this->admin_model->save_promo($this->input->post(), $this->input->post('id', true));
            $this->session->set_flashdata('success', 'Promo berhasil disimpan.');
            redirect('admin/promos');
        }
        $this->render('admin/promos', [
            'title' => 'Promo',
            'promos' => $this->admin_model->promos(),
        ]);
    }

    public function promo_create()
    {
        $this->promos();
    }

    public function promo_delete($id)
    {
        $this->require_admin();
        $this->admin_model->delete_promo($id);
        $this->session->set_flashdata('success', 'Promo berhasil dihapus.');
        redirect('admin/promos');
    }

    public function reviews()
    {
        $this->require_admin();
        $this->render('admin/reviews', [
            'title' => 'Ulasan Produk',
            'reviews' => $this->admin_model->reviews(),
        ]);
    }

    public function review_delete($id)
    {
        $this->require_admin();
        $this->admin_model->delete_review($id);
        $this->session->set_flashdata('success', 'Ulasan berhasil dihapus.');
        redirect('admin/reviews');
    }

    private function require_admin()
    {
        if ($this->session->userdata('user_role') !== 'admin') {
            redirect('admin/login');
        }
    }

    private function render($view, $data = [])
    {
        $this->load->view('admin/header', $data);
        $this->load->view($view, $data);
        $this->load->view('admin/footer');
    }
}
