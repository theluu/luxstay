<?php
namespace App\Services;

use App\Models\EmailTemplate;

class EmailTemplateRenderer
{
    public static function resolve(string $key, array $vars = []): ?array
    {
        $template = EmailTemplate::find($key);
        if (!$template) {
            return null;
        }

        $allVars = array_merge(['app_url' => config('app.url')], $vars);

        return [
            'subject' => self::replace($template->subject, $allVars),
            'body'    => self::replace($template->body, $allVars),
        ];
    }

    private static function replace(string $text, array $vars): string
    {
        foreach ($vars as $k => $v) {
            $text = str_replace('{' . $k . '}', (string) ($v ?? ''), $text);
        }
        return $text;
    }
}
