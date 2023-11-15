<?php


function is_logged_in() {

    $data = get_instance();

    if(!$data->session->userdata('email')) {
        redirect('auth');
    } else {

        $role_id = $data->session->userdata('role_id');
        $menu = $data->uri->segment(1);


        $query = $data->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $query['id'];

        $queryAccess = $data->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
            
        ]);

        if($queryAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }

    }
}



function check_access($role_id, $menu_id) {

    $data = get_instance();

    $data->db->where('role_id', $role_id);
    $data->db->where('menu_id', $menu_id);

    $result = $data->db->get('user_access_menu');

    if($result->num_rows() > 0) {
        return "checked='checked'";
    }
}