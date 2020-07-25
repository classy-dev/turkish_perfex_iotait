<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_pricing_category_by_permission($id)
    {
    	if(is_admin())
    	{
    		$this->db->order_by('id', 'asc');
        	return $this->db->get(db_prefix() . 'pricing_categories')->result_array();
    	} else {
    		$this->db->where('staff_id',$id);
    		$per = $this->db->get(db_prefix() . 'price_category_permission')->result_array();
    		if(!empty($per))
    			$price_id_arr =json_decode($per[0]['price_category_id']);
    		
    		$data_arr = [];
    		foreach ($price_id_arr as $key => $value) {
    			$this->db->where('id', $value);
    			$data = $this->db->get(db_prefix() . 'pricing_categories')->result_array();
    			array_push($data_arr, $data[0]);
    		}
    		return $data_arr;
    	}
    }

    public function add_product_recipe_item($data)
    {
        $rel_product_id = $data['rel_product_id'];
        unset($data['rel_product_id']);
        foreach ($data as $val) {
            unset($val['item_id']);
            $val['rel_product_id'] = $rel_product_id;
            $this->db->insert(db_prefix() . 'product_recipe', $val);
            $insert_id = $this->db->insert_id();
        }
        return $insert_id;
    }

    public function update_product_recipe_item($data)
    {
        $rel_product_id = $data['rel_product_id'];
        unset($data['rel_product_id']);

        if(isset($data['newitems']))
        {
            $newitems = $data['newitems'];
            foreach ($newitems as $key => $item) {
                unset($item['item_id']);
                $item['rel_product_id'] = $rel_product_id;
                $this->db->insert(db_prefix() . 'product_recipe', $val);
                $insert_id = $this->db->insert_id();
            }
        }
        if(isset($data['items'])){
            $items = $data['items'];
            foreach ($items as $key => $item) {
                $id = $item['item_id'];
                unset($item['item_id']);
                $this->db->where('id',$id);
                $this->db->update(db_prefix().'product_recipe',$item);
            }
        }
        if(isset($data['removed_items'])){
            $removed_items = $data['removed_items'];
            foreach ($removed_items as $val) {
                $this->db->where('id',$val);
                $this->db->delete(db_prefix() . 'product_recipe');
            }
        }

    }

    public function get_product_receipe_item($productid)
    {
        $this->db->from(db_prefix().'product_recipe');
        $this->db->where('rel_product_id',$productid);
        return $this->db->get()->result_array();
    }

    public function get_pack_by_product_code($id)
    {
    	$this->db->from(db_prefix() . 'pack_list');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'pack_list.stock_product_code', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function get_pricing_calc($productid)
    {
        $this->db->from(db_prefix().'pricing_calculation');
        $this->db->where('rel_product_id',$productid);
        return $this->db->get()->row();
    }

    public function add_pricing_calc($data)
    {
        $this->db->insert(db_prefix().'pricing_calculation',$data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Pricing Calculation Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_pricing_calc($data, $id)
    {
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pricing_calculation',$data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Pricing Calculation Updated [' . $id . ']');
            return true;
        }
        return false;
    }

    public function get_install_time($productid)
    {
        $this->db->from(db_prefix().'installation_time');
        $this->db->where('rel_product_id',$productid);
        return $this->db->get()->row();
    }

    public function add_install_time($data)
    {
        $this->db->insert(db_prefix().'installation_time',$data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Install Time Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_install_time($data, $id)
    {
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'installation_time',$data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Install Time Updated [' . $id . ']');
            return true;
        }
        return false;
    }

}