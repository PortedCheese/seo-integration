# Seo integration

Добавляется таблица с метатегами. Тег может быть привязать к любой модели, а так же можно создать теги для статичных страниц.

У модели Meta есть метод на проверку возможности привязать тэг к модели (getModel он проверяет есть ли возможность довабить к этой модели тег и нет ли уже такого тега, если передать имя), названия моделей указываются в конфиге. Называть надо так же как названа таблица, потому что есть метод который позволяет получить все теги для конкретной модели (getByModelKey) и кэширует результат. Еще есть метод на получение тегов для страницы по ключу (getByPageKey)


## Установка
    php artisan vendor:publish --provider="PortedCheese\SeoIntegration\SeoIntegrationServiceProvider"

    php artisan migrate
    
    php artisan make:seo {--all : Run all}
                         {--models : Export models}
                         {--controllers : Export controllers}
                         {--policies : Export and create rules}
                         {--only-default : Create default rules}

#### Вывод формы добавляения тегов для пользователя
    @include("seo-integration::admin.meta.create", ['model' => 'users', 'id' => Auth::user()->id])

#### Вывод таблицы всех тегов у модели с кнопками редактирования и удаления
    @include("seo-integration::admin.meta.table-models", ['metas' => Auth::user()->metas])

#### Создание и редактирование тегов для страниц
Есть роут admin.meta.index, на нем выводится таблица

### Versions

    v1.1.2:
        - Добавлен шаблон для меню
        - Настроены права на мета
    Обновление:
        - изменить меню на шаблон seo-integration::admin.meta.menu
        - php artisan make:seo --all
    
    v1.1.1:
        - Добавлена команда
        - Теперь можно переопределять модель и контроллеры
    Обновление:
        - php artisan make:seo --all