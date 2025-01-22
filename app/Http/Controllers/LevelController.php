<?php

namespace App\Http\Controllers;

use App\Models\levelmodel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar level',
            'list' => ['Home', 'level']
        ];
        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level';
        $level = levelmodel::all();
        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $level = levelmodel::select('level_id', 'level_kode', 'level_nama',);

        if ($request->level_id){
            $level->where('level_id',$request->level_id);
        }
        return DataTables::of($level)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                // $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btnsm">Detail</a> ';
                // $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                // return $btn;
                $btn  = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        $breadcrummb = (object)[
            'title' => 'Tambah level',
            'list' => ['Home', 'level', 'tambah']
        ];

        $page = (object)[
            'title' => 'Tambah level baru'
        ];
        $activeMenu = 'level';
        $level = levelmodel::all();
        return view('level.create', ['breadcrumb' => $breadcrummb, 'page' => $page, 'activeMenu' => $activeMenu, 'level' => $level]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);
        levelmodel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    public function show(string $level_id)
    {
        $level = levelmodel::find($level_id);

        $breadcrumb = (object)[
            'title' => 'Detail Level',
            'list' => ['Home', 'level', 'detail']
        ];
        $page = (object)[
            'title' => 'Detail Level'
        ];
        $activeMenu = 'level';
        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $level_id)
    {
        $level = levelmodel::find($level_id);

        $breadcrumb = (object)[
            'title' => 'Edit Level',
            'list' => ['Home', 'level', 'edit']
        ];
        $page = (object)[
            'title' => 'Edit level'
        ];
        $activeMenu = 'level';
        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'level' => $level]);
    }

    public function update(Request $request, string $level_id)
    {
        $request->validate([
            'level_kode' => 'required|string|max:5|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);

        $level = levelmodel::find($level_id);
        $level->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);
        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    public function destroy(string $level_id)
    {
        $check = levelmodel::find($level_id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }
        try {
            levelmodel::destroy($level_id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax(){
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request){
        if($request->ajax()||$request->wantsJson()){
            $rules = [
                'level_kode'=>'required|string|unique:m_level,level_kode',
                'level_nama'=>'required|string|max:100'
            ];

            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Validasi Gagal',
                    'msgField'=>$validator->errors()
                ]);
            }

            levelmodel::create($request->all());
            return response()->json([
                'status'=>true,
                'message'=>'Data level berhasil disimpan'
            ]);
        }
        redirect('/');
    }
    //edit ajax 
    public function edit_ajax(string $level_id){
        $level = levelmodel::find($level_id);

        return view('level.edit_ajax',['level'=>$level]);
    }

    public function update_ajax(Request $request, $level_id)
    {
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode,' .$level_id. ',level_id',
                'level_nama'=>'required|string|max:100'
                
            ];
            // use Illuminate\Support\Facades\Validator; 
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]);
            }

            $check = levelmodel::find($level_id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $level_id){
        $level   =levelmodel::find($level_id);

        return view('level.confirm_ajax',['level'=>$level]);
    }

    public function delete_ajax(Request $request, $level_id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $level = levelmodel::find($level_id);
        
        if ($level) {
            try {
                $level->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    return redirect('/');
}
// show ajax
public function show_ajax(string $id) {
    // Cari level berdasarkan id
    $level = LevelModel::find($id);

    // Periksa apakah level ditemukan
    if ($level) {
        // Tampilkan halaman show_ajax dengan data level
        return view('level.show_ajax', ['level' => $level]);
    } else {
        // Tampilkan pesan kesalahan jika level tidak ditemukan
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}
public function import() 
    { 
        return view('level.import'); 
    }
    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_level' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
 
            $file = $request->file('file_level');  // ambil file dari request 
 
            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 
 
            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 
 
            $insert = []; 
            if(count($data) > 1){ // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati 
                        $insert[] = [ 
                            'level_kode' => $value['A'], 
                            'level_nama' => $value['B'],
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    LevelModel::insertOrIgnore($insert);    
                } 
 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            }else{ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Tidak ada data yang diimport' 
                ]); 
            } 
        } 
        return redirect('/'); 
    }
        public function export_excel()
    {
        // Ambil data dari Levelmodel
        $barang = Levelmodel::select('level_kode', 'level_nama')->get();

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Level Kode');
        $sheet->setCellValue('C1', 'Nama Level');

        // Buat header menjadi bold
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Isi data
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke-2
        foreach ($barang as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->level_kode);
            $sheet->setCellValue('C' . $baris, $value->level_nama);

            $baris++;
            $no++;
        }

        // Set ukuran kolom otomatis untuk semua kolom
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set judul sheet
        $sheet->setTitle('Data Level');

        // Buat writer untuk menulis file excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Level_' . date('Y-m-d_His') . '.xlsx';

        // Atur Header untuk Download File Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        // Simpan file dan kirim ke output
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        // Ambil data barang dari database
        $level = LevelModel::select('level_kode', 'level_nama') 
        ->get();

        // Gunakan library Dompdf untuk membuat PDF
        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);

        // Atur ukuran kertas dan orientasi
        $pdf->setPaper('A4', 'portrait');

        // Aktifkan opsi untuk memuat gambar dari URL (jika ada)
        $pdf->setOption('isRemoteEnabled', true);

        // Render PDF
        $pdf->render();

        // Download PDF
        return $pdf->stream('Data Level ' . date('Y-m-d H:i:s') . '.pdf');
    }
}