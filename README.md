# Seo integration

##### _Зависит от сборки, которая пока не вынесена в пакет. Там есть шаблон admin.layout и компонент confirm-delete-model-button_

Добавляется таблица с метатегами. Тег может быть привязать к любой модели, а так же можно создать теги для статичных страниц.

У модели Meta есть метод на проверку возможности привязать тэг к модели (getModel он проверяет есть ли возможность довабить к этой модели тег и нет ли уже такого тега, если передать имя), названия моделей указываются в конфиге. Называть надо так же как названа таблица, потому что есть метод который позволяет получить все теги для конкретной модели (getByModelKey) и кэширует результат. Еще есть метод на получение тегов для страницы по ключу (getByPageKey)

## Установка
`composer require portedcheese/seo-integration`

Нужно выгрузить конфиг

`php artisan vendor:publish --provider="PortedCheese\SeoIntegration\SeoIntegrationServiceProvider"`

Создаем таблицы

`php artisan migrate`

#### Вывод формы добавляения тегов для пользователя
`@include("seo-integration::admin.meta.create", ['model' => 'users', 'id' => Auth::user()->id])`

#### Вывод таблицы всех тегов у модели с кнопками редактирования и удаления
`@include("seo-integration::admin.meta.table-models", ['metas' => Auth::user()->metas])`

#### Создание и редактирование тегов для страниц
Есть роут admin.meta.index, на нем выводится таблица