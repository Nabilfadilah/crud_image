<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest('id')->get();
        return view('admin.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Add New User";
        return view('admin.add_edit_user', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'photo' => 'mimes:png,jpeg,jpg|max:2048',
            ]
        );

        $filePath = public_path('upload');
        $insert = new User();
        $insert->name = $request->name;
        $insert->email = $request->email;
        $insert->password = bcrypt('password');

        // if ($request->hasfile('photo')) {
        //     $file = $request->file('photo');
        //     // $file_name = time() . $file->getClientOriginalName();
        //     $file_name = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());


        //     $file->move($filePath, $file_name);
        //     $insert->photo = $file_name;
        // }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $file_name = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('upload'), $file_name);
            $insert->photo = $file_name;
        }


        $result = $insert->save();
        Session::flash('success', 'User registered successfully');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Update User";
        $edit = User::findOrFail($id);
        return view('admin.add_edit_user', compact('edit', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'photo' => 'mimes:png,jpeg,jpg|max:2048',
        ]);

        $update = User::findOrFail($id);
        $update->name = $request->name;
        $update->email = $request->email;

        // Proses foto jika ada file baru yang diupload
        if ($request->hasfile('photo')) {
            $filePath = public_path('upload'); // Pastikan direktori benar
            $file = $request->file('photo');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file->move($filePath, $file_name);

            // Hapus foto lama jika ada
            if (!is_null($update->photo)) {
                $oldImage = public_path('upload/' . $update->photo);
                if (File::exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            $update->photo = $file_name; // Update nama file di database
        }

        // Simpan data ke database
        $update->save();

        // Redirect dengan flash message
        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
