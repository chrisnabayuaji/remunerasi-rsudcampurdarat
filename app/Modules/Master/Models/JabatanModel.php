<?php

namespace App\Modules\Master\Models;

use App\Models\DbModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JabatanModel extends Model
{
    protected $table = 'mst_jabatan';
    public $timestamps = false;

    public static function loadDatatables()
    {
        $request = request();
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search.value');

        $query = DB::table('mst_jabatan')->where('deleted_st', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('jabatan_nm', 'ilike', "%$search%")
                  ->orWhere('jabatan_id', 'ilike', "%$search%");
            });
        }

        $recordsTotal = $query->count();

        $recordsFiltered = $query->count();

        $data = $query->orderBy('jabatan_id')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($item) {
                return [
                    'jabatan_id' => $item->jabatan_id,
                    'jabatan_nm' => $item->jabatan_nm,
                    'jabatan_parent' => $item->jabatan_parent,
                    'urut' => $item->urut,
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
