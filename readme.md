```php
Route::group(['namespace' => 'Auth'], function () {
    // Authentication Routes.
    Route::get('/login', 'LoginController@oauth')->name('user.login');
    Route::get('/oauth/callback', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout')->name('user.logout');
});
```