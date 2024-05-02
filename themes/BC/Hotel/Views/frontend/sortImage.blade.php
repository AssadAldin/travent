@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sort {{$hotel->title}} Images</h2>
        <div class="row content">
            @if(count($files))
                @foreach(collect($files)->sortBy('priority') as $file)
                @if($file != null)
                        <div class="col-md-2 mb-2">
                            <!-- trigger modal -->
{{--                            {{$file->id}}--}}
                            {{__('Priority')}} : {{$file->priority}}
                            <a class="text-decoration-none" data-toggle="modal" data-target="#exampleModal{{$file->id}}">
                                <img style="cursor: move!important;" src="<?php echo \Modules\Media\Helpers\FileHelper::url($file, 'thumb') ?>" class="img-fluid" alt="{{$file}}">
                            </a>
                        </div>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="exampleModal{{$file->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{__("Sort")}} {{$hotel->title}} {{__("images")}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('sort.image.update', $file->id) }}" method="post" class="form-inline w-100">
                                            @csrf
                                            @method('put')
                                            <div class="form-group mx-sm-3 mb-2">
                                                <label for="priority" class="sr-only">Priority</label>
                                                <input type="text" name="priority" class="form-control" value="{{$file->priority}}" id="priority" placeholder="priority">
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2">Confirm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endsection
