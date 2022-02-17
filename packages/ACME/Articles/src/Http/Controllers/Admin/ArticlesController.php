<?php

namespace ACME\Articles\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use ACME\Articles\Repositories\ArticleRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArticlesController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ArticleRepository object
     *
     * @var \ACME\Articles\Repositories\ArticleRepository
     */
    protected $articleRepository;

    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \ACME\Articles\Repositories\ArticleRepository  $articleRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(
        ArticleRepository $articleRepository,
        AttributeRepository $attributeRepository
    ) {
        $this->middleware('admin');

        $this->articleRepository = $articleRepository;

        $this->attributeRepository = $attributeRepository;

        $this->_config = request('_config');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'slug'        => ['required', 'unique:article_translations,slug'],
            'name'        => 'required',
            'image.*'     => 'mimes:bmp,jpeg,jpg,png,webp',
            'description' => 'required',
        ]);
        // dd(request()->all());
        $this->articleRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'article']));
        return redirect()->route('admin.articles.index');

        // return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        //article
        $article = $this->articleRepository->findOrFail($id);
        $attributes = $this->attributeRepository->findWhere(['is_filterable' =>  1]);

        return view($this->_config['view'], compact('article', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $locale = core()->getRequestedLocaleCode();

        $this->validate(request(), [
            $locale . '.slug' => ['required', function ($attribute, $value, $fail) use ($id) {
                if (! $this->articleRepository->isSlugUnique($id, $value)) {
                    $fail(trans('admin::app.response.already-taken', ['name' => 'article']));
                }
            }],
            $locale . '.name' => 'required',
            'image.*' => 'mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $this->articleRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'article']));
        //$this->_config['redirect']
        return redirect()->route('admin.articles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = $this->articleRepository->findOrFail($id);

        if ($this->isarticleDeletable($article)) {
            session()->flash('warning', trans('admin::app.response.delete-article-root', ['name' => 'article']));
        } else {
            try {
                // Event::dispatch('article.delete.before', $article);

                $article->delete();

                // Event::dispatch('article.delete.after', $article);

                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'article']));

                return response()->json(['message' => true], 200);
            } catch (\Exception $e) {
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'article']));
            }
        }

        return response()->json(['message' => false], 400);
    }
}