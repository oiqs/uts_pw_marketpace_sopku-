<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('cart_model');
        $this->load->model('user_model');
        $this->load->model('order_model');
        $this->load->model('return_model');
        $this->load->model('review_model');

        // Izinkan diakses dari React via axios (sama origin, tetap diset agar aman)
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    private function json($data, $code = 200)
    {
        $this->output->set_status_header($code);
        echo json_encode($data);
    }

    // Ambil body JSON dari axios.post, fallback ke $_POST
    private function body()
    {
        $raw = trim($this->input->raw_input_stream);
        if ($raw !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) return $decoded;
        }
        return $this->input->post() ?: [];
    }

    /* ===================== PRODUCTS ===================== */

    public function products()
    {
        $category  = $this->input->get('category');
        $keyword   = $this->input->get('keyword');
        $max_price = $this->input->get('max_price');
        $sort      = $this->input->get('sort');
        $limit     = $this->input->get('limit');

        $data = $this->product_model->get_all($category, $keyword, $max_price, $sort);
        if ($limit) {
            $data = array_slice($data, 0, (int) $limit);
        }
        $this->json($data);
    }

    public function product($id)
    {
        $product = $this->product_model->get_by_id($id);
        $product ? $this->json($product) : $this->json(['message' => 'Produk tidak ditemukan'], 404);
    }

    public function categories()
    {
        $this->json($this->product_model->get_categories());
    }

    /* ===================== REVIEWS ===================== */

    public function reviews($product_id)
    {
        $this->json($this->review_model->get_by_product((int) $product_id));
    }

    public function review_add()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->json(['success' => false, 'message' => 'Silakan login terlebih dahulu'], 401);
            return;
        }

        $in = $this->body();
        if (empty($in['product_id']) || empty($in['rating'])) {
            $this->json(['success' => false, 'message' => 'Rating produk wajib diisi'], 400);
            return;
        }

        $this->review_model->add(
            (int) $in['product_id'],
            $user_id,
            (int) $in['rating'],
            $in['comment'] ?? ''
        );

        $this->json(['success' => true]);
    }

    /* ===================== CART ===================== */

    public function cart()
    {
        $this->json($this->cart_model->get_items());
    }

    public function cart_add()
    {
        $in = $this->body();
        $this->cart_model->add((int) $in['product_id'], (int) ($in['qty'] ?? 1));
        $this->json($this->cart_model->get_items());
    }

    public function cart_update()
    {
        $in = $this->body();
        if (isset($in['qty'])) {
            $this->cart_model->update_qty((int) $in['product_id'], (int) $in['qty']);
        }
        if (isset($in['selected'])) {
            $this->cart_model->update_selected((int) $in['product_id'], (bool) $in['selected']);
        }
        $this->json($this->cart_model->get_items());
    }

    public function cart_remove($product_id)
    {
        $this->cart_model->remove((int) $product_id);
        $this->json($this->cart_model->get_items());
    }

    public function cart_clear()
    {
        $this->cart_model->clear();
        $this->json([]);
    }

    /* ===================== AUTH ===================== */

    public function login()
    {
        $in   = $this->body();
        $user = $this->user_model->verify($in['email'] ?? '', $in['password'] ?? '');

        if ($user) {
            $this->session->set_userdata('user_id', $user['id']);
            $this->session->set_userdata('user_name', $user['name']);
            $this->session->set_userdata('user_email', $user['email']);
            $this->session->set_userdata('user_role', $user['role'] ?? 'user');
            $this->cart_model->merge_guest_cart_to_user($user['id']);

            $this->json([
                'success' => true,
                'user' => $user,
                'redirect_url' => (($user['role'] ?? 'user') === 'admin')
                    ? base_url('admin/dashboard')
                    : base_url('login'),
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Email atau password salah'], 401);
        }
    }

    public function register()
    {
        $in = $this->body();
        if (empty($in['nama']) || empty($in['email']) || empty($in['password'])) {
            $this->json(['success' => false, 'message' => 'Semua field wajib diisi'], 400);
            return;
        }
        if ($this->user_model->find_by_email($in['email'])) {
            $this->json(['success' => false, 'message' => 'Email sudah terdaftar'], 409);
            return;
        }
        $this->user_model->create($in['nama'], $in['email'], $in['password']);
        $this->json(['success' => true]);
    }

    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'user_name', 'user_email', 'user_role']);
        $this->json(['success' => true]);
    }

    public function me()
    {
        $user_id = $this->session->userdata('user_id');
        if ($user_id) {
            $this->json([
                'logged_in' => true,
                'id'        => $user_id,
                'name'      => $this->session->userdata('user_name'),
                'email'     => $this->session->userdata('user_email'),
                'role'      => $this->session->userdata('user_role') ?: 'user',
            ]);
        } else {
            $this->json(['logged_in' => false]);
        }
    }

    /* ===================== CHECKOUT ===================== */

    public function couriers()
    {
        $this->json($this->order_model->get_couriers());
    }

    public function promo_check()
    {
        $in    = $this->body();
        $promo = $this->order_model->check_promo($in['code'] ?? '');

        $promo
            ? $this->json(['valid' => true, 'discount_percent' => (float) $promo['discount_percent']])
            : $this->json(['valid' => false]);
    }

    public function checkout()
    {
        $in    = $this->body();
        $items = array_values(array_filter($this->cart_model->get_items(), fn($i) => $i['selected']));

        if (empty($items)) {
            $this->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
            return;
        }

        $subtotal = array_reduce($items, fn($sum, $item) => $sum + ((float) $item['price'] * (int) $item['qty']), 0);
        $discount = $subtotal > 3000000 ? $subtotal * 0.05 : 0;

        $order_code = 'SKU-' . strtoupper(base_convert((string) time(), 10, 36));

        $courier_id = null;
        $shipping_cost = 0;
        if (!empty($in['courier_code'])) {
            $courier = $this->db->get_where('couriers', ['code' => $in['courier_code']])->row_array();
            $courier_id = $courier ? $courier['id'] : null;
            $shipping_cost = $courier ? (float) $courier['price'] : 0;
        }

        $promo_code = strtoupper(trim($in['promo_code'] ?? ''));
        $promo = $this->order_model->check_promo($promo_code);
        $promo_discount = $promo ? $subtotal * ((float) $promo['discount_percent'] / 100) : 0;
        $total = max(0, $subtotal - $discount - $promo_discount + $shipping_cost);

        $order_data = [
            'order_code'     => $order_code,
            'user_id'        => $this->session->userdata('user_id'),
            'nama'           => $in['nama'],
            'telepon'        => $in['telepon'],
            'email'          => $in['email'] ?? null,
            'alamat'         => $in['alamat'],
            'kota'           => $in['kota'],
            'kodepos'        => $in['kodepos'],
            'catatan'        => $in['catatan'] ?? null,
            'courier_id'     => $courier_id,
            'payment_method' => $in['payment_method'],
            'payment_detail' => $in['payment_detail'] ?? null,
            'promo_code'     => $promo ? $promo['code'] : null,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'promo_discount' => $promo_discount,
            'shipping_cost'  => $shipping_cost,
            'total'          => $total,
        ];

        $this->order_model->create($order_data, $items);
        $this->cart_model->clear();

        $this->json(['success' => true, 'order_code' => $order_code]);
    }

    public function orders()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->json(['success' => false, 'message' => 'Silakan login terlebih dahulu'], 401);
            return;
        }

        $this->json(['success' => true, 'orders' => $this->order_model->get_user_orders($user_id)]);
    }

    public function return_create()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->json(['success' => false, 'message' => 'Silakan login terlebih dahulu'], 401);
            return;
        }

        $in = $this->body();
        if (empty($in['order_id']) || empty($in['reason']) || empty($in['items'])) {
            $this->json(['success' => false, 'message' => 'Data return belum lengkap'], 400);
            return;
        }

        $order = $this->db->get_where('orders', [
            'id' => (int) $in['order_id'],
            'user_id' => $user_id,
        ])->row_array();

        if (!$order) {
            $this->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
            return;
        }

        if (!in_array($order['status'], ['dikirim', 'selesai'], true)) {
            $this->json(['success' => false, 'message' => 'Return hanya bisa diajukan untuk pesanan yang dikirim atau selesai'], 422);
            return;
        }

        $return_id = $this->return_model->create([
            'order_id' => $order['id'],
            'user_id' => $user_id,
            'reason' => $in['reason'],
            'description' => $in['description'] ?? '',
            'evidence_image' => $in['evidence_image'] ?? '',
        ], $in['items']);

        if (!$return_id) {
            $this->json(['success' => false, 'message' => 'Gagal mengajukan return'], 500);
            return;
        }

        $return = $this->db->get_where('returns', ['id' => $return_id])->row_array();
        $this->json(['success' => true, 'return_code' => $return['return_code']]);
    }
}
