<?php
require_once(__DIR__ . '/email_templates.php');

class EmailRenderer {
    private $templates;

    public function __construct() {
        $this->templates = new EmailTemplates();
    }

    /**
     * Renderiza asunto y html para una clave/idioma, con reemplazo simple {{clave}}.
     * Retorna fallback si no hay plantilla.
     */
    public function render($clave, $idioma, array $vars, $fallbackSubject, $fallbackHtml) {
        $tpl = $this->templates->obtener($clave, $idioma);
        $subject = $fallbackSubject;
        $html = $fallbackHtml;

        if ($tpl && !empty($tpl['html'])) {
            $subject = $tpl['asunto'] ?: $fallbackSubject;
            $html = $tpl['html'];
        }

        return [
            'asunto' => $this->replaceVars($subject, $vars),
            'html' => $this->replaceVars($html, $vars)
        ];
    }

    private function replaceVars($text, array $vars) {
        $search = [];
        $replace = [];
        foreach ($vars as $k => $v) {
            $search[] = '{{' . $k . '}}';
            $replace[] = $v;
        }
        return str_replace($search, $replace, $text);
    }
}

?>
