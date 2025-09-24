@extends('layouts.auth.app')

@push('title')
    {{ 'Create Password' }}
@endpush

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-center mb-6">Create Password</h2>
            <p class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-sm">
                {!! $info !!}
            </p>

            <form method="POST" action="{{ route('auth.create-password.update') }}">
                @csrf

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password_confirmation')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="cursor-pointer w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Save Password
                </button>
                <a href="{{ route('auth.create-password.skip') }}" class="mt-4 block text-center cursor-pointer w-full bg-slate-400 text-white py-2 rounded-lg hover:bg-slate-500 transition duration-200">Skip it</a>
            </form>
        </div>
    </div>
@endsection
