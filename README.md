laratash
========

A Laravel wrapper for mustache.php, a PHP implementation of http://mustache.github.io/

# Acknowledgements

This project is based entirely on the the [mustache-l4](https://github.com/conarwelsh/mustache-l4) package
by Conar Welsh and contributors.

## Supports
- `Laravel 5`
- `mustache/mustache 2.7+`

# Installation

Add laratash as a dependency to your `composer.json` file:

```json
"require": {
	"laravel/framework": "~5.0@dev",
	"brightmachine/laratash": "~5.0"
}
```

run `composer update`, or `composer install` if this is a brand new project
	
## Add the Service Provider

Open: `config/app.php`

```php
...

'Laratash\LaratashServiceProvider',
	
...
```

You are all setup!


# Usage

Laratash is merely a wrapper for the [Mustache.php](https://github.com/bobthecow/mustache.php) library that integrates it into Laravel 5+.

Laratash registers itself with the Laravel View class, providing seamless integration with Laravel.  You can use Mustache just as you would Blade!
The Laravel View class will choose the right template engine to use based on the file extension of the view.  So all you have to do to render Mustache files, is ensure that your view has a `.mustache` file extension.  Laratash will take care of the rest.

You can even mix and match template engines.  For instance maybe you have a Blade layout file, and you want to nest a Mustache view, that's fine!  However just be aware of the fact that Mustache does not understand Block Sections like Blade does.
The Mustache view will be rendered into a variable named whatever section you passed the view to.  So for example if you were to do:

```php
$view->nest('content', 'some.view');
$view->nest('sidebar', 'some.sidebar');
```

The contents of the parsed `some.view` file will be available in the template file under a variable called `$content`.
The contents of the parsed `some.sidebar` would be available in the template file, under a variable called `$sidebar`.

By default, Mustache partials are also loaded using Laravel's ViewFinder, so you can feel free to use dot-notation to specify a view.

```html
{{#posts}}
	{{> posts._post}}
{{/posts}}
```

Other than that it is business as usual!

# Examples:

- Example using View::make()

	app/views/test.mustache
	
		<h1>{{ pageHeading }}</h1>
		<div>
			{{ pageContent }}
		</div>
		
	app/router.php
	
		Route::get('/', function()
		{
			return View::make('test', array(
				'pageHeading' => 'Rendered with Mustache.php',
				'pageContent' => 'But still looks like Laravel!'
			));
		});

- Example using a Blade controller layout
	
	app/views/layouts/master.blade.php

		<html>
		<head></head>
		<body>
			{{-- since Mustache does not use sections, the nested section will instead
			be rendered as a variable --}}
			{{ content }}
		</body>
		</html>
		
	app/views/test.mustache
	
		<h1>{{ pageHeading }}</h1>
		<div>
			{{ pageContent }}
		</div>
	
	app/controllers/TestController.php

		<?php

		class TestController extends BaseController {
		
		    public $layout = 'layouts.master';
		    
		    public function index()
		    {
		 	$this->layout->nest('content', 'test', array(
		 		'pageHeading' => 'Rendered with Mustache.php',
				'pageContent' => 'But still looks like Laravel!'
		 	));   
		    }
		    
	    	}
	    	
- Example using a Mustache layout

	app/views/posts/_post.mustache
		
		<article>
			<h2>{{ title }}</h2>
			<div>
				{{ content }}
			</div>
		</article>
	
	app/views/blog/index.mustache

		<html>
		<head></head>
		<body>
			<h1>My Blog</h1>
			
			{{#posts}}
				{{> posts._post}}
			{{/posts}}
		</body>
		</html>
		
	app/routes.php
	
		Route::get('/', function()
		{
			$posts = array(
				array(
					'title' => 'This is a Title',
					'content' => 'lorem ipsum...'
				),
				array(
					'title' => 'This is a another title',
					'content' => 'lorem ipsum...'
				),
				array(
					'title' => 'This is yet another Title',
					'content' => 'lorem ipsum...'
				),
			);
			
			return View::make('blog.index', compact('posts));
		});

# Configure

You can alter the configuration options that are passed to Mustache.php by publishing the config file.

	php artisan config:publish brightmachine/laratash
	
All options in this config file are passed directly to the Mustache_Engine constructor, so you can use any of the options that you would use with [Mustache.php](https://github.com/bobthecow/mustache.php)
