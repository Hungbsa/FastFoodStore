<?php
session_start();
$res_id = isset($_SESSION['group']['res_id']) ? intval($_SESSION['group']['res_id']) : 0;
unset($_SESSION['group']);
if ($res_id) {
    header('Location: ../dishes.php?res_id=' . $res_id);
} else {
    header('Location: ../dishes.php');
}
exit;
