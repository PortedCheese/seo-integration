<?php

namespace PortedCheese\SeoIntegration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Meta extends Model
{
    protected $fillable = [
        'name',
        'content',
        'property',
        'meta_id',
        'metable_id',
        'metable_type',
        'page',
        'separated',
    ];

    const OGMETAS = [
        'title',
        'type',
        'url',
        'image',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($meta) {
            if (
                in_array($meta->name, self::OGMETAS) &&
                empty($meta->property) &&
                !$meta->separated
            ) {
                $data = $meta->toArray();
                $data['property'] = "og:{$meta->name}";
                $data['meta_id'] = $meta->id;
                $newMeta = Meta::create($data);
            }
            $meta->forgetCache();
        });

        static::updated(function ($meta) {
            $child = $meta->child;
            if (
                !empty($child) &&
                !$child->separated
            ) {
                $data = $meta->toArray();
                $data['meta_id'] = $meta->id;
                $data['property'] = $child->property;
                $child->update($data);
            }
            $meta->forgetCache();
        });

        static::deleted(function ($meta) {
           $child = $meta->child;
           if (!empty($child)) {
               $child->delete();
           }
            $meta->forgetCache();
        });
    }

    /**
     * Может быть дочерний мета для OG.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function child()
    {
        return $this->hasOne("PortedCheese\SeoIntegration\Models\Meta", 'meta_id');
    }

    /**
     * Если есть родитель то это для OG.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo("PortedCheese\SeoIntegration\Models\Meta", 'meta_id');
    }

    /**
     * Может относится к разным моделям.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function metable()
    {
        return $this->morphTo();
    }

    /**
     * Вывод элемента.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRenderAttribute()
    {
        return view("seo-integration::layouts.meta.render", [
            'meta' => $this,
        ]);
    }

    /**
     * Проверить можно ли создать для модели.
     *
     * @param $model
     * @param $id
     * @param $name
     * @return array
     */
    public static function getModel($model, $id, $name = false)
    {
        $config = config('seo-integration.models');
        if (empty($config[$model])) {
            return [
                'success' => false,
                'message' => 'Для данной модели не задана конфигурация',
            ];
        }
        $class = $config[$model];
        if (!class_exists($class)) {
            return [
                'success' => false,
                'message' => 'Класс модели не найден',
            ];
        }
        try {
            $model = $class::findOrFail((int) $id);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Модель не найдена',
            ];
        }
        if ($name) {
            $collection = Meta::where('name', $name)
                ->where('metable_id', $id)
                ->where('metable_type', $class)
                ->get();
            if ($collection->count()) {
                return [
                    'success' => false,
                    'message' => 'Такой метатег уже существует',
                    'collection' => $collection,
                    'model' => $model,
                ];
            }
        }
        return [
            'success' => true,
            'model' => $model,
        ];
    }

    /**
     * Получить мета для страницы.
     *
     * @param $page
     * @return array|mixed
     */
    public static function getByPageKey($page)
    {
        $key = "meta-page:$page";
        $cached = Cache::get($key);
        if (!empty($cached)) {
            return $cached;
        }
        $collection = Meta::where('page', $page)
            ->get();
        $rendered = [];
        foreach ($collection as $item) {
            $key = $item->name;
            if (!empty($item->property)) {
                $key .= "-{$item->property}";
            }
            $rendered[$key] = $item->render->render();
        }
        Cache::forever($key, $rendered);
        return $rendered;
    }

    /**
     * Получить для модели.
     *
     * @param $model
     * @return array|mixed
     */
    public static function getByModelKey($model)
    {
        if (!method_exists($model, 'metas')) {
            return [];
        }
        $cacheKey = "meta-model:{$model->table}{$model->id}";
        $cached = Cache::get($cacheKey);
        if (!empty($cached)) {
            return $cached;
        }
        $collection = $model->metas;
        $rendered = [];
        foreach ($collection as $item) {
            $key = $item->name;
            if (!empty($item->property)) {
                $key = $item->property;
            }
            $rendered[$key] = $item->render->render();
        }
        Cache::forever($cacheKey, $rendered);
        return $rendered;
    }

    /**
     * Очистка кэша.
     */
    private function forgetCache()
    {
        if (!empty($this->page)) {
            Cache::forget("meta-page:{$this->page}");
        }
        else {
            $model = $this->metable;
            if (empty($model)) {
                return;
            }
            $key = "meta-model:{$model->table}{$model->id}";
            Cache::forget($key);
        }
    }

}
