<?php

namespace ACME\Articles\Models;

use Illuminate\Database\Eloquent\Model;
use ACME\Articles\Contracts\ArticleTranslation as ArticleTranslationContract;

class ArticleTranslation extends Model implements ArticleTranslationContract
{
    protected $fillable = [
        'name',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale_id',
    ];
}