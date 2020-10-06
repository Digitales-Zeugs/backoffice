<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\TransformsRequest as Middleware;
use Illuminate\Support\Facades\Route;
use Closure;

class DataTables extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax()) {
            // https://datatables.net/manual/server-side
            $request->validate([
                'draw'                   => 'nullable|integer',
                'start'                  => 'nullable|integer',
                'length'                 => 'nullable|integer',
                'search.value'           => 'nullable|string',
                'search.regex'           => 'nullable|string|in:true,false',
                'order.*.column'         => 'nullable|integer',
                'order.*.dir'            => 'nullable|string',
                'columns.*.data'         => 'nullable|string',
                'columns.*.name'         => 'nullable|string',
                'columns.*.searchable'   => 'nullable|string|in:true,false',
                'columns.*.orderable'    => 'nullable|string|in:true,false',
                'columns.*.search.value' => 'nullable|string',
                'columns.*.search.regex' => 'nullable|string|in:true,false',
            ]);

            $this->clean($request);

            if (property_exists(Route::current()->controller, 'datatablesModel')) {
                $modelClass = Route::current()->controller->datatablesModel;
                if (class_exists($modelClass)) {
                    $query = $modelClass::query();

                    if($request->has('start')) {
                        $query->skip($request->input('start'));
                    }

                    if($request->has('length')) {
                        $query->take($request->input('length'));
                    }

                    if($request->has('order')) {
                        foreach($request->input('order') as $order) {
                            $column = $request->input('columns.' . $order['column'] . '.name');
                            if (!$column) $column = $request->input('columns.' . $order['column'] . '.data');
                            if (!$column) continue;

                            $query->orderBy($column, $order['dir']);
                        }
                    }

                    if ($request->has('columns')) {
                        foreach($request->input('columns.*.search') as $idx => $search) {
                            if ($search['value'] == null) continue;
                            if ($search['regex']) continue; // Búsquedas regex no soportadas
                            
                            $column = $request->input('columns.' . $idx . '.name');
                            if (!$column) $column = $request->input('columns.' . $idx . '.data');
                            if (!$column) continue;
            
                            $query->where($column, 'like', '%' . $search['value'] . '%');
                        }
                    }

                    $request->datatablesQuery = $query;
                }
            }
        }

        return $next($request);
    }

    protected function transform(string $key, $value)
    {
        if (!is_string($value)) {
            return $value;
        }

        if ($value === 'true') {
            return true;
        } else if ($value === 'false') {
            return false;
        }

        return $value;
    }
}
