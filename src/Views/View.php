<?php
namespace App\Views;

class View {
    private $layout = 'default';
    private $layouts = [
        'default' => [
            'header' => 'partials/header.php',
            'footer' => 'partials/footer.php',
        ]
    ];   

    public function render($viewName, $data = []) {
        // Extract data to make variables available in view
        extract($data);
        
        try {
            // Start output buffering
            ob_start();
            
            // Include head
            require __DIR__ . "/templates/" . $this->layouts[$this->layout]['header'];
            
            // Include the main content view
            require __DIR__ . "/templates/$viewName.php";
            
            // Include footer
            require __DIR__ . "/templates/" . $this->layouts[$this->layout]['footer'];
            
            // Get the contents and output it
            echo ob_get_clean();
            
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    public function renderPartial($viewName, $data = []) {
        extract($data);
        
        try {
            ob_start();
            require __DIR__ . "/templates/$viewName.php";
            return ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    public function setLayout($layout) {
        if (isset($this->layouts[$layout])) {
            $this->layout = $layout;
        }
    }

    public function translate($key, $language) {
        return $language->get($key);
    }
}