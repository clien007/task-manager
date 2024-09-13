<?php

namespace App\Traits;

use App\Models\Task;

trait FiltersTasks
{
    /**
     * Apply filters to the task query builder based on the request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyFilters($query, $request)
    {
        // Filter by status if present
        if ($request->has('status') && $request->input('status') !== NULL) {
            $query->where('status_id', $request->input('status'));
        }

        // Filter by category if present
        if ($request->has('category') && $request->input('category') !== NULL) {
            $query->where('category_id', $request->input('category'));
        }

        return $query;
    }
}
