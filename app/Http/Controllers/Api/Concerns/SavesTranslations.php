<?php

namespace App\Http\Controllers\Api\Concerns;

trait SavesTranslations
{
    /**
     * Apply per-locale translation data to a spatie translatable model.
     * Request format: { "translations": { "name": {"vi": "...", "en": "...", "zh": "..."} } }
     */
    protected function applyTranslations($model, array $translations): void
    {
        foreach ($translations as $field => $localeValues) {
            if (is_array($localeValues) && in_array($field, $model->translatable ?? [])) {
                $model->setTranslations($field, $localeValues);
            }
        }
    }

    /**
     * Return all locale translations for all translatable fields.
     * Use in show/update responses so the admin can populate tab panels.
     */
    protected function allTranslations($model): array
    {
        $result = [];
        foreach ($model->translatable ?? [] as $field) {
            $raw     = $model->getRawOriginal($field) ?? '';
            $decoded = $raw !== '' ? json_decode($raw, true) : null;

            if (is_array($decoded)) {
                // Already stored as JSON with per-locale keys
                $result[$field] = $decoded;
            } elseif ($raw !== '') {
                // Plain string (record not yet translated) — treat as vi default
                $result[$field] = ['vi' => $raw];
            } else {
                $result[$field] = [];
            }
        }
        return $result;
    }

    protected function translationStatus($model): array
    {
        $locales      = config('app.supported_locales', ['vi', 'en', 'zh']);
        $primaryField = ($model->translatable ?? [])[0] ?? null;
        if (!$primaryField) {
            return array_fill_keys($locales, false);
        }

        $raw     = $model->getRawOriginal($primaryField) ?? '';
        $decoded = $raw !== '' ? json_decode($raw, true) : null;
        $stored  = is_array($decoded) ? $decoded : ($raw !== '' ? ['vi' => $raw] : []);

        $status = [];
        foreach ($locales as $locale) {
            $status[$locale] = !empty($stored[$locale]);
        }
        return $status;
    }
}
