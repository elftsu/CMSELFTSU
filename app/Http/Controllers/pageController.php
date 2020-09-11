<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
// OR with multi
use Artesaos\SEOTools\Facades\JsonLdMulti;

// OR
use Artesaos\SEOTools\Facades\SEOTools;
use App\Page;
use \App\Post;
use \App\Categories;

class pageController extends Controller
{
    


   
    
    public function index() {
       
    }
    
    public function createSeo($title ,$description,$keyword ,$created_at ,$slug ,$img) {
        SEOMeta::setTitle($title ,false);

        SEOTools::twitter()->setSite('@LuizVinicius73');
        SEOTools::setCanonical(env('APP_URL').$slug);


SEOMeta::setDescription($description);
SEOMeta::addMeta('article:published_time', $created_at->toW3CString(), 'property');
SEOMeta::addMeta('article:section', $title, 'property');
SEOMeta::addKeyword([$keyword]);

OpenGraph::setDescription($description);
OpenGraph::setTitle($title);
OpenGraph::setUrl(env('APP_URL').$slug);
OpenGraph::addProperty('type', 'article');
OpenGraph::addProperty('locale', 'pt-br');
OpenGraph::addProperty('locale:alternate', ['pt-pt', 'en-us']);

OpenGraph::addImage(env('APP_URL').'storage/'.$img);
OpenGraph::addImage(['url' => env('APP_URL').'storage/'.$img, 'size' => 300]);


// OR with multi

JsonLdMulti::setTitle($title);
JsonLdMulti::setDescription($description);


JsonLdMulti::addImage(env('APP_URL').'/storage/'.$img);
if(! JsonLdMulti::isEmpty()) {
    JsonLdMulti::newJsonLd();
    JsonLdMulti::setType('WebPage');   

    JsonLdMulti::setTitle($title);
    JsonLdMulti::setDescription($description);

}


// Namespace URI: http://ogp.me/ns/article#
// article
OpenGraph::setTitle('Article')
    ->setDescription($description)
    ->setType('article')
    ->setArticle([
        'published_time' => 'datetime',
        'modified_time' => 'datetime',
        'expiration_time' => 'datetime',
        'author' => 'profile / array',
        'section' => 'string',
        'tag' => 'string / array'
    ]);

    OpenGraph::setTitle('WebSite')
    ->setDescription($description)
    ->setType('WebSite')
    ->setArticle([
        'name' => 'Site name',
        'url' => 'url',
        'sameAs' => '["https://facebook.com/mypage",
        "https://instagram.com/site",
        "https://twitter.com/name"]',
        'potentialAction' => '{
            "@type": "SearchAction",
            "target": "http://example.com/pages/search_results?q={search_term}",
            "query-input": "required name=search_term"
            }'
    ]);

   
    OpenGraph::setTitle('Profile')
             ->setDescription('Some Person')
            ->setType('profile')
            ->setProfile([
                'first_name' => 'string',
                'last_name' => 'string',
                'username' => 'string',
                'gender' => 'enum(male, female)'
            ]);


// Namespace URI: http://ogp.me/ns/book#
// book


// Namespace URI: http://ogp.me/ns/profile#
// profile
OpenGraph::setTitle('Profile')
     ->setDescription($description)
    ->setType('profile')
    ->setProfile([
        'first_name' => 'string',
        'last_name' => 'string',
        'username' => 'string',
        'gender' => 'enum(male, female)'
    ]);

// og:place
OpenGraph::setTitle('Place')
     ->setDescription($description)
    ->setType('place')
    ->setPlace([
        'location:latitude' => 'float',
        'location:longitude' => 'float',
    ]);








    }




   
    public function ShowHome(){
$page_data = Page::where('slug', '=', 'home')->firstOrFail();

$this->createSeo($page_data->title , $page_data->meta_description , $page_data->meta_keyword ,$page_data->created_at  ,$page_data->slug ,$page_data->image

);

       $tours_data = Post::with('Category')->get();
       $ShemaSeo='<script type="application/ld+json">{
        "@context": "http://schema.org",
        "@type": "WebSite",
        "name": "Site name",
        "url": "https://example.com",
        "sameAs": ["https://facebook.com/mypage",
                   "https://instagram.com/site",
                   "https://twitter.com/name"],
        "potentialAction": {
        "@type": "SearchAction",
        "target": "http://example.com/pages/search_results?q={search_term}",
        "query-input": "required name=search_term"
        }
        }</script>';

        //return view('home', compact('page_data'));

    //   $tours_data = Post::with('Caty')->get();
	//	return View::make('listings', compact('listings'));

  //  $tours_data  = Post::with('Categorie')->first();

    return view("home", compact('page_data','tours_data' ,'ShemaSeo'));

    
       // return Post::find(1)->categorie;
    }




}
