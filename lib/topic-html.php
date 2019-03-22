<?php

function getTopicHtml($topics) {
    global $userIsAdmin;
    $html = '';
    foreach ($topics as $topic) {

        $rtid = $topic['rtid'];
        $name = $topic['rt_name'];

        $html .= '<tr id="' . $rtid . '"><td><a href="resources/topic?t=' . $rtid . '">' . $name . '</a></td>';
        $html .= '<td>';

        if ($userIsAdmin) {
            $html .= '<button class="btn delete" onmouseup="onDeleteTopic(\'' . $rtid . '\',\'' . $name . '\')"><i class="far fa-trash-alt"></i></button>';
            $html .= '<button class="btn edit" onclick="onEditTopic(\'' . $rtid . '\',\'' . $name . '\')"><i class="far fa-edit"></i></button>';
            $html .= '<button class="btn cancel" style="display: none;" onclick="onCancelEditTopic(\'' . $rtid . '\')"><i class="fas fa-times"></i></button>';
            $html .= '<button class="btn save" style="display: none;" onclick="onSaveTopic(\'' . $rtid . '\')"><i class="far fa-save"></i></button>';
        }

        $html .= '</td>';
        $html .= '</tr>';

    }

    return $html;

}