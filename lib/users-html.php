<?php

function getUsersHtml($users) {

    $levels = json_decode(file_get_contents(BASE . '/include/config/priv-levels.json'), true);

    $html = '';
    foreach ($users as $user) {
        $html .= '<tr class="user">';
        $html .= '<td>' . $user['u_onid'] . '</td>';
        $html .= '<td>';
        $html .= '<select id="' . $user['uid'] . '" class="form-control user-priv-select" ';
        $html .= 'onChange="onPrivilegeLevelEdit(this)">';
        foreach ($levels as $index => $level) {
            $html .= '<option value="' . $index . '" ';
            if ($index == $user['u_privilege_level']) {
                $html .= 'selected';
            }
            $html .= '>' . $level . '</option>';
        }
        $html .= '</select></td>';
        $html .= '<td>' . date("F j, Y, g:i a", $user['u_last_login']) . '</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
    }

    return $html;
}