<?php

namespace App\Modules\Master\Models;

use App\Models\DbModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StatusPegawaiModel extends Model
{
    protected $table = 'mst_status_pegawai';
    public $timestamps = false;

    public static function loadDatatables()
    {
        $request = request();
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search.value');

        $query = DB::table('mst_status_pegawai')->where('deleted_st', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('statuspegawai_nm', 'ilike', "%$search%")
                  ->orWhere('statuspegawai_id', 'ilike', "%$search%");
            });
        }

        $recordsTotal = $query->count();

        $recordsFiltered = $query->count();

        $data = $query->orderBy('statuspegawai_id')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($item) {
                return [
                    'statuspegawai_id' => $item->statuspegawai_id,
                    'statuspegawai_nm' => $item->statuspegawai_nm,
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
