<?php
use App\Models\Article;
use App\Models\User;


 
it('models_can_be_instantiated_with_static_user_id', function () {
   $user= CreateUser();
    $article = new Article([    
        'title'=> 'test article',
        'body'=> 'test article body',
        'user_id'=> $user->id,
        ]);
        expect($article->title)->toBe('test article');
        expect($article->body)->toBe('test article body');
        expect($article->user_id)->toBe($user->id);
});

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('models_can_be_instantiated_with_range_user_id', function ($Title) {
    $article = new Article([    
        'title'=> $Title,
        'body'=> 'test article body',
        'user_id'=> $this->user->id,
        ]);
    expect($article->title)->toBe($Title);

})->with('title');

dataset('title',[
    "1"=>"this is article 1",
    "2"=>"this is article 2",
    "3"=>"this is article 3",
    "4"=>"this is article 4"
]);

