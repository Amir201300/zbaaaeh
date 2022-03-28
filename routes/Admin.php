<?php

Route::post('/admin/login', 'AuthController@login')->name('admin.login');

Route::prefix('Admin')->group(function () {
    Route::get('/login', function () {
        return view('Admin.loginAdmin');
    });
    Route::group(['middleware' => 'roles', 'roles' => ['Admin']], function () {

        Route::get('/logout/logout', 'AuthController@logout')->name('user.logout');
        Route::get('/home', 'AuthController@index')->name('admin.dashboard');

        // Profile Route
        Route::prefix('profile')->group(function () {
            Route::get('/index', 'profileController@index')->name('profile.index');
            Route::post('/index', 'profileController@update')->name('profile.update');
        });

        // Category Routes
        Route::prefix('Category')->group(function () {
            Route::get('/index', 'CategoryController@index')->name('Category.index');
            Route::get('/allData', 'CategoryController@allData')->name('Category.allData');
            Route::post('/create', 'CategoryController@create')->name('Category.create');
            Route::get('/edit/{id}', 'CategoryController@edit')->name('Category.edit');
            Route::post('/update', 'CategoryController@update')->name('Category.update');
            Route::get('/destroy/{id}', 'CategoryController@destroy')->name('Category.destroy');
        });

        // Product_images Routes
        Route::prefix('Product_images')->group(function () {
            Route::get('/index', 'Product_imagesController@index')->name('Product_images.index');
            Route::get('/allData', 'Product_imagesController@allData')->name('Product_images.allData');
            Route::post('/create', 'Product_imagesController@create')->name('Product_images.create');
            Route::get('/edit/{id}', 'Product_imagesController@edit')->name('Product_images.edit');
            Route::post('/update', 'Product_imagesController@update')->name('Product_images.update');
            Route::get('/destroy/{id}', 'Product_imagesController@destroy')->name('Product_images.destroy');
        });

        // Product Routes
        Route::prefix('Product')->group(function () {
            Route::get('/index', 'ProductController@index')->name('Product.index');
            Route::get('/allData', 'ProductController@allData')->name('Product.allData');
            Route::post('/create', 'ProductController@create')->name('Product.create');
            Route::get('/edit/{id}', 'ProductController@edit')->name('Product.edit');
            Route::post('/update', 'ProductController@update')->name('Product.update');
            Route::get('/destroy/{id}', 'ProductController@destroy')->name('Product.destroy');
        });

        // Cut_method Routes
        Route::prefix('Cut_method')->group(function () {
            Route::get('/index', 'Cut_methodController@index')->name('Cut_method.index');
            Route::get('/allData', 'Cut_methodController@allData')->name('Cut_method.allData');
            Route::post('/create', 'Cut_methodController@create')->name('Cut_method.create');
            Route::get('/edit/{id}', 'Cut_methodController@edit')->name('Cut_method.edit');
            Route::post('/update', 'Cut_methodController@update')->name('Cut_method.update');
            Route::get('/destroy/{id}', 'Cut_methodController@destroy')->name('Cut_method.destroy');
        });
//Pack_method

        //Pack_method Routes
        Route::prefix('Pack_method')->group(function () {
            Route::get('/index', 'Pack_methodController@index')->name('Pack_method.index');
            Route::get('/allData', 'Pack_methodController@allData')->name('Pack_method.allData');
            Route::post('/create', 'Pack_methodController@create')->name('Pack_method.create');
            Route::get('/edit/{id}', 'Pack_methodController@edit')->name('Pack_method.edit');
            Route::post('/update', 'Pack_methodController@update')->name('Pack_method.update');
            Route::get('/destroy/{id}', 'Pack_methodController@destroy')->name('Pack_method.destroy');
        });


        //Slider Routes
        Route::prefix('Slider')->group(function () {
            Route::get('/index', 'SliderController@index')->name('Slider.index');
            Route::get('/allData', 'SliderController@allData')->name('Slider.allData');
            Route::post('/create', 'SliderController@create')->name('Slider.create');
            Route::get('/edit/{id}', 'SliderController@edit')->name('Slider.edit');
            Route::post('/update', 'SliderController@update')->name('Slider.update');
            Route::get('/destroy/{id}', 'SliderController@destroy')->name('Slider.destroy');
        });

        //Setting

        //Setting Routes
        Route::prefix('Setting')->group(function () {
            Route::get('/index', 'SettingController@index')->name('Setting.index');
            Route::get('/allData', 'SettingController@allData')->name('Setting.allData');
            Route::get('/edit/{id}', 'SettingController@edit')->name('Setting.edit');
            Route::post('/update', 'SettingController@update')->name('Setting.update');
        });

        /** User */
        Route::prefix('User')->group(function () {
            Route::get('/index', 'UserController@index')->name('User.index');
            Route::get('/allData', 'UserController@allData')->name('User.allData');
            Route::get('/destroy/{id}', 'UserController@destroy')->name('User.destroy');
            Route::get('/show/{id}', 'UserController@show')->name('User.show');
        });
    });
});

