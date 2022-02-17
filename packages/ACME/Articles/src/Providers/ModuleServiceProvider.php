<?php

namespace ACME\Articles\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \ACME\Articles\Models\Article::class,
        \ACME\Articles\Models\ArticleTranslation::class,
    ];
}