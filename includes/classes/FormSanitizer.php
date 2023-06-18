<?php
class FormSanitizer {
    // Sanitize the form string to remove any HTML tags,
    // remove any spaces, convert the string to lowercase,
    // and capitalize the first letter of the string.
    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;
    }
    
    // Sanitize the form username to remove any HTML tags,
    // and remove any spaces.
    public static function sanitizeFormUsername($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }

    // Sanitize the form email to remove any HTML tags,
    public static function sanitizeFormPassword($inputText) {
        $inputText = strip_tags($inputText);
        return $inputText;
    }
    
    // Sanitize the form email to  remove any HTML tags,
    // and remove any spaces.
    public static function sanitizeFormEmail($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }
}
?>