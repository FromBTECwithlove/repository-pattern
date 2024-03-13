@extends('layouts.pages')

@section('title', 'Home page')

@section('content')

    <h1 class="font-bold text-5xl"><a href="/">LARAVEL CRUD EXAMPLE</a></h1>

    <div class="w-full flex justify-end">
        <form action="{{ route('home.store') }}" method="POST">
            @csrf
            <div class="w-full flex h-10">
                <label class="w-full flex h-full">
                    <input type="text" name="name" class="px-4 outline-none" placeholder="Enter name...">
                </label>
                <button type="submit" class="w-max bg-green-500 text-white h-full px-5">Create</button>
            </div>
        </form>
    </div>

    @if(session()->has('message'))
        <div class="text-sm bg-green-500 text-white w-full py-3 rounded-lg px-4">
            {{ session('message') }}
        </div>
    @endif

    <table class="border-collapse border border-slate-500">
        <thead>
        <tr>
            <td colspan="10" class="p-4">
                <form action="{{ route('home.index') }}" class="flex w-full">
                    <label class="block w-full">
                        <input type="search" name="name" class="w-full h-10 px-4 outline-none"
                               placeholder="Enter name search..." autocomplete="off">
                    </label>
                    <button type="submit" class="hidden w-max bg-green-500 text-white h-full px-5">Search</button>
                </form>
            </td>
        </tr>
        <tr>
            <th class="p-3 border border-slate-600">
                <input id="parent_checkbox" title="" type="checkbox">
            </th>
            <th class="p-3 border border-slate-600">ID</th>
            <th class="p-3 border border-slate-600">Name</th>
            <th class="p-3 border border-slate-600">Slug</th>
            <th class="p-3 border border-slate-600">Create</th>
            <th class="p-3 border border-slate-600">Update</th>
            <th class="p-3 border border-slate-600">
                <div class="flex flex-col gap-1">
                    <span>Actions</span>
                    <button id="delMultiple" class="bg-red-500 text-sm text-white px-2 rounded-xl hidden"></button>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            <tr>
                <td class="p-3 border border-slate-700">
                    <input id="child_checkbox" name="checkbox" title="{{ $product->name }}"
                           value="{{ $product->id }}" type="checkbox">
                </td>
                <td class="p-3 border border-slate-700">{{ $product->id }}</td>
                <td class="p-3 border border-slate-700">{{ $product->name }}</td>
                <td class="p-3 border border-slate-700">{{ $product->slug }}</td>
                <td class="p-3 border border-slate-700">{{ $product->created_at }}</td>
                <td class="p-3 border border-slate-700">{{ $product->updated_at }}</td>
                <td class="p-3 border border-slate-700">
                    <a href="{{ route('home.edit', $product->id) }}"
                       class="bg-orange-500 cursor-pointer text-white px-2 text-sm py-1 rounded">Edit</a>
                    <a onclick="event.preventDefault(); confirm('Delete {{ $product->slug }} ?') ? document.getElementById('deleteForm').submit() : false;"
                       class="bg-red-500 cursor-pointer text-white px-2 text-sm py-1 rounded">Delete</a>

                    <form id="deleteForm" action="{{ route('home.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No product(s) available.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="flex justify-end">{{ $products->links('pagination::tailwind') }}</div>

    <div class="">
        <form id="delMultipleForm" action="{{ route('delete-multiple') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="ids" value="" class="hidden">
        </form>
    </div>
@endsection

@push('script')

    <script>
        $(function () {
            function displayDelMultiPostBtn() {
                let checkbox = $('input[name="checkbox"]:checked');
                if (checkbox.length > 0) {
                    $('button#delMultiple')
                        .text('Del ' + checkbox.length + ' item(s)')
                        .removeClass('hidden');
                } else {
                    $('button#delMultiple').addClass('hidden');
                }
            }

            $(document).on('click', '#parent_checkbox', function () {
                let checkbox = $('input[name="checkbox"]');
                let _this = this;
                if (_this.checked) {
                    checkbox.each(function () {
                        this.checked = true;
                    });
                } else {
                    checkbox.each(function () {
                        this.checked = false;
                    });
                }

                displayDelMultiPostBtn();
            });

            $(document).on('change', 'input[name="checkbox"]', function () {
                let checkbox_checked_length = $('input[name="checkbox"]:checked').length;
                let checkbox_length = $('input[name="checkbox"]').length;
                if (checkbox_length === checkbox_checked_length) {
                    $('#parent_checkbox').prop('checked', true);
                } else {
                    $('#parent_checkbox').prop('checked', false);
                }
                displayDelMultiPostBtn();
            });


            $(document).on('click', '#delMultiple', function () {
                const checked_list = [];
                let checked_box = $('input[name="checkbox"]:checked');
                checked_box.each(function () {
                    checked_list.push($(this).val());
                });

                if (checked_list.length > 0) {
                    const cf = confirm('Are you sure to delete records?');

                    if (cf) {
                        $('input[name="ids"]').val(checked_list);
                        $('#delMultipleForm').submit();
                    }
                } else {
                    alert("No post selected yet!");
                    return false;
                }
            });
        });
    </script>

@endpush
