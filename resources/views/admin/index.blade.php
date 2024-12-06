@extends('layouts.content')
@section('main-content')
    <div class="container">
        <h2>
            Laravel 11 CRUD with upload image
        </h2>
        <div class="text-end mb-5">
            <a href="{{ route('user.create') }}" class="btn btn-primary">Add New User</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-primary">
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @forelse ($users as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>

                            <td>
                                <div class="showPhoto">
                                    <div id="imagePreview"
                                        style="@if ($row->photo != '') background-image: url('{{ asset('upload/' . $row->photo) }}');
                                              @else 
                                                background-image: url('{{ asset('img/avatar.png') }}'); @endif;">
                                    </div>
                                </div>
                            </td>

                            <td>
                                <a href="" class="btn btn-primary"> Edit</a>
                                <button class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Users Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

<style>
    #imagePreview {
        width: 50px;
        /* Atur ukuran sesuai kebutuhan */
        height: 50px;
        /* Atur ukuran sesuai kebutuhan */
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border: 1px solid #ddd;
        /* Opsional: tambahkan border untuk cek area elemen */
    }

    .showPhoto {
        width: 51%;
        height: 54%;
        margin: auto;
    }

    .showPhoto>div {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>