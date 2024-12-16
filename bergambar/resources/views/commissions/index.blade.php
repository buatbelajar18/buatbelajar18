@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Commissions by User ID: {{ $userId }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Commission Name</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commissions as $commission)
                <tr>
                    <td>{{ $commission->id }}</td>
                    <td>{{ $commission->name }}</td>
                    <td>{{ $commission->amount }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <a href="{{ route('commissions.edit', $commission->id) }}" class="btn btn-warning">Edit</a>

                        <!-- Form Hapus -->
                        <form action="{{ route('commissions.destroy', $commission->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
