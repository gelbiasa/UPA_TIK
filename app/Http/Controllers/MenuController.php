<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\WebpEncoder;

class MenuController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Menu',
            'list' => ['Home', 'Menu'],
        ];

        $page = (object) [
            'title' => 'Manajemen Menu Sistem'
        ];

        $activeMenu = 'menu';

        $menus = Menu::whereNull('parent_id')
            ->with('children')
            ->orderBy('order_number')
            ->get();

        return view('admin.menu.index', compact('menus', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Menu',
            'list' => ['Home', 'Menu', 'Tambah'],
        ];

        $page = (object) [
            'title' => 'Tambah Menu'
        ];

        $activeMenu = 'menu';

        $parents = Menu::whereNull('parent_id')->get();
        return view('admin.menu.create', compact('parents', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:menus,slug',
            'order_number' => 'required|integer',
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('menu-thumbnails', 'public');
            }

            $page = Page::create([
                'title' => $request->name,
                'thumbnail' => $thumbnailPath,
                'content' => $request->content,
                'user_id' => Auth::id()
            ]);

            Menu::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id ?: null,
                'page_id' => $page->pages_id,
                'order_number' => $request->order_number,
                'slug' => $request->slug
            ]);

            DB::commit();
            return redirect('/menu')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();

            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('menu-images', $fileName, 'public');

            return response()->json([
                'url' => Storage::url($filePath)
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function edit($menu_id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Menu',
            'list' => ['Home', 'Menu', 'Edit'],
        ];

        $page = (object) [
            'title' => 'Edit Menu'
        ];

        $activeMenu = 'menu';

        $menu = Menu::with('page')->findOrFail($menu_id);
        $parents = Menu::whereNull('parent_id')
            ->where('menus_id', '!=', $menu_id)
            ->get();

        return view('admin.menu.edit', compact('menu', 'parents', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function update(Request $request, $menu_id)
    {
        $menu = Menu::with('page')->findOrFail($menu_id);

        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:menus,slug,' . $menu_id . ',menus_id',
            'order_number' => 'required|integer',
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $thumbnailPath = $menu->page->thumbnail;
            if ($request->hasFile('thumbnail')) {
                if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                    Storage::disk('public')->delete($thumbnailPath);
                }
                $thumbnailPath = $request->file('thumbnail')->store('menu-thumbnails', 'public');
            }

            $menu->page->update([
                'title' => $request->name,
                'thumbnail' => $thumbnailPath,
                'content' => $request->content
            ]);

            $menu->update([
                'name' => $request->name,
                'parent_id' => $request->parent_id ?: null,
                'order_number' => $request->order_number,
                'slug' => $request->slug
            ]);

            DB::commit();
            return redirect('/menu')->with('success', 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroy($menu_id)
    {
        $menu = Menu::with('page')->findOrFail($menu_id);

        DB::beginTransaction();

        try {
            if ($menu->page && $menu->page->thumbnail) {
                Storage::disk('public')->delete($menu->page->thumbnail);
            }

            if ($menu->page) {
                $menu->page->delete();
            }

            $menu->delete();

            DB::commit();
            return redirect('/menu')->with('success', 'Menu berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
