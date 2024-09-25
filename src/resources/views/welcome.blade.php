@extends('layouts.default')
@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">CSV fájl feltöltése</h2>

        <!-- Hibakezelés -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-4 rounded-lg mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form a CSV fájl feltöltéséhez -->
        <form action="{{route('import')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="mb-4">
                <label for="file" class="block text-gray-700 font-bold mb-2">Válassz CSV fájlt:</label>
                <input type="file" name="import_csv" id="import_csv"
                       class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Feltöltés
                </button>
            </div>
        </form>
    </div>
@endsection
