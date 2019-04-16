<?php

namespace PortedCheese\SeoIntegration\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PortedCheese\SeoIntegration\Http\Requests\MetaModelStoreRequest;
use PortedCheese\SeoIntegration\Http\Requests\MetaStaticStoreRequest;
use PortedCheese\SeoIntegration\Http\Requests\MetaUpdateRequest;
use PortedCheese\SeoIntegration\Models\Meta;

class MetaController extends Controller
{
    /**
     * Список мета, которые не привязаны к материалу.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $metas = Meta::whereNotNull('page')
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
     * @param MetaStaticStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeStatic(MetaStaticStoreRequest $request)
    {
        $userInput = $request->all();
        $name = $userInput['name'];
        $page = $userInput['page'];
        $collection = Meta::where('name', $name)
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
     * Создать мета для модели.
     *
     * @param MetaModelStoreRequest $request
     * @param $model
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeModel(MetaModelStoreRequest $request, $model, $id)
    {
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
     * Страница редактирования.
     *
     * @param Request $request
     * @param Meta $meta
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request, Meta $meta)
    {
        if (!$request->has('destination')) {
            return redirect()
                ->back()
                ->setTargetUrl($request->fullUrlWithQuery([
                    'destination' => redirect()->back()->getTargetUrl()
                ]));
        }
        $hasModel = !empty($meta->metable);
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
     * @param MetaUpdateRequest $request
     * @param Meta $meta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MetaUpdateRequest $request, Meta $meta)
    {
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
     * Удаление.
     *
     * @param Meta $meta
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Meta $meta)
    {
        $meta->delete();
        return redirect()
            ->back()
            ->with('success', 'Метатег удален');
    }
}