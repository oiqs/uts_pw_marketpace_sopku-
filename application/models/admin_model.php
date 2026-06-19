<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function counts()
    {
        $revenue = $this->db->select_sum('total')
            ->where('status !=', 'batal')
            ->get('orders')
            ->row()
            ->total;

        return [
            'products' => $this->db->count_all('products'),
            'orders'   => $this->db->count_all('orders'),
            'users'    => $this->db->where('role', 'user')->count_all_results('users'),
            'revenue'  => (float) ($revenue ?: 0),
        ];
    }

    public function latest_orders($limit = 5)
    {
        return $this->orders_query()
            ->limit($limit)
            ->get()
            ->result_array();
    }

    public function products()
    {
        return $this->db->select('products.*, categories.name as category')
            ->from('products')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->order_by('products.id', 'DESC')
            ->get()
            ->result_array();
    }

    public function product($id)
    {
        return $this->db->get_where('products', ['id' => $id])->row_array();
    }

    public function save_product($data, $id = null)
    {
        $payload = [
            'category_id'   => $data['category_id'] ?: null,
            'title'         => trim($data['title']),
            'description'   => trim($data['description']),
            'price'         => (float) $data['price'],
            'image'         => trim($data['image']),
            'rating_rate'   => (float) ($data['rating_rate'] ?: 0),
            'rating_count'  => (int) ($data['rating_count'] ?: 0),
            'stock'         => (int) ($data['stock'] ?: 0),
        ];

        if ($id) {
            return $this->db->where('id', $id)->update('products', $payload);
        }
        return $this->db->insert('products', $payload);
    }

    public function delete_product($id)
    {
        return $this->db->where('id', $id)->delete('products');
    }

    public function categories()
    {
        return $this->db->order_by('name', 'ASC')->get('categories')->result_array();
    }

    public function save_category($name, $id = null)
    {
        $payload = ['name' => trim($name)];
        if ($id) {
            return $this->db->where('id', $id)->update('categories', $payload);
        }
        return $this->db->insert('categories', $payload);
    }

    public function delete_category($id)
    {
        return $this->db->where('id', $id)->delete('categories');
    }

    public function couriers()
    {
        return $this->db->order_by('id', 'ASC')->get('couriers')->result_array();
    }

    public function save_courier($data, $id = null)
    {
        $payload = [
            'code'     => trim($data['code']),
            'name'     => trim($data['name']),
            'estimasi' => trim($data['estimasi']),
            'price'    => (float) $data['price'],
        ];

        if ($id) {
            return $this->db->where('id', $id)->update('couriers', $payload);
        }
        return $this->db->insert('couriers', $payload);
    }

    public function delete_courier($id)
    {
        return $this->db->where('id', $id)->delete('couriers');
    }

    public function promos()
    {
        return $this->db->order_by('id', 'DESC')->get('promo_codes')->result_array();
    }

    public function save_promo($data, $id = null)
    {
        $payload = [
            'code'             => strtoupper(trim($data['code'])),
            'discount_percent' => (float) $data['discount_percent'],
            'is_active'        => !empty($data['is_active']) ? 1 : 0,
        ];

        if ($id) {
            return $this->db->where('id', $id)->update('promo_codes', $payload);
        }
        return $this->db->insert('promo_codes', $payload);
    }

    public function delete_promo($id)
    {
        return $this->db->where('id', $id)->delete('promo_codes');
    }

    public function users()
    {
        return $this->db->select('id, name, email, role, created_at')
            ->order_by('id', 'DESC')
            ->get('users')
            ->result_array();
    }

    public function delete_user($id)
    {
        return $this->db->where('id', $id)->where('role !=', 'admin')->delete('users');
    }

    public function orders()
    {
        return $this->orders_query()->get()->result_array();
    }

    public function order($id)
    {
        return $this->orders_query()->where('orders.id', $id)->get()->row_array();
    }

    public function order_items($order_id)
    {
        return $this->db->where('order_id', $order_id)->get('order_items')->result_array();
    }

    public function update_order_status($id, $status)
    {
        $allowed = ['pending', 'diproses', 'dikirim', 'selesai', 'batal'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        return $this->db->where('id', $id)->update('orders', ['status' => $status]);
    }

    public function returns()
    {
        $this->load->model('return_model');
        return $this->return_model->all();
    }

    public function return_detail($id)
    {
        $this->load->model('return_model');
        return [
            'return' => $this->return_model->find($id),
            'items'  => $this->return_model->items($id),
        ];
    }

    public function update_return_status($id, $status, $note = '')
    {
        $this->load->model('return_model');
        return $this->return_model->update_status($id, $status, $note);
    }

    private function orders_query()
    {
        return $this->db->select('orders.*, users.name as user_name, couriers.name as courier_name')
            ->from('orders')
            ->join('users', 'users.id = orders.user_id', 'left')
            ->join('couriers', 'couriers.id = orders.courier_id', 'left')
            ->order_by('orders.created_at', 'DESC');
    }

    public function reviews()
    {
        return $this->db->select('reviews.*, products.title as product_title, users.name as user_name')
            ->from('reviews')
            ->join('products', 'products.id = reviews.product_id', 'left')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->order_by('reviews.created_at', 'DESC')
            ->get()
            ->result_array();
    }

    public function delete_review($id)
    {
        // Get product_id before deleting
        $review = $this->db->get_where('reviews', ['id' => $id])->row();
        if ($review) {
            $this->db->where('id', $id)->delete('reviews');
            
            // Recalculate product rating
            $this->db->select_avg('rating', 'avg_rating');
            $this->db->select('COUNT(*) as total_reviews');
            $this->db->where('product_id', $review->product_id);
            $result = $this->db->get('reviews')->row();

            $avg_rating = round($result->avg_rating ?: 0, 1);
            $total_reviews = $result->total_reviews;

            $this->db->where('id', $review->product_id);
            $this->db->update('products', [
                'rating_rate' => $avg_rating,
                'rating_count' => $total_reviews
            ]);
            return true;
        }
        return false;
    }
}
