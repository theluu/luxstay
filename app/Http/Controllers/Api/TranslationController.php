<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Room;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranslationController extends Controller
{
    use SavesTranslations;

    private const MODELS = [
        'rooms'               => [Room::class,            'name'],
        'posts'               => [Post::class,            'title'],
        'activities'          => [Activity::class,        'title'],
        'products'            => [Product::class,         'name'],
        'sliders'             => [Slider::class,          'title'],
        'post-categories'     => [PostCategory::class,    'name'],
        'product-categories'  => [ProductCategory::class, 'name'],
    ];

    public function index(string $model, Request $request): JsonResponse
    {
        [$modelClass, $primaryField] = self::MODELS[$model] ?? abort(404);

        $query = $modelClass::query();

        if ($search = $request->query('search')) {
            $query->where($primaryField, 'like', "%{$search}%");
        }

        $items = $query->orderBy('id', 'desc')->paginate(20);

        $data = $items->getCollection()->map(function ($item) use ($primaryField) {
            $raw     = $item->getRawOriginal($primaryField);
            $decoded = is_string($raw) ? json_decode($raw, true) : null;
            $label   = (is_array($decoded)
                ? ($decoded['vi'] ?? $decoded[array_key_first($decoded)] ?? '')
                : $raw) ?: '—';

            return [
                'id'                 => $item->id,
                'label'              => $label,
                'translation_status' => $this->translationStatus($item),
                'all_translations'   => $this->allTranslations($item),
            ];
        });

        return response()->json([
            'data'       => $data,
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    public function update(Request $request, string $model, int $id): JsonResponse
    {
        [$modelClass] = self::MODELS[$model] ?? abort(404);
        $item = $modelClass::findOrFail($id);

        if ($request->has('translations')) {
            $this->applyTranslations($item, $request->input('translations', []));
            $item->save();
        }

        return response()->json([
            'data' => [
                'id'                 => $item->id,
                'translation_status' => $this->translationStatus($item),
                'all_translations'   => $this->allTranslations($item),
            ],
        ]);
    }

    public function translate(Request $request): JsonResponse
    {
        $text   = trim($request->input('text', ''));
        $target = $request->input('target', 'en');

        if (empty($text)) {
            return response()->json(['translated' => '']);
        }

        $langpairs = ['en' => 'vi|en', 'zh' => 'vi|zh'];
        $langpair  = $langpairs[$target] ?? 'vi|en';

        try {
            $resp = Http::timeout(10)->get('https://api.mymemory.translated.net/get', [
                'q'        => $text,
                'langpair' => $langpair,
            ]);
            $translated = $resp->json('responseData.translatedText') ?? '';
        } catch (\Throwable) {
            $translated = '';
        }

        return response()->json(['translated' => $translated]);
    }

    public function clearLocale(string $model, int $id, string $locale): JsonResponse
    {
        if ($locale === 'vi') {
            abort(422, 'Cannot clear the default locale (vi).');
        }

        [$modelClass] = self::MODELS[$model] ?? abort(404);
        $item = $modelClass::findOrFail($id);

        foreach ($item->translatable as $field) {
            $item->setTranslation($field, $locale, '');
        }
        $item->save();

        return response()->json([
            'data' => [
                'id'                 => $item->id,
                'translation_status' => $this->translationStatus($item),
                'all_translations'   => $this->allTranslations($item),
            ],
        ]);
    }
}
