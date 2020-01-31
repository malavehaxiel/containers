<?php

namespace MalaveHaxiel\Container\Repositories;

use Illuminate\Http\Request;

abstract class Container {

    abstract public function getModel();

    public function new($request = array())
	{
		if ($request instanceof Request) $request = $request->toArray();

		$instance = $this->getModel();
		$instance->fill($request);

		return $instance;
    }
    
    public function toSelect($estatus = true, array $options = array(), $closure = null)
	{
		$query = $this->newQuery();

		if (! is_null($estatus)) $query->active($estatus);

		$query->orderBy($options['order'] ?? $options['value'] ?? 'descripcion', $options['mode'] ?? 'ASC');

		if (is_callable($closure)) $query = $closure($query);

		return $query->get()->pluck(
			$options['value'] ?? 'descripcion', 
			$options['key'] ?? 'id'
		);
    }

    public function all()
    {
    	return $this->__call('get', array());
    }
    
    public function __call($method, $params)
	{		
		$query = $this->getModel()->newQuery();

		return call_user_func_array([$query, $method], $params);
	}
}
