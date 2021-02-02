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
        if (!$request->ajax()) {
            return $next($request);
        }

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

        // Limpiamos y transformamos datos
        $this->clean($request);

        // Si no está seteado ningún modelo, pasamos el control
        if (!property_exists(Route::current()->controller, 'datatablesModel')) {
            return $next($request);
        }

        $modelClass = Route::current()->controller->datatablesModel;

        // Si el modelo no existe, no es accesible, étc, pasamos el control
        if (!class_exists($modelClass)) {
            return $next($request);
        }

        // Creamos una nueva query
        $query = $modelClass::query();

        // Omitir registros iniciales
        if($request->has('start')) {
            $query->skip($request->input('start'));
        }

        // Cantidad de registros a devolver
        if($request->has('length')) {
            $query->take($request->input('length'));
        }

        // Campos por los que ordenar
        if($request->has('order')) {
            foreach($request->input('order') as $order) {
                $column = $this->getColumnName($request, $order['column']);
                if (!$column) continue;

                $query->orderBy($column, $order['dir']);
            }
        }

        // Campos por los que filtrar
        if ($request->has('columns')) {
            foreach($request->input('columns.*.search') as $idx => $search) {
                if ($search['value'] == null) continue;
                if ($search['regex']) continue; // Búsquedas regex no soportadas

                $column = $this->getColumnName($request, $idx);
                if (!$column) continue;

                // Filtro para mostrar todos los status
                if ($column == 'status_id' && $search['value'] == '-1') {
                    $query->whereNotNull('status_id');
                    continue;
                } else if ($column == 'status_id' && $search['value'] != '-1') {
                    $query->where('status_id', $search['value']);
                    continue;
                }

                $query->where($column, 'like', '%' . $search['value'] . '%');
            }
        }

        // Seteamos la query en la request
        $request->datatablesQuery = $query;

        // Pasamos el control
        $response = $next($request);

        // Si hay adjuntos datos en la respuesta, los procesamos
        if (!property_exists($response, 'datatablesOutput')) {
            return $response;
        }

        $data = $response->datatablesOutput;
        unset($response->datatablesOutput);

        $output = [];
        $output['draw'] = $request->input('draw');
        $output['data'] = $data;
        $output['recordsFiltered'] = $query->count();

        if ($modelClass) {
            $output['recordsTotal'] = $modelClass::count();
        } else {
            $output['recordsTotal'] = $query->count();
        }

        $response->setContent(json_encode($output));

        $response->header('Content-Type', 'application/json');

        return $response;
    }

    private function getColumnName(Request $request, int $idx)
    {
        $column = $request->input('columns.' . $idx . '.name');
        if (!$column) $column = $request->input('columns.' . $idx . '.data');
        if (!$column) return null;

        return $column;
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
