<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\Image;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
//  Lấy tất cả các bình luận của một bài viết cụ thể theo kiểu chỏ sang relation:  $article->comments
    $article = Article::query()->find(1);
    $comments = $article->comments;

//  Lấy tất cả các đánh giá của một video cụ thể  theo kiểu chỏ sang relation: $video->ratings
    $video = Video::query()->find('1');
    $ratings = $video->ratings;

//  Lấy tất cả các bình luận của một người dùng cụ thể (có thể dùng join or sử dụng relation)
    $user = User::query()->find(1);
    $comment = $user->comments;

//  Lấy trung bình đánh giá của một bài viết cụ thể. Gợi ý: $article->ratings()->avg('rating')
    $article = Article::query()->find(1);
    $avgRatings = $article->ratings()->avg('rating');

//  Lấy tất cả các bài viết, video, và hình ảnh được bình luận bởi một người dùng cụ thể .
// Gợi ý: lấy tất cả comment theo user id, sau đó sử dụng filter của collection để lấy dữ liệu theo từng loại bài viết.
    $user = User::query()->find(1);
    $comment = $user->comments;

    $articlesCommented = $comments->filter(function ($comment) {
        return $comment->commentable_type == Article::class;
    })->pluck('commentable');

    $videosCommented = $comments->filter(function ($comment) {
        return $comment->commentable_type == Video::class;
    })->pluck('commentable');

    $imagesCommented = $comments->filter(function ($comment) {
        return $comment->commentable_type == Image::class;
    })->pluck('commentable');

//    Lấy danh sách các bài viết, video, và hình ảnh có đánh giá trung bình cao nhất. Gợi ý:
    $topRatedArticles = Article::with(['ratings' => function($query) {
    $query->select(DB::raw('rateable_id, AVG(rating) as average_rating'))
     ->groupBy('rateable_id')
     ->orderBy('average_rating', 'desc')
     ->take(5);
     }])
     ->get();

    $topRateVideos = Video::with(['ratings'=>function ($query){
        $query->select(DB::raw('rateable_id,AVG(rating) as average_rating'))
            ->groupBy('rateable_id')
            ->orderByDesc('average_rating')
            ->take('5');
    }])->get();


   $topRateImages = Image::with(['ratings'=>function ($query) {
        $query->select(DB::raw('rateable_id, AVG(rating) as avgRate'))
            ->groupBy('rateable_id')
            ->orderByDesc('avgRate')
            ->take(5);
    }])->get();


    return view('welcome');
});
