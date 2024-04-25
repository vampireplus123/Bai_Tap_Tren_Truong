<?php
session_start();

// Xóa tất cả các biến phiên
session_unset();

// Hủy phiên đăng nhập
session_destroy();

// Chuyển hướng người dùng đến trang đăng nhập hoặc trang chính
header("Location: /Home/Home.php"); // hoặc trang chính
exit();
?>
