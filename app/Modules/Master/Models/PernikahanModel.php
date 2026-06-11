<?php

namespace App\Modules\Master\Models;

use App\Models\DbModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PernikahanModel extends Model
{
    protected $table = 'mst_pernikahan';
    public $timestamps = false;

    public static function loadDatatables()
    {
        $request = request();
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search.value');

        $query = DB::table('mst_pernikahan')->where('deleted_st', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pernikahan_nm', 'ilike', "%$search%")
                  ->orWhere('pernikahan_id', 'ilike', "%$search%");
            });
        }

        $recordsTotal = $query->count();

        $recordsFiltered = $query->count();

        $data = $query->orderBy('pernikahan_id')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($item) {
                return [
                    'pernikahan_id' => $item->pernikahan_id,
                    'pernikahan_nm' => $item->pernikahan_nm,
                    'satusehat_cd' => $item->satusehat_cd,
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
