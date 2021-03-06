<?php

namespace PortedCheese\SeoIntegration\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MetaController extends Controller
{
    /**
     * Список мета, которые не привязаны к материалу.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize("viewAny", Meta::class);

        $metas = Meta::query()
            ->whereNotNull('page')
            ->get()
            ->sortBy('page')
            ->groupBy('page');
        return view("seo-integration::admin.meta.index", [
            'pages' => $metas,
        ]);
    }

    /**
     * Сохраняем мета тэг, который не привязан к материалу.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function storeStatic(Request $request)
    {
        $this->authorize("create", Meta::class);

        $this->validateStatic($request->all());
        $userInput = $request->all();
        $name = $userInput['name'];
        $page = $userInput['page'];
        $collection = Meta::query()
            ->where('name', $name)
            ->where('page', $page)
            ->get();
        if ($collection->count()) {
            return redirect()
                ->route('admin.meta.index')
                ->with('danger', 'Такой метатег уже существует');
        }
        Meta::create($userInput);
        return redirect()
            ->route('admin.meta.index')
            ->with('success', 'Метатег добавлен');
    }

    /**
     * Валидация для стараницы.
     *
     * @param $data
     */
    protected function validateStatic($data)
    {
        Validator::make($data, [
            "page" => ["required", "min:2", "max:250"],
            "name" => ["required", "max:250"],
            "content" => ["required"],
        ], [], [
            "page" => "Страница",
            "name" => "Name",
            "content" => "Content",
        ]);
    }

    /**
     * Создать мета для модели.
     *
     * @param Request $request
     * @param $model
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function storeModel(Request $request, $model, $id)
    {
        $this->authorize("create", Meta::class);

        $this->validateModel($request->all());
        $userInput = $request->all();
        $result = Meta::getModel($model, $id, $userInput['name']);
        if (!$result['success']) {
            return redirect()
                ->back()
                ->with('danger', $result['message']);
        }
        $model = $result['model'];
        $meta = Meta::create($userInput);
        $meta->metable()->associate($model);
        $meta->save();
        return redirect()
            ->back()
            ->with('success', 'Метатег добавлен');
    }

    /**
     * Валидация добавления для модели.
     *
     * @param $data
     */
    protected function validateModel($data)
    {
        Validator::make($data, [
            "name" => ["required", "max:250"],
            "content" => ["required"],
        ], [], [
            "name" => "Name",
            "content" => "Content",
        ])->validate();
    }

    /**
     * Получить изображение из модели.
     *
     * @param Request $request
     * @param $model
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getImageByModel(Request $request, $model, $id)
    {
        $this->authorize("create", Meta::class);

        $result = Meta::getModel($model, $id, "image");
        if (! $result['success']) {
            if (! empty($result['collection'])) {
                $model = $result['model'];
                if (empty($model->image->file_name)) {
                    return redirect()
                        ->back()
                        ->with("danger", "Изображение не найдено");
                }
                $fileName = $model->image->file_name;
                foreach ($result['collection'] as $item) {
                    $item->content = $fileName;
                    $item->save();
                }
                return redirect()
                    ->back()
                    ->with("success", "Изображение обновлено");
            }
            return redirect()
                ->back()
                ->with("danger", $result['message']);
        }
        $model = $result['model'];
        if (empty($model->image->file_name)) {
            return redirect()
                ->back()
                ->with("danger", "Изображение не найдено");
        }
        $meta = Meta::create([
            "name" => "image",
            "content" => $model->image->file_name,
        ]);
        $meta->metable()->associate($model);
        $meta->save();
        return redirect()
            ->back()
            ->with('success', 'Метатег добавлен');
    }

    /**
     * Страница редактирования.
     *
     * @param Request $request
     * @param Meta $meta
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Request $request, Meta $meta)
    {
        $this->authorize("update", Meta::class);

        if (!$request->has('destination')) {
            return redirect()
                ->back()
                ->setTargetUrl($request->fullUrlWithQuery([
                    'destination' => redirect()->back()->getTargetUrl()
                ]));
        }
        $hasModel = ! empty($meta->metable);
        $route = 'admin.meta.update-' . ($hasModel ? 'model' : 'static');
        return view("seo-integration::admin.meta.update", [
            'meta' => $meta,
            'route' => $route,
            'hasModel' => $hasModel,
            'back' => $request->get('destination'),
        ]);
    }

    /**
     * Обновление.
     *
     * @param Request $request
     * @param Meta $meta
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Meta $meta)
    {
        $this->authorize("update", Meta::class);

        $this->updateValidator($request->all());
        $userInput = $request->all();
        if (empty($userInput['separated'])) {
            $userInput['separated'] = 0;
        }
        else {
            $userInput['separated'] = 1;
        }
        $meta->update($userInput);
        return redirect()
            ->back()
            ->setTargetUrl($userInput['back'])
            ->with('success', 'Метатег обновлен');
    }

    /**
     * Валидация обновления.
     *
     * @param $data
     */
    protected function updateValidator($data)
    {
        Validator::make($data, [
            "page" => ["required_if:model,off", "min:2", "max:250"],
            "name" => ["required", "max:250"],
            "content" => ["required"],
        ], [] ,[
            "page" => "Стараница",
            "model" => "Тип материала",
            "name" => "Name",
            "content" => "Content",
        ])->validate();
    }

    /**
     * Удаление.
     *
     * @param Meta $meta
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Meta $meta)
    {
        $this->authorize("delete", Meta::class);

        $meta->delete();
        return redirect()
            ->back()
            ->with('success', 'Метатег удален');
    }
}
