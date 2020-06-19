<?php

namespace PortedCheese\SeoIntegration\Traits;



use App\Meta;

trait ShouldMetas
{
    protected static function bootShouldMetas()
    {
        static::created(function($model) {
            // Создать метатеги по умолчанию.
            $model->createDefaultMetas();
        });

        static::deleting(function ($model) {
            // Удаляем метатеги.
            $model->clearMetas();
        });
    }

    /**
     * Получить ключ тэгов.
     *
     * @return string
     */
    protected function getMetaKey()
    {
        if (! empty($this->metaKey)) {
            return $this->metaKey;
        }
        else {
            return "default";
        }
    }

    /**
     * Метатеги.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas() {
        return $this->morphMany(Meta::class, 'metable');
    }

    /**
     * Создать метатеги по умолчанию.
     */
    public function createDefaultMetas()
    {
        $metaKey = $this->getMetaKey();

        $result = Meta::getModel($metaKey, $this->id, "title");
        if ($result['success'] && ! empty($this->title)) {
            $meta = Meta::create([
                'name' => 'title',
                'content' => $this->title,
            ]);
            $meta->metable()->associate($this);
            $meta->save();
        }

        $result = Meta::getModel($metaKey, $this->id, "description");
        if ($result['success'] && ! empty($this->short)) {
            $meta = Meta::create([
                'name' => 'description',
                'content' => $this->short,
            ]);
            $meta->metable()->associate($this);
            $meta->save();
        }
    }

    /**
     * Удаляем созданные теги.
     */
    public function clearMetas()
    {
        foreach ($this->metas as $meta) {
            $meta->delete();
        }
    }
}