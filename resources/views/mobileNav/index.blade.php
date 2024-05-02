@extends('admin.layouts.app')
<link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
@php
    use App\Models\HideShowNav;
    $main_color = setting_item('style_main_color', '#5191fa');
    $hideShowNav = HideShowNav::find(1);
@endphp
@section('content')
    <div class="container-fluid">
        @include('admin.message')
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('Mobile Navbar') }}</h1>
            <div class="title-actions">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".createItem"><i
                        class="fa fa-plus"></i> {{ __('Add Item') }}</button>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="col-right">
                    <div>
                        To add icon go to <a href="https://fontawesome.com/v4/icons/">font awsome</a> or <a
                            href="https://icofont.com/">Icofont</a> and take the icon code like
                        the example:
                    </div>
                    <div>
                        ex: &lt;i class="fa fa-home"&gt;&lt;/i&gt; all you need to do is take (fa fa-home) and add it to the
                        icon or &lt;i class="icofont-dashboard"&gt;&lt;/i&gt; and copy the icon code (icofont-dashboard).
                    </div>
                </div>
                <form action="{{ route('mobile.navbar.hide.show') }}" method="post">
                    @method('put')
                    @csrf
                    <input type="hidden" value="1" name="status" />
                    </p>
                    <button type="submit" class="btn btn-primary">
                        @if (!$hideShowNav->status)
                            {{ __('Show') }}
                        @else
                            {{ __('Hide') }}
                        @endif
                    </button>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="panel-title"><strong>{{ __('User Mobile Navbar') }}</strong></div>
                <div class="col-right">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th> {{ __('Icon') }}</th>
                                    <th> {{ __('Icon name') }}</th>
                                    <th> {{ __('Icon code') }}</th>
                                    <th> {{ __('Icon size') }}</th>
                                    <th> {{ __('Url') }}</th>
                                    <th> {{ __('Order') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($items))
                                    @foreach ($items as $item)
                                        @if ($item->type == 'user')
                                            <tr>
                                                <td><i class="fa {{ $item->icon }} fa-2x"></i></td>
                                                <td>{{ $item->text }}</td>
                                                <td>{{ $item->icon }}</td>
                                                <td>{{ $item->size }}</td>
                                                <td>{{ $item->url }}</td>
                                                <td>{{ $item->order }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                                        data-target=".editItem{{ $item->id }}"><i
                                                            class="fa fa-edit"></i>
                                                        {{ __('Edit') }}</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target=".deleteItem{{ $item->id }}"><i
                                                            class="fa fa-trash"></i>
                                                        {{ __('Delete') }}</button>
                                                </td>
                                            </tr>
                                            {{-- edit modal --}}
                                            <div class="modal fade editItem{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Navbar Item
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('mobile.navbar.update', $item) }}"
                                                                method="post">
                                                                @method('put')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Name</label>
                                                                    <input name="text" required type="text"
                                                                        class="form-control" value="{{ $item->text }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Icon</label>
                                                                    <input name="icon" required type="text"
                                                                        class="form-control" value="{{ $item->icon }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Icon
                                                                        size</label>
                                                                    <input name="size" required type="text"
                                                                        class="form-control" value="{{ $item->size }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Url</label>
                                                                    <input name="url" required type="text"
                                                                        class="form-control" value="{{ $item->url }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Order</label>
                                                                    <input name="order" required type="number"
                                                                        class="form-control" value="{{ $item->order }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Navbar type</label>
                                                                    <select required name="type" class="form-control">
                                                                        <option
                                                                            @if ($item->type == 'user') selected @endif
                                                                            value="user">User</option>
                                                                        <option
                                                                            @if ($item->type == 'admin') selected @endif
                                                                            value="admin">Admin</option>
                                                                    </select>
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary">{{ __('Save') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Destroy modal --}}
                                            <div class="modal fade deleteItem{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Navbar
                                                                Item
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('mobile.navbar.destroy', $item) }}"
                                                                method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <p>Do you want to delete this item ({{ $item->text }})?
                                                                </p>
                                                                <button type="submit"
                                                                    class="btn btn-primary">{{ __('Save') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center pt-5">
            <nav class="navbar navbar-light bg-light align-items-end py-0">
                @if (!empty($items))
                    @foreach ($items as $item)
                        @if ($item->type == 'user')
                            <a class="navbar-brand text-center" style="color:{{ $main_color }}"
                                href="{{ $item->url }}"><i class="{{ $item->icon }} fa-{{ $item->size }}x"></i>
                                <div class="h6 my-0 py-0 text-dark"><small>{{ __($item->text) }}</small></div>
                            </a>
                        @endif
                    @endforeach
                @endif
            </nav>
        </div>

        <div class="panel mt-4">
            <div class="panel-body">
                <div class="panel-title"><strong>{{ __('Admin Mobile Navbar') }}</strong></div>
                <div class="col-right">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th> {{ __('Icon') }}</th>
                                    <th> {{ __('Icon name') }}</th>
                                    <th> {{ __('Icon code') }}</th>
                                    <th> {{ __('Icon size') }}</th>
                                    <th> {{ __('Url') }}</th>
                                    <th> {{ __('Order') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($items))
                                    @foreach ($items as $item)
                                        @if ($item->type == 'admin')
                                            <tr>
                                                <td><i class="fa {{ $item->icon }} fa-2x"></i></td>
                                                <td>{{ $item->text }}</td>
                                                <td>{{ $item->icon }}</td>
                                                <td>{{ $item->size }}</td>
                                                <td>{{ $item->url }}</td>
                                                <td>{{ $item->order }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                                        data-target=".editItem{{ $item->id }}"><i
                                                            class="fa fa-edit"></i>
                                                        {{ __('Edit') }}</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target=".deleteItem{{ $item->id }}"><i
                                                            class="fa fa-trash"></i>
                                                        {{ __('Delete') }}</button>
                                                </td>
                                            </tr>
                                            {{-- edit modal --}}
                                            <div class="modal fade editItem{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Navbar
                                                                Item
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('mobile.navbar.update', $item) }}"
                                                                method="post">
                                                                @method('put')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Name</label>
                                                                    <input name="text" required type="text"
                                                                        class="form-control" value="{{ $item->text }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Icon</label>
                                                                    <input name="icon" required type="text"
                                                                        class="form-control" value="{{ $item->icon }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Icon
                                                                        size</label>
                                                                    <input name="size" required type="text"
                                                                        class="form-control" value="{{ $item->size }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Url</label>
                                                                    <input name="url" required type="text"
                                                                        class="form-control" value="{{ $item->url }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Order</label>
                                                                    <input name="order" required type="number"
                                                                        class="form-control" value="{{ $item->order }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Navbar type</label>
                                                                    <select required name="type" class="form-control">
                                                                        <option
                                                                            @if ($item->type == 'user') selected @endif
                                                                            value="user">User</option>
                                                                        <option
                                                                            @if ($item->type == 'admin') selected @endif
                                                                            value="admin">Admin</option>
                                                                    </select>
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary">{{ __('Save') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Destroy modal --}}
                                            <div class="modal fade deleteItem{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Navbar
                                                                Item
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('mobile.navbar.destroy', $item) }}"
                                                                method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <p>Do you want to delete this item ({{ $item->text }})?
                                                                </p>
                                                                <button type="submit"
                                                                    class="btn btn-primary">{{ __('Save') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center pt-5">
            <nav class="navbar navbar-light bg-light align-items-end py-0">
                @if (!empty($items))
                    @foreach ($items as $item)
                        @if ($item->type == 'admin')
                            <a class="navbar-brand text-center" style="color:{{ $main_color }}"
                                href="{{ $item->url }}"><i class="{{ $item->icon }} fa-{{ $item->size }}x"></i>
                                <div class="h6 my-0 py-0 text-dark"><small>{{ __($item->text) }}</small></div>
                            </a>
                        @endif
                    @endforeach
                @endif
            </nav>
        </div>

        {{-- Create model --}}
        <div class="modal fade createItem" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Navbar Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('mobile.navbar.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label">Name</label>
                                <input name="text" required type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Icon</label>
                                <input name="icon" required type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Icon size</label>
                                <input name="size" required type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Url</label>
                                <input name="url" required type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Order</label>
                                <input name="order" required type="number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Navbar type</label>
                                <select required name="type" class="form-control">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
