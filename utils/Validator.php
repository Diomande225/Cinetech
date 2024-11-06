<?php
class Validator {
    private $errors = [];
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || empty(trim($this->data[$field]))) {
            $this->errors[$field] = $message ?? "Le champ $field est requis";
        }
        return $this;
    }

    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "L'email n'est pas valide";
        }
        return $this;
    }

    public function min($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "Le champ $field doit contenir au moins $length caractères";
        }
        return $this;
    }

    public function max($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "Le champ $field ne doit pas dépasser $length caractères";
        }
        return $this;
    }

    public function matches($field1, $field2, $message = null) {
        if (isset($this->data[$field1]) && isset($this->data[$field2]) && 
            $this->data[$field1] !== $this->data[$field2]) {
            $this->errors[$field2] = $message ?? "Les champs ne correspondent pas";
        }
        return $this;
    }

    public function isValid() {
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
} 