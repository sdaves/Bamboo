<?php namespace RobGordijn\Bamboo;

class BambooController extends \BaseController
{
	/**
	 * Number of records per page.
	 * @var integer
	 */
	protected $recordsPerPage = 10;

	/**
	 * column for orderBy() on index view
	 * @var string
	 */
	protected $orderByColumn = '';

	/**
	 * direction for orderBy() on index view
	 * @var string
	 */
	protected $orderByDirection = 'asc';

	/**
	 * The Blade view used for the layout.
	 * @var string
	 */
	protected $bladeLayout = 'Bamboo::layout';

	/**
	 * The namespace hint for the translations.
	 * @var string
	 */
	protected $translateHint = 'Bamboo::';

	/**
	 * The directory for the resource views.
	 * @var string
	 */
	protected $viewDir = 'Bamboo::';

	/**
	 * Storage of current Eloquent model
	 * @var Eloquent
	 */
	protected $Model;

	/**
	 * Storage for current route name.
	 * @var string
	 */
	protected $routeName = '';

	/**
	 * Storage for the parsed Model structure.
	 * @var array
	 */
	protected $structure = array();

	/**
	 * Default structure.
	 * @var array
	 */
	protected $defaultStructure = array(
		 'type' 		=> 'string'
		,'label' 		=> null
		,'rules' 		=> array() // @todo
		,'onIndex' 		=> false
		,'attributes' 	=> array()
		,'values' 		=> array()
	);

	/**
	 * Title.
	 * @var string
	 */
	protected $title = 'Bamboo - scaffolding for Laravel 4';

	/**
	 * Constructor, sets Model and current route name.
	 * @param Eloquent $Model
	 */
	public function __construct(\Eloquent $Model)
	{
		$this->setModel($Model);
		$this->setRouteName($this->parseRouteName());
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$columns 			= $this->getIndexColumns();
		$orderByColumn 		= empty($this->orderByColumn) ? $this->Model->getKeyName() : $this->orderByColumn;
		$orderByDirection 	= $this->orderByDirection;
		$records 			= $this->Model->orderBy($orderByColumn, $orderByDirection)->paginate($this->recordsPerPage);
		$links 				= $records->links();

		return $this->makeView('index', array(
			 'columns' 	=> $columns
			,'records' 	=> $records
			,'links' 	=> $links
		));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$columns = $this->getStructure();

		return $this->makeView('create', array(
			 'columns' => $columns
		));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();
		$validation = \Validator::make($input, $this->getRules());

		if($validation->passes())
		{
			$record = $this->Model->create($input);
			return \Redirect::route($this->routeName . 'show', $record->getKey());
		}

		return \Redirect::route($this->routeName . 'create')->withInput()->withErrors($validation);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$columns 	= $this->getStructure();
		$record 	= $this->Model->findOrFail($id);

		return $this->makeView('show', array(
			 'columns' 	=> $columns
			,'record' 	=> $record
		));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$columns 	= $this->getStructure();
		$record 	= $this->Model->findOrFail($id);

		return $this->makeView('edit', array(
			 'columns' 	=> $columns
			,'record' 	=> $record
		));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = \Input::all();
		$validation = \Validator::make($input, $this->getRules());

		if($validation->passes())
		{
			$record = $this->Model->find($id)->update($input);
			return \Redirect::route($this->routeName . 'show', $id);
		}

		return \Redirect::route($this->routeName . 'edit', $id)->withInput()->withErrors($validation);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->Model->findOrFail($id)->delete();

		return \Redirect::route($this->routeName . 'index');
	}

	/**
	 * Create the html label for column
	 * 
	 * @param  string $column
	 * @param  array $structure
	 * @return string
	 */
	public static function label($column, $structure)
	{
		$label = empty($structure['label']) ? $column :  $structure['label'];
		return \Form::label($label);
	}

	/**
	 * Create the html field for column
	 * 
	 * @param  string $column
	 * @param  array $structure
	 * @return string
	 */
	public static function field($column, $structure)
	{
		switch($structure['type'])
		{
			case 'string':
				return \Form::text($column, null, $structure['attributes']);
				break;
			case 'hidden':
				return \Form::hidden($column, null, $structure['attributes']);
				break;
			case 'password':
				return \Form::password($column, null, $structure['attributes']);
				break;
			case 'email':
				return \Form::email($column, null, $structure['attributes']);
				break;

			case 'text':
			case 'textarea':
				return \Form::textarea($column, null, $structure['attributes']);
				break;

			case 'radios':
				$html = '';
				foreach($structure['values'] as $value => $display)
				{
					$html .= \Form::radio($column, $value, null, $structure['attributes']) . " " . $display . "<br>\n";
				}
				return $html;
				break;

			case 'select':
				return \Form::select($column, $structure['values'], null, $structure['attributes']);
				break;
		}
	}

	/**
	 * Returns the current Eloquent model
	 *
	 * @return Eloquent
	 */
	protected function getModel()
	{
		return $this->Model;
	}

	/**
	 * Set the current Eloquent model
	 *
	 * @param  Eloquent  $Model
	 * @return void
	 */
	protected function setModel($Model)
	{
		$this->Model = $Model;
	}

	/**
	 * Returns the current route name.
	 *
	 * @return Eloquent
	 */
	protected function getRouteName()
	{
		return $this->routeName;
	}

	/**
	 * Sets the current route name.
	 * 
	 * @param string $routeName
	 */
	protected function setRouteName($routeName)
	{
		$this->routeName = $routeName;
	}

	/**
	 * Parse the current route name from the Laravel Route
	 *
	 * @return string
	 */
	protected function parseRouteName()
	{
		$name = explode('.', \Route::currentRouteName());
		array_pop($name);
		return implode($name, '.') . '.';
	}

	/**
	 * Make a Reponse object for the resource methods.
	 *
	 * @return Response
	 */
	protected function makeView($view, array $data = array())
	{
		$default = array(
			 'title' 			=> $this->title
			,'Model' 			=> $this->Model
			,'routeName' 		=> $this->routeName
			,'viewDir' 			=> $this->viewDir
			,'bladeLayout' 		=> $this->bladeLayout
			,'translateHint' 	=> $this->translateHint
			,'viewName' 		=> $view
		);
		$data = array_merge($default, $data);

		return \View::make($this->viewDir . '/' . $view, $data);
	}

	/**
	 * Retrieve structure from model, once.
	 *
	 * @return array
	 */
	protected function getStructure()
	{
		if(empty($this->structure))
		{
			$this->loadStructure();
		}
		return $this->structure;
	}

	/**
	 * Get structure and parse all columns.
	 *
	 * @return void
	 */
	protected function loadStructure()
	{
		foreach($this->Model->getStructure() as $column => $structure)
		{
			$this->structure[ $column ] = $this->parseStructure($column, $structure);
		}
	}

	/**
	 * Merge current structure with a default
	 *
	 * @return array
	 */
	protected function parseStructure($column, array $structure)
	{
		return array_merge($this->defaultStructure, $structure);
	}

	/**
	 * Get all rules from model, looks at:
	 * 1) a static $rules property
	 * 2) rules element in the column structure @todo
	 *
	 * @return array
	 */
	protected function getRules()
	{
		$c = get_class($this->Model);
		return isset($c::$rules) ? $c::$rules : array();
	}

	/**
	 * Get the columns for the index view.
	 *
	 * @return array
	 */
	protected function getIndexColumns()
	{
		return array_keys(array_filter($this->getStructure(), function($column){
			return $column['onIndex'];
		}));
	}
}