<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->get(); // Ambil menu utama

        $breadcrumb = (object) [
            'title' => 'Daftar Menu',
            'list' => ['Home', 'Daftar Menu']
        ];

        $page = (object) [
            'title' => 'Daftar Menu'
        ];

        $activeMenu = 'daftar_menu'; // Set the active menu

        return view('daftarMenu.index', [
            'menus' => $menus,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function subMenu($parentId)
    {
        $subMenus = Menu::where('parent_id', $parentId)->with('page')->get(); // Tambahkan relasi page
        $parentMenu = Menu::find($parentId);

        if (!$parentMenu) {
            abort(404, 'Menu utama tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => "Sub Menu dari {$parentMenu->name}",
            'list' => ['Home', 'Daftar Menu', $parentMenu->name]
        ];

        $page = (object) [
            'title' => "Sub Menu dari {$parentMenu->name}"
        ];

        $activeMenu = 'daftar_menu';

        return view('daftarMenu.sub_menu', [
            'subMenus' => $subMenus,
            'parentMenu' => $parentMenu,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function content($pageId)
    {
        $page = DB::table('pages')->where('pages_id', $pageId)->first();

        if (!$page) {
            abort(404, 'Halaman tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => $page->title,
            'list' => ['Home', 'Daftar Menu', $page->title]
        ];

        $pageInfo = (object) [
            'title' => $page->title
        ];

        $activeMenu = 'daftar_menu';

        return view('daftarMenu.content', [
            'page' => $page,
            'breadcrumb' => $breadcrumb,
            'pageInfo' => $pageInfo,
            'activeMenu' => $activeMenu
        ]);
    }
}
