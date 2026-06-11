<?php

namespace App\Modules\Master\Models;

use App\Models\DbModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AgamaModel extends Model
{
    protected $table = 'mst_agama';
    public $timestamps = false;

    public static function loadDatatables()
    {
        $request = request();
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search.value');

        $query = DB::table('mst_agama')->where('deleted_st', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agama_nm', 'ilike', "%$search%")
                  ->orWhere('agama_id', 'ilike', "%$search%");
            });
        }

        $recordsTotal = $query->count();

        $recordsFiltered = $query->count();

        $data = $query->orderBy('agama_id')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($item) {
                return [
                    'agama_id' => $item->agama_id,
                    'agama_nm' => $item->agama_nm,
                    'active_st' => $item->active_st,
                ];
            });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
}
