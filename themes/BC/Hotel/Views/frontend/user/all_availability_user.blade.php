@extends('layouts.user')

@section('content')
    @php $services  = []; @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('Hotels Availability Calendar') }}</h1>
        </div>
        @include('admin.message')

        @if (count($rows))
            <div class="panel">
                <div class="panel-title"><strong>{{ __('Availability') }}</strong></div>
                <div class="panel-body no-padding">
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="nav nav-tabs  flex-column vertical-nav" id="items_tab" role="tablist">
                                @foreach ($rows as $k => $item)
                                    @foreach ($item->rooms as $room)
                                        <li class="nav-item event-name ">
                                            <a class="nav-link" data-id="{{ $room->id }}" data-toggle="tab"
                                                href="#calendar-161" title="{{ $room->title }}">#{{ $room->id }} -
                                                {{ $room->title }}</a>
                                        </li>
                                    @endforeach
                                    <div id="bravo_modal_calendar" class="modal fade">
                                        <div class="modal-dialog modal-lg  modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Date Information') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="row form_modal_calendar form-horizontal" novalidate
                                                        onsubmit="return false">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __('Date Ranges') }}</label>
                                                                <input readonly type="text"
                                                                    class="form-control has-daterangepicker">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __('Status') }}</label>
                                                                <br>
                                                                <label><input true-value=1 false-value=0 type="checkbox"
                                                                        v-model="form.active">
                                                                    {{ __('Available for booking?') }}</label>
                                                            </div>
                                                        </div>
                                                        {{-- @dd($item->price) --}}
                                                        <div class="col-md-6" v-show="form.active">
                                                            <div class="form-group">
                                                                <label class="mb-0">{{ __('Day Stay admin') }}</label>
                                                                <!--<small>{{ __('(Sunday - Monday - Tuesday - Wednesday)') }}</small>-->
                                                                <input type="number" v-model="form.price"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" v-show="form.active">
                                                            <div class="form-group">
                                                                <label class="mb-0">{{ __('Full Day admin') }}</label>
                                                                <!--<small>{{ __('(Sunday - Monday - Tuesday - Wednesday)') }}</small>-->
                                                                <input type="number" v-model="form.price2"
                                                                    class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6" v-show="form.active">
                                                            <div class="form-group">
                                                                <label class="mb-0">{{ __('Weekends Day Stay') }}</label>
                                                                <div class="">
                                                                    <!--<small>{{ __('This price will effect only in weekend (Friday - Saturday)') }}</small>-->
                                                                </div>
                                                                <input type="number" v-model="form.weekendsPrice"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" v-show="form.active">
                                                            <div class="form-group">
                                                                <label class="mb-0">{{ __('Weekends Full Day') }}</label>
                                                                <div class="">
                                                                    <!--<small>{{ __('This price will effect only in weekend (Friday - Saturday)') }}</small>-->
                                                                </div>
                                                                <input type="number" v-model="form.weekendsPrice2"
                                                                    class="form-control">
                                                            </div>
                                                        </div>

                                                        {{-- <div class="col-md-6" v-show="form.active">
                                                            <div class="form-group">
                                                                <label>{{ __('Number of room') }}</label>
                                                                <input type="number" v-model="form.number"
                                                                    class="form-control">
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-md-6 d-none" v-show="form.active">
                                                            <div class="form-group">
                                                                <label>{{ __('Instant Booking?') }}</label>
                                                                <br>
                                                                <label><input true-value=1 false-value=0 type="checkbox"
                                                                        v-model="form.is_instant">
                                                                    {{ __('Enable instant booking') }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="mb-0">{{ __('Host notes') }}</label>
                                                                <div class="">
                                                                    <small>{{ __('Here you can add any note to this day') }}</small>
                                                                </div>
                                                                <textarea v-model="form.note" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div v-if="lastResponse.message">
                                                        <br>
                                                        <div class="alert"
                                                            :class="!lastResponse.status ? 'alert-danger' : 'alert-success'">
                                                            @{{ lastResponse.message }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">{{ __('Close') }}</button>
                                                    <button type="button" class="btn btn-primary"
                                                        @click="saveForm({{ $item }})">{{ __('Save changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-9">
                            <div id="dates-calendar" class="dates-calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">{{ __('No hotels found') }}</div>
        @endif
        <div class="d-flex justify-content-center">
            {{ $rows->appends($request->query())->links() }}
        </div>
    </div>

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('libs/fullcalendar-4.2.0/core/main.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/fullcalendar-4.2.0/daygrid/main.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/daterange/daterangepicker.css') }}">

    <style>
        #dates-calendar .loading {}
    </style>
@endpush

@push('js')
    <script src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script src="{{ asset('libs/daterange/daterangepicker.min.js?_ver=' . config('app.asset_version')) }}"></script>
    <script src="{{ asset('libs/fullcalendar-4.2.0/core/main.js') }}"></script>
    <script src="{{ asset('libs/fullcalendar-4.2.0/interaction/main.js') }}"></script>
    <script src="{{ asset('libs/fullcalendar-4.2.0/daygrid/main.js') }}"></script>

    <script>
        var calendarEl, calendar, lastId, formModal;
        $('#items_tab').on('show.bs.tab', function(e) {
            calendarEl = document.getElementById('dates-calendar');
            lastId = $(e.target).data('id');
            if (calendar) {
                calendar.destroy();
            }
            calendar = new FullCalendar.Calendar(calendarEl, {
                buttonText: {
                    today: '{{ __('Today') }}',
                },
                plugins: ['dayGrid', 'interaction'],
                header: {},
                selectable: true,
                selectMirror: false,
                allDay: false,
                editable: false,
                eventLimit: true,
                defaultView: 'dayGridMonth',
                firstDay: daterangepickerLocale.first_day_of_week,
                events: {
                    url: "{{ route('hotel.vendor.room.availability.loadDates.all') }}",
                    extraParams: {
                        id: lastId,
                    }
                },
                loading: function(isLoading) {
                    if (!isLoading) {
                        $(calendarEl).removeClass('loading');
                    } else {
                        $(calendarEl).addClass('loading');
                    }
                },
                select: function(arg) {
                    formModal.show({
                        start_date: moment(arg.start).format('YYYY-MM-DD'),
                        end_date: moment(arg.end).format('YYYY-MM-DD'),
                    });
                },
                eventClick: function(info) {
                    var form = Object.assign({}, info.event.extendedProps);
                    form.start_date = moment(info.event.start).format('YYYY-MM-DD');
                    form.end_date = moment(info.event.start).format('YYYY-MM-DD');
                    formModal.show(form);
                    // console.log(info);
                },
                eventRender: function(info) {
                    $(info.el).find('.fc-title').html(info.event.extendedProps.title);
                    $(info.el).find('.fc-title2').html(info.event.extendedProps.title2);
                    // console.log(info);
                }
            });
            calendar.render();
        });

        $('.event-name:first-child a').trigger('click');

        formModal = new Vue({
            el: '#bravo_modal_calendar',
            data: {
                lastResponse: {
                    status: null,
                    message: ''
                },
                form: {
                    id: '',
                    note: '',
                    price: '',
                    price2: '',
                    weekendsPrice: '',
                    weekendsPrice2: '',
                    start_date: '',
                    end_date: '',
                    is_instant: '',
                    enable_person: 0,
                    min_guests: 0,
                    max_guests: 0,
                    active: 0,
                    number: 1
                },
                formDefault: {
                    id: '',
                    note: '',
                    price: '',
                    price2: '',
                    weekendsPrice: '',
                    weekendsPrice2: '',
                    start_date: '',
                    end_date: '',
                    is_instant: '',
                    enable_person: 0,
                    min_guests: 0,
                    max_guests: 0,
                    active: 0,
                    number: 1
                },
                person_types: [],
                person_type_item: {
                    name: '',
                    desc: '',
                    min: '',
                    max: '',
                    note: '',
                    price: '',
                    price2: '',
                    weekendsPrice: '',
                    weekendsPrice2: '',
                },
                onSubmit: false
            },
            methods: {
                show: function(form) {
                    $(this.$el).modal('show');
                    this.lastResponse.message = '';
                    this.onSubmit = false;

                    if (typeof form != 'undefined') {
                        this.form = Object.assign({}, form);
                        if (typeof this.form.person_types == 'object') {
                            this.person_types = Object.assign({}, this.form.person_types);
                        }

                        if (form.start_date) {
                            var drp = $('.has-daterangepicker').data('daterangepicker');
                            drp.setStartDate(moment(form.start_date).format(bookingCore.date_format));
                            drp.setEndDate(moment(form.end_date).format(bookingCore.date_format));
                        }
                    }
                },
                hide: function() {
                    $(this.$el).modal('hide');
                    this.form = Object.assign({}, this.formDefault);
                    this.person_types = [];
                },
                saveForm: function(item) {
                    this.form.target_id = lastId;
                    var hotel_id = item.id;
                    var me = this;
                    me.lastResponse.message = '';
                    if (this.onSubmit) return;

                    if (!this.validateForm()) return;

                    if (this.form.price == "") me.form.price = 0;
                    if (this.form.price2 == "") me.form.price2 = 0;
                    if (this.form.weekendsPrice == "") me.form.weekendsPrice = 0;
                    if (this.form.weekendsPrice2 == "") me.form.weekendsPrice2 = 0;
                    // console.log(me.form);

                    this.onSubmit = true;
                    this.form.person_types = Object.assign({}, this.person_types);
                    var url = "{{ route('hotel.vendor.room.availability.store', ':hotel_id') }}"
                    url = url.replace(':hotel_id', hotel_id);
                    $.ajax({
                        url: url,
                        data: this.form,
                        dataType: 'json',
                        method: 'post',
                        success: function(json) {
                            if (json.status) {
                                if (calendar)
                                    calendar.refetchEvents();
                                me.hide();
                            }
                            me.lastResponse = json;
                            me.onSubmit = false;
                        },
                        error: function(e) {
                            me.onSubmit = false;
                        }
                    });
                },
                validateForm: function() {
                    if (!this.form.start_date) return false;
                    if (!this.form.end_date) return false;
                    // if (!this.form.price) return false;
                    // if (!this.form.price2) return false;
                    return true;
                },
                addItem: function() {
                    // console.log(this.person_types);
                    this.person_types.push(Object.assign({}, this.person_type_item));
                },
                deleteItem: function(index) {
                    this.person_types.splice(index, 1);
                }
            },
            created: function() {
                var me = this;
                this.$nextTick(function() {
                    $('.has-daterangepicker').daterangepicker({
                            "locale": {
                                "format": bookingCore.date_format
                            }
                        })
                        .on('apply.daterangepicker', function(e, picker) {
                            // console.log(picker);
                            me.form.start_date = picker.startDate.format('YYYY-MM-DD');
                            me.form.end_date = picker.endDate.format('YYYY-MM-DD');
                            // console.log(picker);
                        });

                    $(me.$el).on('hide.bs.modal', function() {

                        this.form = Object.assign({}, this.formDefault);
                        this.person_types = [];

                    });

                })
            },
            mounted: function() {
                // $(this.$el).modal();
            }
        });
    </script>
@endpush
