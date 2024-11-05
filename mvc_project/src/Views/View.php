<?php
namespace App\Views;

class View {
    public function render($viewName, $data = []) {
        extract($data);
        include __DIR__ . "/templates/$viewName.php";
    }
}