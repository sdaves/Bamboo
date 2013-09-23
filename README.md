## Bamboo - scaffolding for Laravel 4
Bamboo is a package for Laravel version 4 that enables scaffolding for Eloquent models.

![Bamboo - example for Page model](bamboo-example.png)

### Installation
* Add `"RobGordijn/Bamboo": "dev-master"` to the require section in `composer.json`.

* Add `'RobGordijn\Bamboo\BambooServiceProvider'` to the providers array in `app/config/app.php`.
* In the `controllers` directory, create a new controller that extends the BambooController and provides an Eloquent model in the constructor.

code:

	<?php
	use RobGordijn\Bamboo\BambooController;

	class BlogController extends BambooController
	{
		public function __construct(Blog $Model)
		{
			parent::__construct($Model);
		}
	}

* Create a route to that controller.

code:

	<?php
	// a single route
	Route::resource('blogs', 'BlogController');
	
	// or within in a group with a prefix
	Route::group(array('prefix' => 'admin'), function()
	{
		Route::resource('blogs', 'BlogController');
	});

* The last step is to provide information about the structure of your model. This is done via a public method `getStructure` in the Eloquent model.

code:

	<?php
	class Blog extends Eloquent
	{
		public function getStructure()
		{
			$title = array(
				 'type' => 'string'
				,'onIndex' => true
			);
			$content = array(
				 'type' => 'text'
			);
			return compact('title',  'content');
		}
	}

* done; point your browser to `/blogs` to work with Bamboo.



### Structure options
The `getStructure` method in the Eloquent model must return an array of structures. The keys are the names of your table columns. The structure has the following options:

|key|value|explanation|
|-|-|-|
|type|(string) string, email, password, |single line text input|
||(string) text, textarea|textarea input|
||(string) radios|list of radio buttons, requires 'values'|
||(string) select|dropdown menu, requires 'values'|
|label|(string)|String used for the label|
|rules|(array)|Array with model rules, **not implemented yet**, use static Model::$rules meanwhile|
|onIndex|(bool)|Display column on the index view|
|attributes|(array)|Array with attributes used in the formbuilder|
|values|(array)|Array with possible values for type 'radios' and 'select'|

### Controller options
**Records per page (index view)**

Default: 10, specify the protected `recordsPerPage` property in the resource controller to overwrite.

**Blade layout** 

Every view uses a Blade layout to render. A default layout is shipped with Bamboo and uses  Bootstrap classes to look nice. Specify the protected `bladeLayout` property in the resource controller to overwrite.

### Translations

Bamboo ships in English, feel free to contribute.


### F.A.Q.
**What about validation?**

Specify the rules for the columns in the static property `rules`, Bamboo will pick them up when storing or updating records.

**Do I need to worry about the url of the resource controller?**

Nope, as long as you register the route to the controller with `Route::resource()`, Bamboo will figure out the rest.


**Does Bamboo generate controllers and views like [Jeffrey Way's Laravel 4 Generators](https://github.com/JeffreyWay/Laravel-4-Generators)?**

Nope, Bamboo does not generate the controllers and views for each model but reuses one controller and some views.
