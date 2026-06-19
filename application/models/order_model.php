<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function get_couriers()
    {
        $rows = $this->db->get('couriers')->result_array();
        return array_map(function ($r) {
            return [
                'id'       => $r['code'],
                'label'    => $r['name'],
                'estimasi' => $r['estimasi'],
                'harga'    => (float) $r['price'],
            ];
        }, $rows);
    }

    public function check_promo($code)
    {
        $code = strtoupper(trim($code));
        if ($code === '') return null;

        return $this->db->get_where('promo_codes', ['code' => $code, 'is_active' => 1])->row_array();
    }

    public function create($data, $items)
    {
        $this->db->insert('orders', $data);
        $order_id = $this->db->insert_id();

        foreach ($items as $item) {
            $this->db->insert('order_items', [
                'order_id'   => $order_id,
                'product_id' => $item['id'],
                'title'      => $item['title'],
                'image'      => $item['image'],
                'price'      => $item['price'],
                'qty'        => $item['qty'],
            ]);
        }
        return $order_id;
    }

    public function get_user_orders($user_id)
    {
        $this->db->select('orders.*, couriers.name as courier_name');
        $this->db->from('orders');
        $this->db->join('couriers', 'couriers.id = orders.courier_id', 'left');
        $this->db->where('orders.user_id', $user_id);
        $this->db->order_by('orders.created_at', 'DESC');
        $orders = $this->db->get()->result_array();

        foreach ($orders as &$order) {
            $order['items'] = $this->db->where('order_id', $order['id'])->get('order_items')->result_array();
            $order['returns'] = $this->db->where('order_id', $order['id'])->order_by('id', 'DESC')->get('returns')->result_array();
        }

        return $orders;
    }
}
