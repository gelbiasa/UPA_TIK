<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class IndukMenuController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Menu Utama',
            'list' => ['Home', 'Menu Utama'],
        ];

        $page = (object)[
            'title' => 'Daftar menu yang terdaftar dalam sistem'
        ];

        $activeMenu = 'induk_menu'; //set menu yang aktif
        return view('indukMenu.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        // Filter hanya data dengan parent_id null
        $menu = Menu::whereNull('parent_id')->select('menus_id', 'name');

        return DataTables::of($menu)
            ->addIndexColumn() // menambahkan kolom index
            ->addColumn('aksi', function ($menu) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/indukMenu/' . $menu->menus_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/indukMenu/' . $menu->menus_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax()
    {
        Menu::select('menus_id')->get();

        return view('indukMenu.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:menus',
            ];

            // Validasi data inputan
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                // Hitung order_number berdasarkan jumlah data dengan parent_id null
                $orderNumber = Menu::whereNull('parent_id')->count() + 1;

                // Simpan data ke database
                Menu::create([
                    'name' => $request->name,
                    'parent_id' => null,
                    'page_id' => null,
                    'order_number' => $orderNumber,
                    'slug' => $request->slug,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data induk menu berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ]);
            }
        }

        // Jika bukan request Ajax, redirect ke halaman lain
        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return view('indukMenu.edit_ajax', ['menu' => null]);
        }

        return view('indukMenu.edit_ajax', ['menu' => $menu]);
    }

    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name' => 'required|string|max:100',
                'slug' => 'required|string|max:100|unique:menus,slug,' . $id . ',menus_id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $menu = Menu::find($id);
            if ($menu) {
                $menu->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $menu = Menu::find($id);
        return view('indukMenu.confirm_ajax', ['menu' => $menu]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            // Periksa apakah menu memiliki submenu
            $hasSubMenu = Menu::where('parent_id', $id)->exists();

            if ($hasSubMenu) {
                return response()->json([
                    'status' => false,
                    'message' => 'Menu tidak bisa dihapus karena memiliki sub menu di dalamnya'
                ]);
            }

            // Lanjutkan dengan penghapusan jika tidak memiliki submenu
            if ($menu->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ]);
        }

        return redirect('/');
    }
}
