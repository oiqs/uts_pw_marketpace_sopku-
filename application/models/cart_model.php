<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model
{
    // Identitas pemilik cart: user_id (jika login) atau session_id (guest)
    private function owner()
    {
        $user_id = $this->session->userdata('user_id');
        if ($user_id) {
            return ['user_id' => $user_id, 'session_id' => null];
        }

        $token = $this->session->userdata('cart_token');
        if (!$token) {
            $token = bin2hex(random_bytes(16));
            $this->session->set_userdata('cart_token', $token);
        }
        return ['user_id' => null, 'session_id' => $token];
    }

    private function where_owner()
    {
        $owner = $this->owner();
        return $owner['user_id']
            ? ['user_id' => $owner['user_id']]
            : ['session_id' => $owner['session_id']];
    }

    public function get_items()
    {
        $this->db->select('cart_items.*, products.title, products.price, products.image, categories.name as category');
        $this->db->from('cart_items');
        $this->db->join('products', 'products.id = cart_items.product_id');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where($this->where_owner());
        $rows = $this->db->get()->result_array();

        return array_map(function ($r) {
            return [
                'id'       => (int) $r['product_id'],
                'cart_id'  => (int) $r['id'],
                'title'    => $r['title'],
                'price'    => (float) $r['price'],
                'image'    => $r['image'],
                'category' => $r['category'],
                'qty'      => (int) $r['qty'],
                'selected' => (bool) $r['selected'],
            ];
        }, $rows);
    }

    public function add($product_id, $qty = 1)
    {
        $where = $this->where_owner();
        $where['product_id'] = $product_id;

        $existing = $this->db->get_where('cart_items', $where)->row();
        if ($existing) {
            $this->db->where('id', $existing->id)
                     ->update('cart_items', ['qty' => $existing->qty + $qty]);
        } else {
            $this->db->insert('cart_items', array_merge($where, ['qty' => $qty, 'selected' => 1]));
        }
    }

    public function update_qty($product_id, $qty)
    {
        $where = $this->where_owner();
        $where['product_id'] = $product_id;
        $this->db->where($where)->update('cart_items', ['qty' => max(1, (int) $qty)]);
    }

    public function update_selected($product_id, $selected)
    {
        $where = $this->where_owner();
        $where['product_id'] = $product_id;
        $this->db->where($where)->update('cart_items', ['selected' => $selected ? 1 : 0]);
    }

    public function remove($product_id)
    {
        $where = $this->where_owner();
        $where['product_id'] = $product_id;
        $this->db->where($where)->delete('cart_items');
    }

    public function clear()
    {
        $this->db->where($this->where_owner())->delete('cart_items');
    }

    // Dipanggil saat user login: pindahkan cart guest -> cart user
    public function merge_guest_cart_to_user($user_id)
    {
        $token = $this->session->userdata('cart_token');
        if (!$token) return;

        $guest_items = $this->db->get_where('cart_items', ['session_id' => $token])->result_array();
        foreach ($guest_items as $gi) {
            $existing = $this->db->get_where('cart_items', [
                'user_id' => $user_id, 'product_id' => $gi['product_id']
            ])->row();

            if ($existing) {
                $this->db->where('id', $existing->id)
                         ->update('cart_items', ['qty' => $existing->qty + $gi['qty']]);
                $this->db->where('id', $gi['id'])->delete('cart_items');
            } else {
                $this->db->where('id', $gi['id'])->update('cart_items', [
                    'user_id' => $user_id, 'session_id' => null
                ]);
            }
        }
    }
}