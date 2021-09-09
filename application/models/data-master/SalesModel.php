<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesModel extends Render_Model
{


    public function getAllData()
    {
        $exe                         = $this->db->select('*')
            ->join('level c', 'c.lev_id = a.role_lev_id', 'left')
            ->join('users b', 'b.user_id = a.role_user_id', 'left')
            ->where('c.lev_id', '6')
            ->get('role_users a');

        return $exe->result_array();
    }


    public function getDataDetail($id)
    {
        $exe                         = $this->db->select('*')
            ->join('users b', 'b.user_id = a.role_user_id', 'left')
            ->where('a.role_user_id', $id)
            ->get('role_users a');

        return $exe->row_array();
    }


    public function getDataLevel()
    {
        $exe                         = $this->db->get('level');

        return $exe->result_array();
    }


    public function insert($level, $nama, $telepon, $username, $password, $status)
    {
        $data['user_nama']             = $nama;
        $data['user_email']         = $username;
        $data['user_password']         = $this->b_password->bcrypt_hash($password);
        $data['user_phone']         = $telepon;
        $data['user_status']         = $status;

        // Insert users
        $execute                     = $this->db->insert('users', $data);
        $execute                     = $this->db->insert_id();

        $data2['role_user_id']         = $execute;
        $data2['role_lev_id']         = $level;

        // Insert role users
        $execute2                     = $this->db->insert('role_users', $data2);

        $exe['id']                     = $execute;
        $exe['level']                 = $this->_cek('level', 'lev_id', $level, 'lev_nama');

        return $exe;
    }


    public function update($id, $level, $nama, $telepon, $username, $password)
    {
        $data['user_nama']             = $nama;
        $data['user_email']         = $username;
        $data['user_phone']         = $telepon;
        $data['updated_at']         = Date("Y-m-d H:i:s", time());
        if ($password != '') {
            $data['user_password']         = $this->b_password->bcrypt_hash($password);
        }

        // Update users
        $execute                     = $this->db->where('user_id', $id);
        $execute                     = $this->db->update('users', $data);

        $data2['role_user_id']         = $id;
        $data2['role_lev_id']         = $level;

        // Update role users
        $execute2                     = $this->db->where('role_user_id', $id);
        $execute2                      = $this->db->update('role_users', $data2);

        $exe['id']                     = $id;
        $exe['level']                 = $this->_cek('level', 'lev_id', $level, 'lev_nama');

        return $exe;
    }


    public function delete($id)
    {
        // Delete users
        // $exe                         = $this->db->where('user_id', $id);
        // $exe                         = $this->db->delete('users');
        $data['user_status'] = 0;
        $data['updated_at'] = Date("Y-m-d H:i:s", time());
        $data['deleted_at'] = Date("Y-m-d H:i:s", time());
        $exe = $this->db->where('user_id', $id);
        $exe = $this->db->update('users', $data);

        // Delete role users
        // $exe2                         = $this->db->where('role_user_id', $id);
        // $exe2                         = $this->db->delete('role_users');

        return $exe;
    }

    public function getAllIsiLaporExport()
    {
        $this->db->select('*');
        $this->db->from('role_users a');
        $this->db->join('level c', 'c.lev_id = a.role_lev_id', 'left');
        $this->db->join('users b', 'b.user_id = a.role_user_id', 'left');
        $this->db->where('c.lev_id', '6');

        $return = $this->db->get()->result_array();
        return $return;
    }
}

/* End of file List_salesModel.php */
/* Location: ./application/models/sales/List_salesModel.php */