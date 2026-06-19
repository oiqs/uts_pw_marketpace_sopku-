<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    public function get_all($category = null, $keyword = null, $max_price = null, $sort = null)
    {
        $this->db->select('products.*, categories.name as category');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');

        if ($category && $category !== 'all') {
            $this->db->where('categories.name', $category);
        }
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('products.title', $keyword);
            $this->db->or_like('products.description', $keyword);
            $this->db->or_like('categories.name', $keyword);
            $this->db->group_end();
        }
        if ($max_price !== null && $max_price !== '') {
            $this->db->where('products.price <=', $max_price);
        }

        switch ($sort) {
            case 'price-asc':  $this->db->order_by('products.price', 'ASC'); break;
            case 'price-desc': $this->db->order_by('products.price', 'DESC'); break;
            case 'rating':     $this->db->order_by('products.rating_rate', 'DESC'); break;
            case 'popular':    $this->db->order_by('products.rating_count', 'DESC'); break;
            default:           $this->db->order_by('products.id', 'ASC');
        }

        $rows = $this->db->get()->result_array();
        return array_map([$this, 'format_row'], $rows);
    }

    public function get_by_id($id)
    {
        $this->db->select('products.*, categories.name as category');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.id', $id);
        $row = $this->db->get()->row_array();
        return $row ? $this->format_row($row) : null;
    }

    public function get_categories()
    {
        $rows = $this->db->select('name')->get('categories')->result_array();
        return array_column($rows, 'name');
    }

    // Samakan struktur JSON dengan https://fakestoreapi.com/products
    private function format_row($row)
    {
        return [
            'id'          => (int) $row['id'],
            'title'       => $row['title'],
            'description' => $row['description'],
            'price'       => (float) $row['price'],
            'image'       => $row['image'],
            'category'    => $row['category'],
            'rating'      => [
                'rate'  => (float) $row['rating_rate'],
                'count' => (int) $row['rating_count'],
            ],
            'stock'       => (int) $row['stock'],
        ];
    }
}