@extends('layouts.user')
@section('content')
    <h2 class="title-bar">
        {{ __('Manage Rooms') }}
        <div class="title-action my-3">
            <a href="{{ route('hotel.vendor.edit', ['id' => $hotel->id]) }}?lang=en" class="btn btn-info mt-2"><i
                    class="fa fa-hand-o-right"></i> {{ __('Back to hotel') }}</a>
            <a href="{{ route('hotel.vendor.room.availability.index', ['hotel_id' => $hotel->id]) }}"
                class="btn btn-warning mt-2"><i class="fa fa-calendar"></i> {{ __('Availability Rooms') }}</a>
                {{-- {{dd($rows->total())}} --}}
            @if ($rows->total() <= 0)
                <a href="{{ route('hotel.vendor.room.create', ['hotel_id' => $hotel->id]) }}" class="btn btn-success mt-2"><i
                        class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Room') }}</a>
            @endif
        </div>
    </h2>
    @include('admin.message')
    @if ($rows->total() > 0)
        <div class="bravo-list-item">
            <div class="bravo-pagination">
                <span
                    class="count-string">{{ __('Showing :from - :to of :total Rooms', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
                {{ $rows->appends(request()->query())->links() }}
            </div>
            <div class="list-item">
                <div class="row">
                    @foreach ($rows as $row)
                        <div class="col-md-12">
                            @include('Hotel::frontend.vendorHotel.room.loop-list')
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bravo-pagination">
                <span
                    class="count-string">{{ __('Showing :from - :to of :total Rooms', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
                {{ $rows->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        {{ __('No Room') }}
    @endif
@endsection