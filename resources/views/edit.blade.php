@extends('layouts.pages')

@section('title', 'Edit '. $product->name)

@section('content')

    <div class="relative w-full h-full flex items-center justify-center">
        <div class="w-full flex flex-col gap-5">
            <h1 class="font-bold text-5xl">Edit {{ $product->name }}</h1>

            @if(session()->has('message'))
                <div class="text-sm bg-green-500 text-white w-full py-3 rounded-lg px-4">
                    {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('home.update', $product->id) }}" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $product->id }}" class="hidden">
                <label class="flex flex-col mb-3">
                    <span class="font-semibold">Name</span>
                    <input type="text" name="name" value="{{ $product->name }}" placeholder="Enter name..."
                           class="px-4 py-3 rounded-lg">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col">
                    <span class="font-semibold">Slug</span>
                    <span class="px-4 py-3 bg-gray-300 rounded-lg">
                        {{ $product->slug }}
                    </span>
                </label>

                <div class="w-full flex justify-end items-center gap-3">
                    <a href="{{ route('home.index') }}" class="px-5 py-2 bg-gray-500 text-white rounded-lg">Cancel</a>
                    <button type="submit"
                            class="px-5 py-2 bg-blue-500 text-white rounded-lg">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
