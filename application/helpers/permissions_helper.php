<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Check if the logged-in user has permission for a specific module and action.
 *
 * @param string $module (invoices, quotes, clients, payments, products, projects, tasks, reports, settings, users, roles)
 * @param string $action (view, create, edit, delete)
 * @return bool
 */
function has_permission(string $module, string $action = 'view'): bool
{
    $CI =& get_instance();

    $user_id = $CI->session->userdata('user_id');
    if (!$user_id) {
        return false;
    }



    $user_type = $CI->session->userdata('user_type');

    // Super Admin (user_type 1) has unrestricted access
    if ((string) $user_type === '1') {
        return true;
    }

    // Guest users (user_type 2) have restricted portal access
    if ((string) $user_type === '2') {
        return false;
    }

    // Custom Role / Staff (user_type 3)
    $role_permissions = $CI->session->userdata('user_role_permissions');

    if ($role_permissions === null) {
        $user_id = $CI->session->userdata('user_id');
        if (!$user_id) {
            return false;
        }

        $CI->load->model('users/mdl_users');
        $user = $CI->mdl_users->get_by_id($user_id);
        if (!$user || empty($user->user_role_id)) {
            return false;
        }

        $CI->load->model('roles/mdl_roles');
        $role = $CI->mdl_roles->get_by_id($user->user_role_id);
        if (!$role) {
            return false;
        }

        $role_permissions = json_decode($role->role_permissions, true) ?: [];
        $CI->session->set_userdata('user_role_permissions', $role_permissions);
    }

    if (isset($role_permissions[$module][$action])) {
        return (bool) $role_permissions[$module][$action];
    }

    return false;
}

function check_permission(string $module, string $action = 'view', string $redirect_to = 'dashboard'): void
{
    if (!has_permission($module, $action)) {
        $CI =& get_instance();

        if ($CI->input->is_ajax_request() || (isset($CI->ajax_controller) && $CI->ajax_controller)) {
            $CI->output->set_status_header(403);
            echo json_encode([
                'success'           => 0,
                'validation_errors' => [
                    'permission' => trans('access_denied'),
                ],
            ]);
            exit;
        }

        $CI->session->set_flashdata('alert_error', trans('access_denied'));
        redirect($redirect_to);
    }
}
